<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Province extends Model
{
    use HasFactory;

    /**
     * Primary key tidak auto-increment karena menggunakan kode resmi (char 2 digit).
     */
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'name',
        'latitude',
        'longitude',
    ];

    /**
     * Relasi ke Kabupaten / Kota.
     */
    public function regencies(): HasMany
    {
        return $this->hasMany(Regency::class, 'province_id', 'id');
    }
}

