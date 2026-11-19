<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'email',
    'phone',
    'avatar',
    'google_id',
    'password',
    'status',
    'vip_expires_at',
    'wallet_balance',
    'suspended_until',
    'moderation_reason',
    'moderated_at',
    'moderated_by',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
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
     * Iklan banner komersial yang dipesan oleh user.
     */
    public function banners(): HasMany
    {
        return $this->hasMany(Banner::class, 'user_id');
    }

    /**
     * Riwayat transaksi pembayaran via payment gateway.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    /**
     * Kos yang disimpan ke daftar favorit oleh user.
     */
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    /**
     * Koleksi kos favorit.
     */
    public function favoriteKosts(): BelongsToMany
    {
        return $this->belongsToMany(Kost::class, 'wishlists', 'user_id', 'kost_id')->withTimestamps();
    }

    /**
     * Laporan / keluhan yang diajukan oleh user.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'user_id');
    }

    /**
     * Riwayat transaksi dompet (wallet transactions).
     */
    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class, 'user_id');
    }

    /**
     * Cek apakah saldo dompet mencukupi nominal tertentu.
     */
    public function hasSufficientBalance(float $amount): bool
    {
        return (float) $this->wallet_balance >= $amount;
    }

    /**
     * Tambahkan saldo ke dompet pengguna dan catat transaksi.
     */
    public function creditWallet(float $amount, string $referenceId, string $description, ?string $paymentMethod = null, ?string $notes = null): WalletTransaction
    {
        $before = (float) $this->wallet_balance;
        $after = $before + $amount;

        $this->update(['wallet_balance' => $after]);

        return $this->walletTransactions()->create([
            'reference_id' => $referenceId,
            'type' => 'topup',
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => $after,
            'status' => 'success',
            'description' => $description,
            'payment_method' => $paymentMethod,
            'notes' => $notes,
        ]);
    }

    /**
     * Kurangi saldo dompet pengguna untuk pembayaran atau penarikan.
     */
    public function debitWallet(float $amount, string $referenceId, string $type, string $description, ?string $paymentMethod = null, ?string $bankName = null, ?string $accountNumber = null, ?string $accountHolder = null, string $status = 'success'): WalletTransaction
    {
        $before = (float) $this->wallet_balance;
        $after = max(0, $before - $amount);

        $this->update(['wallet_balance' => $after]);

        return $this->walletTransactions()->create([
            'reference_id' => $referenceId,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $before,
            'balance_after' => $after,
            'status' => $status,
            'description' => $description,
            'payment_method' => $paymentMethod,
            'bank_name' => $bankName,
            'account_number' => $accountNumber,
            'account_holder' => $accountHolder,
        ]);
    }

    /**
     * Cek apakah user memiliki status keanggotaan VIP aktif.
     */
    public function isVip(): bool
    {
        if ($this->hasAnyRole(['admin', 'superadmin'])) {
            return true;
        }

        return $this->vip_expires_at && now()->lessThan($this->vip_expires_at);
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
     * URL Avatar untuk Filament Panel.
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url;
    }

    /**
     * Accessor untuk URL Avatar.
     */
    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar) {
            if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
                return $this->avatar;
            }

            return Storage::disk('public')->url($this->avatar);
        }

        return null;
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
            'vip_expires_at' => 'datetime',
            'wallet_balance' => 'decimal:2',
            'suspended_until' => 'datetime',
            'moderated_at' => 'datetime',
        ];
    }
}
