<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'category',
    ];

    /**
     * Relasi ke Kos yang memiliki fasilitas ini.
     */
    public function kosts(): BelongsToMany
    {
        return $this->belongsToMany(Kost::class, 'kost_facilities', 'facility_id', 'kost_id');
    }
}

