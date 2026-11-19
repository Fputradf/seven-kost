<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kost_id',
        'room_id',
        'start_date',
        'end_date',
        'duration_months',
        'total_amount',
        'status',
        'notes',
        'is_for_other',
        'tenant_name',
        'tenant_phone',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration_months' => 'integer',
        'total_amount' => 'decimal:2',
        'is_for_other' => 'boolean',
    ];

    /**
     * Tentukan apakah pengajuan sewa ini diperuntukkan bagi orang lain.
     */
    public function getIsForOtherAttribute(): bool
    {
        // 1. Jika atribut is_for_other secara eksplisit diisi di database
        if (array_key_exists('is_for_other', $this->attributes) && ! is_null($this->attributes['is_for_other'])) {
            return (bool) $this->attributes['is_for_other'];
        }

        // 2. Jika nama penghuni kosong -> pasti diri sendiri
        if (empty($this->tenant_name)) {
            return false;
        }

        // 3. Bandingkan nama penghuni dengan nama akun pemesan
        $userName = $this->tenant?->name ?? (auth()->check() && auth()->id() === $this->user_id ? auth()->user()->name : null);
        if ($userName && strcasecmp(trim($this->tenant_name), trim($userName)) === 0) {
            return false;
        }

        // Jika ada perbedaan nama yang jelas antara pemesan dan penghuni
        return $userName !== null && strcasecmp(trim($this->tenant_name), trim($userName)) !== 0;
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    /**
     * Tentukan apakah sewa kamar ini telah memasuki periode penagihan perpanjangan.
     */
    public function isDueForRenewal(): bool
    {
        if ($this->status !== 'active' || ! $this->end_date) {
            return false;
        }

        $noticeDays = (int) setting('rent_bill_notice_days', 7);
        $billStartDate = $this->end_date->copy()->startOfDay()->subDays($noticeDays);

        return now()->startOfDay()->greaterThanOrEqualTo($billStartDate);
    }

    /**
     * Tentukan apakah masa sewa kamar telah melewati tanggal jatuh tempo (terlambat).
     */
    public function isOverdue(): bool
    {
        if ($this->status !== 'active' || ! $this->end_date) {
            return false;
        }

        return now()->startOfDay()->greaterThan($this->end_date->copy()->startOfDay());
    }

    /**
     * Hitung total hari keterlambatan sejak tanggal jatuh tempo berakhir.
     */
    public function getOverdueDays(): int
    {
        if (! $this->isOverdue()) {
            return 0;
        }

        return (int) $this->end_date->copy()->startOfDay()->diffInDays(now()->startOfDay());
    }

    /**
     * Hitung jumlah hari keterlambatan yang dikenakan denda setelah melewati masa tenggang.
     */
    public function getLateFeeDays(): int
    {
        if (! (bool) setting('late_fee_enabled', true)) {
            return 0;
        }

        $graceDays = (int) setting('late_fee_grace_days', 1);
        $overdueDays = $this->getOverdueDays();

        return max(0, $overdueDays - $graceDays);
    }

    /**
     * Hitung total denda keterlambatan berjalan.
     */
    public function getLateFee(): float
    {
        $chargeableDays = $this->getLateFeeDays();
        if ($chargeableDays <= 0) {
            return 0.0;
        }

        $dailyRate = (float) setting('late_fee_daily_amount', 20000);

        return (float) ($chargeableDays * $dailyRate);
    }

    /**
     * Hitung rincian kalkulasi total biaya untuk perpanjangan sewa kamar.
     *
     * @return array{room_price: float, duration_months: int, subtotal: float, fee_booking_tenant: float, overdue_days: int, late_fee_days: int, late_fee: float, grand_total: float}
     */
    public function calculateRenewalTotal(int $durationMonths = 1): array
    {
        $monthlyPrice = (float) ($this->room?->price ?? ($this->duration_months > 0 ? ($this->total_amount / $this->duration_months) : 0));
        $subtotal = $monthlyPrice * max(1, $durationMonths);
        $tenantFee = (float) setting('fee_booking_tenant', 5000);
        $lateFee = $this->getLateFee();
        $grandTotal = $subtotal + $tenantFee + $lateFee;

        return [
            'room_price' => $monthlyPrice,
            'duration_months' => max(1, $durationMonths),
            'subtotal' => $subtotal,
            'fee_booking_tenant' => $tenantFee,
            'overdue_days' => $this->getOverdueDays(),
            'late_fee_days' => $this->getLateFeeDays(),
            'late_fee' => $lateFee,
            'grand_total' => $grandTotal,
        ];
    }
}
