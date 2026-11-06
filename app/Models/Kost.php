<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Kost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'province_id',
        'regency_id',
        'name',
        'slug',
        'description',
        'type',
        'price_start',
        'rent_type',
        'address',
        'latitude',
        'longitude',
        'rules',
        'verification_status',
        'rejection_reason',
        'is_available',
        'is_featured',
    ];

    protected $casts = [
        'price_start' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Boot model untuk auto-generate slug unik jika belum ada.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($kost) {
            if (empty($kost->slug)) {
                $baseSlug = Str::slug($kost->name);
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $kost->slug = $slug;
            }
        });
    }

    /**
     * Pemilik kos (User).
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Provinsi kos.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    /**
     * Kabupaten / Kota kos.
     */
    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    /**
     * Fasilitas yang dimiliki kos.
     */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'kost_facilities', 'kost_id', 'facility_id');
    }

    /**
     * Tipe kamar yang tersedia.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class, 'kost_id');
    }

    /**
     * Galeri foto kos.
     */
    public function images(): HasMany
    {
        return $this->hasMany(KostImage::class, 'kost_id')->orderBy('sort_order');
    }

    /**
     * Foto utama / cover kos.
     */
    public function primaryImage()
    {
        return $this->hasOne(KostImage::class, 'kost_id')->where('is_primary', true);
    }
}

