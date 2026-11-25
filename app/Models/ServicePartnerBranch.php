<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServicePartnerBranch extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'service_partner_id',
        'branch_name',
        'phone',
        'province_id',
        'regency_id',
        'address',
        'latitude',
        'longitude',
        'service_radius_km',
        'operational_hours',
        'is_open',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'is_open' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'service_radius_km' => 'float',
    ];

    /**
     * Mitra induk pemilik cabang ini.
     */
    public function servicePartner(): BelongsTo
    {
        return $this->belongsTo(ServicePartner::class, 'service_partner_id');
    }

    /**
     * Provinsi lokasi cabang.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Kabupaten/Kota lokasi cabang.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }
}
