<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'target_url',
        'user_id',
        'advertiser_name',
        'regency_id',
        'starts_at',
        'ends_at',
        'click_count',
        'is_active',
        'payment_status',
        'payment_channel',
        'amount',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'click_count' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function regency(): BelongsTo
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function getAdvertiserAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        return $this->advertiser_name ?: 'Admin Seven Kost';
    }

    public function getImageUrlAttribute(): string
    {
        if (str_starts_with($this->image_path, 'http')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'images/')) {
            return asset($this->image_path);
        }

        if (file_exists(public_path($this->image_path))) {
            return asset($this->image_path);
        }

        return asset('storage/'.$this->image_path);
    }
}
