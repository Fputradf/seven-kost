<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdsPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'duration_days',
        'price',
        'description',
        'is_active',
    ];

    protected $casts = [
        'duration_days' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function promotions(): HasMany
    {
        return $this->hasMany(AdsPromotion::class, 'ads_package_id');
    }
}
