<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kost_id',
        'room_id',
        'start_date',
        'end_date',
        'duration_months',
        'total_amount',
        'status',
        'notes',
        'tenant_name',
        'tenant_phone',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'duration_months' => 'integer',
        'total_amount' => 'decimal:2',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
