<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePartnerPackage extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'service_partner_id',
        'name',
        'price',
        'unit',
        'description',
        'is_active',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Mitra induk pemilik paket layanan ini.
     */
    public function servicePartner(): BelongsTo
    {
        return $this->belongsTo(ServicePartner::class, 'service_partner_id');
    }
}
