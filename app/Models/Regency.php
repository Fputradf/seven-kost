<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Regency extends Model
{
    use HasFactory;

    /**
     * Primary key tidak auto-increment karena menggunakan kode resmi (char 4 digit).
     */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'province_id',
        'name',
        'type',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke Provinsi.
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }
}

