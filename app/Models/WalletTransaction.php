<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference_id',
        'type',
        'amount',
        'balance_before',
        'balance_after',
        'status',
        'description',
        'payment_method',
        'bank_name',
        'account_number',
        'account_holder',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isSuccess(): bool
    {
        return $this->status === 'success';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isTopup(): bool
    {
        return $this->type === 'topup';
    }

    public function isPayment(): bool
    {
        return $this->type === 'payment';
    }

    public function isWithdrawal(): bool
    {
        return $this->type === 'withdrawal';
    }

    public function getFormattedAmountAttribute(): string
    {
        $prefix = in_array($this->type, ['topup', 'refund'], true) ? '+' : '-';

        return $prefix.' Rp '.number_format($this->amount, 0, ',', '.');
    }
}
