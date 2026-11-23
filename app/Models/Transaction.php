<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'reference_id',
        'type',
        'item_id',
        'title',
        'amount',
        'payment_channel',
        'status',
        'xendit_invoice_id',
        'xendit_invoice_url',
        'paid_at',
        'payload',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(ServicePartner::class, 'partner_id');
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    /**
     * Hitung tanggal & waktu batas akhir pembayaran (kadaluarsa).
     */
    public function getExpiresAtAttribute(): ?Carbon
    {
        if (! empty($this->payload['expires_at'])) {
            return Carbon::parse($this->payload['expires_at']);
        }

        $createdAt = $this->created_at ? $this->created_at->copy() : now();

        return match ($this->type) {
            // 1. Top Up Saldo Dompet: 1 Jam (60 Menit)
            'topup' => $createdAt->addHour(),

            // 2. Booking Kamar Baru: 2 Jam
            'booking' => $createdAt->addHours(2),

            // 3. Tagihan Sewa Rutin (Renewal): Sampai tanggal jatuh tempo sewa (end_date)
            'renewal' => $this->resolveRenewalExpiresAt($createdAt),

            // Layanan kamar kost: 2 Jam
            'service' => $createdAt->addHours(2),

            // Default invoice umum: 24 Jam
            default => $createdAt->addDay(),
        };
    }

    /**
     * Selesaikan batas waktu untuk perpanjangan sewa (renewal) berbasis jatuh tempo booking.
     */
    protected function resolveRenewalExpiresAt(Carbon $fallbackCreatedAt): Carbon
    {
        $booking = Booking::find($this->item_id);
        if ($booking && $booking->end_date) {
            $dueDate = Carbon::parse($booking->end_date)->endOfDay();
            if (now()->lessThanOrEqualTo($dueDate)) {
                return $dueDate;
            }

            // Jika sudah lewat jatuh tempo, beri grace period toleransi 2 hari
            return $dueDate->copy()->addDays(2);
        }

        // Default jika data booking belum terlacak: 7 hari
        return $fallbackCreatedAt->addDays(7);
    }

    /**
     * Hitung sisa detik aktif pembayaran transaksi saat ini (minimal 0).
     */
    public function getRemainingSecondsAttribute(): int
    {
        if ($this->isPaid() || in_array($this->status, ['expired', 'failed', 'cancelled'], true)) {
            return 0;
        }

        $expiresAt = $this->expires_at;
        if (! $expiresAt) {
            return 0;
        }

        return max(0, (int) now()->diffInSeconds($expiresAt, false));
    }

    /**
     * Periksa apakah batas waktu pembayaran transaksi telah kedaluwarsa.
     */
    public function isExpired(): bool
    {
        if (in_array($this->status, ['expired', 'failed', 'cancelled'], true)) {
            return true;
        }

        if ($this->status === 'pending') {
            $expiresAt = $this->expires_at;

            return $expiresAt ? now()->greaterThan($expiresAt) : false;
        }

        return false;
    }

    /**
     * Format tanggal & jam kedaluwarsa untuk tampilan UI.
     */
    public function getFormattedExpiresAtAttribute(): string
    {
        $expiresAt = $this->expires_at;

        return $expiresAt ? $expiresAt->translatedFormat('d M Y, H:i') : '-';
    }

    public function getFormattedAmountAttribute(): string
    {
        return 'Rp '.number_format($this->amount, 0, ',', '.');
    }

    public function getChannelLabelAttribute(): string
    {
        return match ($this->payment_channel) {
            'qris' => 'QRIS (Semua Bank & E-Wallet)',
            'bca_va' => 'BCA Virtual Account',
            'mandiri_va' => 'Mandiri Virtual Account',
            'bri_va' => 'BRI Virtual Account',
            'bni_va' => 'BNI Virtual Account',
            'ewallet' => 'E-Wallet (GoPay, OVO, ShopeePay)',
            default => strtoupper($this->payment_channel),
        };
    }

    public function getPaymentUrl(): string
    {
        return match ($this->type) {
            'service' => route('tenant.services.payment', $this->reference_id),
            'topup' => route('tenant.wallet.payment', $this->reference_id),
            'booking', 'renewal' => route('tenant.bookings.payment', $this->reference_id),
            default => route('payments.checkout', $this->reference_id),
        };
    }
}
