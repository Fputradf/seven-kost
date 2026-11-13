<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdsPromotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'ads_package_id',
        'starts_at',
        'ends_at',
        'status',
        'click_count',
        'cpc_spent',
        'proof_of_payment',
        'admin_notes',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'click_count' => 'integer',
        'cpc_spent' => 'decimal:2',
    ];

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(AdsPackage::class, 'ads_package_id');
    }
}
