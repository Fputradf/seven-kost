<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'email',
    'phone',
    'avatar',
    'google_id',
    'password',
    'status',
    'suspended_until',
    'moderation_reason',
    'moderated_at',
    'moderated_by',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    /**
     * Kos yang dimiliki oleh user (pemilik).
     */
    public function kosts(): HasMany
    {
        return $this->hasMany(Kost::class, 'user_id');
    }

    /**
     * Booking yang diajukan oleh user (penghuni).
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Admin/Superadmin yang melakukan tindakan moderasi pada user ini.
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(self::class, 'moderated_by');
    }

    /**
     * Cek apakah status user aktif normal.
     */
    public function isActive(): bool
    {
        if ($this->status === 'banned') {
            return false;
        }

        if ($this->status === 'suspended') {
            if ($this->suspended_until && now()->lessThan($this->suspended_until)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Cek apakah user sedang disuspend.
     */
    public function isSuspended(): bool
    {
        if ($this->status !== 'suspended') {
            return false;
        }

        if ($this->suspended_until && now()->greaterThanOrEqualTo($this->suspended_until)) {
            return false;
        }

        return true;
    }

    /**
     * Cek apakah user diblokir permanen.
     */
    public function isBanned(): bool
    {
        return $this->status === 'banned';
    }

    /**
     * Tentukan akses ke Filament Admin Panel.
     * Hanya pengguna dengan peran 'superadmin' atau 'admin' yang aktif yang dapat mengakses panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        if (! $this->isActive()) {
            return false;
        }

        if ($panel->getId() === 'superadmin') {
            return $this->hasRole('superadmin');
        }

        if ($panel->getId() === 'admin') {
            return $this->hasRole('admin');
        }

        return false;
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'suspended_until' => 'datetime',
            'moderated_at' => 'datetime',
        ];
    }
}
