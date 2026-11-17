<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
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

    public function isPaid(): bool
    {
        return $this->status === 'paid';
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
}
