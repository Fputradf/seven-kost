<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class KycVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'role',
        'nik',
        'full_name',
        'ktp_path',
        'selfie_path',
        'business_document_path',
        'business_document_name',
        'status',
        'rejection_reason',
        'submitted_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function getKtpUrlAttribute(): ?string
    {
        if (! $this->ktp_path) {
            return null;
        }

        if (str_starts_with($this->ktp_path, 'http://') || str_starts_with($this->ktp_path, 'https://')) {
            return $this->ktp_path;
        }

        return Storage::disk('public')->url($this->ktp_path);
    }

    public function getSelfieUrlAttribute(): ?string
    {
        if (! $this->selfie_path) {
            return null;
        }

        if (str_starts_with($this->selfie_path, 'http://') || str_starts_with($this->selfie_path, 'https://')) {
            return $this->selfie_path;
        }

        return Storage::disk('public')->url($this->selfie_path);
    }

    public function getBusinessDocumentUrlAttribute(): ?string
    {
        if (! $this->business_document_path) {
            return null;
        }

        if (str_starts_with($this->business_document_path, 'http://') || str_starts_with($this->business_document_path, 'https://')) {
            return $this->business_document_path;
        }

        return Storage::disk('public')->url($this->business_document_path);
    }
}
