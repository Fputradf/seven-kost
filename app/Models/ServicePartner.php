<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class ServicePartner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'category',
        'province_id',
        'regency_id',
        'address',
        'phone',
        'latitude',
        'longitude',
        'service_radius_km',
        'is_open',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'verification_status',
        'operational_hours',
        'banner_image',
    ];

    /**
     * URL Banner / Foto Kartu Layanan usaha mitra.
     */
    public function getBannerUrlAttribute(): string
    {
        if ($this->banner_image) {
            if (str_starts_with($this->banner_image, 'http://') || str_starts_with($this->banner_image, 'https://')) {
                return $this->banner_image;
            }

            return Storage::disk('public')->url($this->banner_image);
        }

        return match ($this->category) {
            'cleaning' => asset('images/frontend/service-cleaning.jpg'),
            'catering' => asset('images/frontend/service-catering.jpg'),
            'maintenance' => asset('images/frontend/cta-kost-room.jpg'),
            default => asset('images/frontend/service-laundry.jpg'),
        };
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_open' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'service_radius_km' => 'float',
    ];

    /**
     * User pemilik akun mitra.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Provinsi domisili hub mitra.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Kabupaten/Kota domisili hub mitra.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * Transaksi layanan yang ditangani oleh mitra ini.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'partner_id');
    }

    /**
     * Banner iklan promosi komersial milik mitra ini.
     */
    public function banner(): HasOne
    {
        return $this->hasOne(Banner::class, 'user_id', 'user_id');
    }

    /**
     * Label kategori terformat bahasa Indonesia.
     */
    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'laundry' => 'Laundry Kiloan',
            'cleaning' => 'Kebersihan Kamar & Toilet',
            'catering' => 'Katering Harian',
            'maintenance' => 'Teknisi AC & Listrik',
            default => ucfirst($this->category),
        };
    }
}
