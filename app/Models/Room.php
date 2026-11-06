<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'name',
        'price',
        'total_rooms',
        'available_rooms',
        'room_size',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_rooms' => 'integer',
        'available_rooms' => 'integer',
    ];

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }

    public function bookings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Booking::class, 'room_id');
    }
}

