<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class MarketplaceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'price',
        'condition',
        'meetup_location',
        'city',
        'description',
        'is_featured',
        'status',
        'views_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function ($item) {
            if (empty($item->slug)) {
                $baseSlug = Str::slug($item->title) ?: 'barang-kos';
                $slug = $baseSlug;
                $count = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug.'-'.$count++;
                }
                $item->slug = $slug;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(MarketplaceItemImage::class)->orderBy('order');
    }

    public function primaryImage(): HasOne
    {
        return $this->hasOne(MarketplaceItemImage::class)->where('is_primary', true);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp '.number_format($this->price, 0, ',', '.');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'kasur' => 'Kasur',
            'kipas' => 'Kipas Angin',
            'rak' => 'Rak & Lemari',
            'perabot' => 'Perabot Kamar',
            'elektronik' => 'Elektronik Kecil',
            'buku' => 'Buku & Kuliah',
            default => 'Lainnya',
        };
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'baru' => 'Baru',
            'bekas_seperti_baru' => 'Bekas (Mulus)',
            'bekas_baik' => 'Bekas (Baik)',
            'bekas_cukup' => 'Bekas (Cukup)',
            default => 'Bekas Layak Pakai',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Menunggu Persetujuan',
            'active' => 'Aktif (Disetujui)',
            'rejected' => 'Ditolak',
            'sold' => 'Terjual',
            default => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'amber',
            'active' => 'emerald',
            'rejected' => 'rose',
            'sold' => 'slate',
            default => 'slate',
        };
    }

    public function getPrimaryImageUrlAttribute(): string
    {
        $primary = $this->images->firstWhere('is_primary', true) ?? $this->images->first();
        if ($primary && $primary->image_path) {
            if (str_starts_with($primary->image_path, 'http') || str_starts_with($primary->image_path, 'images/')) {
                return asset($primary->image_path);
            }

            return asset('storage/'.$primary->image_path);
        }

        return asset('images/frontend/marketplace-hd.jpg');
    }
}
