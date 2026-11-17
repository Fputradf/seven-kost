<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\Transaction;
use App\Models\User;
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

        // Tentukan metode pembayaran yang diizinkan sesuai channel
        $paymentMethods = match ($transaction->payment_channel) {
            'qris' => ['QRIS'],
            'bca_va' => ['BCA'],
            'mandiri_va' => ['MANDIRI'],
            'bri_va' => ['BRI'],
            'bni_va' => ['BNI'],
            'ewallet' => ['OVO', 'DANA', 'SHOPEEPAY', 'LINKAJA'],
            default => ['QRIS', 'BCA', 'MANDIRI', 'BRI', 'BNI', 'OVO', 'DANA'],
        };

        // Jika API Key Xendit tersedia, panggil Endpoint resmi Xendit Invoice v2
        if (! empty($this->secretKey) && ! str_starts_with($this->secretKey, 'mock_')) {
            try {
                $response = Http::withBasicAuth($this->secretKey, '')
                    ->timeout(15)
                    ->post('https://api.xendit.co/v2/invoices', [
                        'external_id' => $externalId,
                        'amount' => $amount,
                        'description' => $description,
                        'payer_email' => $customer['email'] ?? $transaction->user?->email,
                        'customer' => [
                            'given_names' => $customer['name'] ?? $transaction->user?->name ?? 'Pelanggan SevenKost',
                            'email' => $customer['email'] ?? $transaction->user?->email,
                            'mobile_number' => $customer['phone'] ?? $transaction->user?->phone,
                        ],
                        'payment_methods' => $paymentMethods,
                        'success_redirect_url' => route('payments.success', $externalId),
                        'failure_redirect_url' => route('payments.checkout', $externalId),
                    ]);

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
        $checkoutUrl = route('payments.checkout', $externalId);

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

        return true;
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
