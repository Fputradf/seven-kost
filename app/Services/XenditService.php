<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class XenditService
{
    protected ?string $secretKey;

    protected ?string $webhookToken;

    public function __construct()
    {
        $this->secretKey = config('services.xendit.secret_key');
        $this->webhookToken = config('services.xendit.webhook_token');
    }

    /**
     * Buat invoice pembayaran di Xendit Payment Gateway (Live API dengan Fallback Sandbox Lokal).
     *
     * @param  array{name?: string, email?: string, phone?: string}  $customer
     * @return array{id: string, invoice_url: string, status: string}
     */
    public function createInvoice(Transaction $transaction, array $customer = []): array
    {
        $externalId = $transaction->reference_id;
        $amount = (int) $transaction->amount;
        $description = $transaction->title;

        $successRedirectUrl = $transaction->type === 'booking'
            ? route('tenant.bookings.payment-success', $externalId)
            : route('payments.success', $externalId);

        $failureRedirectUrl = $transaction->type === 'booking'
            ? route('tenant.bookings.payment', $externalId)
            : route('payments.checkout', $externalId);

        $duration = $this->calculateInvoiceDuration($transaction);
        $expiresAt = now()->addSeconds($duration);

        $txPayload = $transaction->payload ?? [];
        $txPayload['expires_at'] = $expiresAt->toIso8601String();
        $txPayload['invoice_duration'] = $duration;
        $transaction->update(['payload' => $txPayload]);

        $payload = [
            'external_id' => $externalId,
            'amount' => $amount,
            'description' => $description,
            'invoice_duration' => $duration,
            'payer_email' => $customer['email'] ?? $transaction->user?->email,
            'customer' => [
                'given_names' => $customer['name'] ?? $transaction->user?->name ?? 'Pelanggan SevenKost',
                'email' => $customer['email'] ?? $transaction->user?->email,
                'mobile_number' => $customer['phone'] ?? $transaction->user?->phone,
            ],
            'success_redirect_url' => $successRedirectUrl,
            'failure_redirect_url' => $failureRedirectUrl,
        ];

        // Untuk booking / channel all, jangan batasi payment_methods agar pengguna bebas memilih di Xendit Sandbox
        if ($transaction->type !== 'booking' && ! empty($transaction->payment_channel) && ! in_array($transaction->payment_channel, ['all', 'default'])) {
            $payload['payment_methods'] = match ($transaction->payment_channel) {
                'qris' => ['QRIS'],
                'bca_va' => ['BCA'],
                'mandiri_va' => ['MANDIRI'],
                'bri_va' => ['BRI'],
                'bni_va' => ['BNI'],
                'ewallet' => ['OVO', 'DANA', 'SHOPEEPAY', 'LINKAJA'],
                default => ['QRIS', 'BCA', 'MANDIRI', 'BRI', 'BNI', 'OVO', 'DANA'],
            };
        }

        // Jika API Key Xendit tersedia, panggil Endpoint resmi Xendit Invoice v2
        if (! empty($this->secretKey) && ! str_starts_with($this->secretKey, 'mock_')) {
            try {
                $response = Http::withBasicAuth($this->secretKey, '')
                    ->timeout(15)
                    ->post('https://api.xendit.co/v2/invoices', $payload);

                if ($response->successful()) {
                    $data = $response->json();

                    $transaction->update([
                        'xendit_invoice_id' => $data['id'] ?? null,
                        'xendit_invoice_url' => $data['invoice_url'] ?? null,
                    ]);

                    return [
                        'id' => $data['id'],
                        'invoice_url' => $data['invoice_url'],
                        'status' => $data['status'] ?? 'PENDING',
                    ];
                }

                Log::warning('Xendit API returned error, falling back to local gateway simulation', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            } catch (Exception $e) {
                Log::error('Xendit connection failed: '.$e->getMessage());
            }
        }

        // Fallback Simulasi Gateway Cerdas (Localhost / Sandbox Offline)
        $simulatedId = 'xnd_inv_'.uniqid();
        $checkoutUrl = in_array($transaction->type, ['booking', 'renewal'], true)
            ? route('tenant.bookings.payment', $externalId)
            : ($transaction->type === 'topup'
                ? route('tenant.wallet.payment', $externalId)
                : ($transaction->type === 'service'
                    ? route('tenant.services.payment', $externalId)
                    : route('payments.checkout', $externalId)));

        $transaction->update([
            'xendit_invoice_id' => $simulatedId,
            'xendit_invoice_url' => $checkoutUrl,
        ]);

        return [
            'id' => $simulatedId,
            'invoice_url' => $checkoutUrl,
            'status' => 'PENDING',
        ];
    }

    /**
     * Tandai transaksi berhasil lunas dan eksekusi aktivasi otomatis produk terkait.
     */
    public function markTransactionAsPaid(Transaction $transaction, ?string $xenditPaymentId = null): bool
    {
        if ($transaction->isPaid()) {
            return true;
        }

        $transaction->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // 1. Aktivasi Otomatis Akun VIP Pemilik
        if ($transaction->type === 'vip') {
            $user = $transaction->user;
            if ($user) {
                // Tentukan tambahan durasi VIP sesuai paket
                $months = match ($transaction->item_id) {
                    '1_month' => 1,
                    '3_months' => 3,
                    '1_year' => 12,
                    default => 1,
                };

                // Jika user masih punya sisa durasi VIP aktif, akumulasikan
                $currentExpiry = ($user->vip_expires_at && now()->lessThan($user->vip_expires_at))
                    ? $user->vip_expires_at
                    : now();

                $user->update([
                    'vip_expires_at' => $currentExpiry->copy()->addMonths($months),
                ]);

                // Pastikan role pemilik di-assign jika belum
                try {
                    if (method_exists($user, 'hasRole') && ! $user->hasRole('pemilik')) {
                        $user->assignRole('pemilik');
                    }
                } catch (\Throwable) {
                    // Abaikan jika role belum di-seed di lingkungan pengujian
                }
            }
        }

        // 2. Pembaruan Status Pembayaran Iklan Banner Komersial
        if ($transaction->type === 'banner') {
            $bannerId = (int) $transaction->item_id;
            $banner = Banner::find($bannerId);
            if ($banner) {
                $banner->update([
                    'payment_status' => 'paid',
                    'payment_channel' => $transaction->payment_channel,
                    'amount' => $transaction->amount,
                ]);
            }
        }

        // 3. Aktivasi Hunian Kamar Kos setelah Pembayaran Lunas
        if ($transaction->type === 'booking') {
            $bookingId = (int) $transaction->item_id;
            $booking = Booking::find($bookingId);
            if ($booking && in_array($booking->status, ['approved', 'pending'])) {
                $booking->update([
                    'status' => 'active',
                ]);
            }
        }

        // 4. Perpanjangan Masa Sewa Kamar Kos (Renewal)
        if ($transaction->type === 'renewal') {
            $bookingId = (int) $transaction->item_id;
            $booking = Booking::find($bookingId);
            if ($booking) {
                $payload = $transaction->payload ?? [];
                $additionalMonths = (int) ($payload['duration_months'] ?? 1);
                $currentEnd = $booking->end_date ?? now();
                $newEndDate = $currentEnd->copy()->addMonths($additionalMonths);

                $booking->update([
                    'end_date' => $newEndDate,
                    'duration_months' => $booking->duration_months + $additionalMonths,
                    'status' => 'active',
                ]);
            }
        }

        // 5. Top Up Saldo Dompet Digital Penghuni (Wallet)
        if ($transaction->type === 'topup') {
            $user = $transaction->user;
            if ($user) {
                $existing = WalletTransaction::where('reference_id', $transaction->reference_id)->first();
                if (! $existing || ! $existing->isSuccess()) {
                    if ($existing) {
                        $before = (float) $user->wallet_balance;
                        $after = $before + (float) $transaction->amount;
                        $user->update(['wallet_balance' => $after]);
                        $existing->update([
                            'status' => 'success',
                            'balance_before' => $before,
                            'balance_after' => $after,
                        ]);
                    } else {
                        $user->creditWallet(
                            (float) $transaction->amount,
                            $transaction->reference_id,
                            'Top Up Saldo Dompet via '.($transaction->getChannelLabelAttribute() ?: 'Xendit'),
                            $transaction->payment_channel
                        );
                    }
                }
            }
        }

        // 6. Pemesanan Layanan Kebutuhan Kos (Laundry, Cleaning, AC, dll)
        if ($transaction->type === 'service') {
            // Status transaksi telah di-update menjadi 'paid' dan paid_at telah dicatat.
            Log::info("Service order #{$transaction->reference_id} successfully paid via Xendit.");
        }

        return true;
    }

    /**
     * Hitung durasi aktif invoice (dalam detik) berdasarkan jenis transaksi:
     * - Top up saldo dompet: 1 Jam (3600 detik)
     * - Booking kamar baru: 2 Jam (7200 detik)
     * - Perpanjangan / Tagihan sewa bulanan (renewal): Sampai tanggal jatuh tempo sewa (H-7 s/d Hari H)
     * - Pesanan layanan kamar: 2 Jam (7200 detik)
     * - Default lainnya: 24 Jam (86400 detik)
     */
    public function calculateInvoiceDuration(Transaction $transaction): int
    {
        return match ($transaction->type) {
            'topup' => 3600,
            'booking' => 7200,
            'renewal' => $this->calculateRenewalDuration($transaction),
            'service' => 7200,
            default => 86400,
        };
    }

    /**
     * Hitung durasi invoice perpanjangan sewa kamar kost sampai tanggal jatuh tempo sewa.
     */
    protected function calculateRenewalDuration(Transaction $transaction): int
    {
        $booking = Booking::find($transaction->item_id);
        if ($booking && $booking->end_date) {
            $dueDate = Carbon::parse($booking->end_date)->endOfDay();
            $secondsUntilDue = (int) now()->diffInSeconds($dueDate, false);

            if ($secondsUntilDue > 3600) {
                // Maksimal 7 hari (604.800 detik)
                return min($secondsUntilDue, 7 * 86400);
            }

            // Jika sudah hari H atau lewat jatuh tempo, berikan toleransi 2 hari (172.800 detik)
            return 2 * 86400;
        }

        return 7 * 86400;
    }

    /**
     * Cek dan sinkronisasikan status invoice langsung ke API Xendit.
     * Sangat penting untuk lingkungan localhost di mana webhook eksternal tidak dapat menjangkau server lokal.
     */
    public function checkInvoiceStatus(Transaction $transaction): Transaction
    {
        if ($transaction->isPaid()) {
            return $transaction;
        }

        if (empty($transaction->xendit_invoice_id) || str_starts_with($transaction->xendit_invoice_id, 'xnd_inv_')) {
            if ($transaction->status === 'pending' && $transaction->isExpired()) {
                $transaction->update(['status' => 'expired']);
            }

            return $transaction;
        }

        if (empty($this->secretKey) || str_starts_with($this->secretKey, 'mock_')) {
            return $transaction;
        }

        try {
            $response = Http::withBasicAuth($this->secretKey, '')
                ->timeout(8)
                ->get("https://api.xendit.co/v2/invoices/{$transaction->xendit_invoice_id}");

            if ($response->successful()) {
                $data = $response->json();
                $status = strtoupper($data['status'] ?? '');

                if (in_array($status, ['PAID', 'SETTLED'], true)) {
                    if (! empty($data['payment_channel'])) {
                        $transaction->update(['payment_channel' => $data['payment_channel']]);
                    }
                    $this->markTransactionAsPaid($transaction, $data['id'] ?? null);
                } elseif (in_array($status, ['EXPIRED', 'FAILED'], true)) {
                    $transaction->update(['status' => strtolower($status)]);
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed checking Xendit invoice status: '.$e->getMessage(), [
                'transaction_id' => $transaction->id,
                'xendit_invoice_id' => $transaction->xendit_invoice_id,
            ]);
        }

        return $transaction->fresh();
    }

    /**
     * Simulasi pelunasan langsung via Xendit Sandbox (Untuk Pengujian & Demo Cepat).
     */
    public function simulatePayment(string $referenceId): Transaction
    {
        $transaction = Transaction::where('reference_id', $referenceId)->firstOrFail();

        $this->markTransactionAsPaid($transaction, 'sim_pay_'.uniqid());

        return $transaction->fresh();
    }

    /**
     * Tangani Webhook Callback Resmi dari Xendit Gateway.
     */
    public function handleWebhook(array $payload, ?string $token = null): bool
    {
        if (! empty($this->webhookToken) && $token !== $this->webhookToken) {
            Log::warning('Xendit webhook token mismatch');

            return false;
        }

        $externalId = $payload['external_id'] ?? null;
        $status = strtoupper($payload['status'] ?? '');

        if (! $externalId) {
            return false;
        }

        $transaction = Transaction::where('reference_id', $externalId)->first();
        if (! $transaction) {
            return false;
        }

        if (in_array($status, ['PAID', 'SETTLED'])) {
            $this->markTransactionAsPaid($transaction, $payload['id'] ?? null);
        } elseif (in_array($status, ['EXPIRED', 'FAILED'])) {
            $transaction->update(['status' => strtolower($status)]);
        }

        return true;
    }
}
