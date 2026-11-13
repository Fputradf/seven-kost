<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KostImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'file_path',
        'is_primary',
        'sort_order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'kost_id');
    }
}
