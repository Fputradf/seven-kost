<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Paksa HTTPS saat diakses melalui tunnel ngrok agar asset & Vite tidak terblokir Mixed Content
        if (str_contains(request()->getHost(), 'ngrok') || request()->header('x-forwarded-proto') === 'https') {
            URL::forceScheme('https');
        }

        // Superadmin bypass: Berikan izin penuh untuk semua permission check
        Gate::before(function ($user, $ability) {
            return $user->hasRole('superadmin') ? true : null;
        });

        // Kustomisasi email reset password berbahasa Indonesia & ramah SMTP
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            $expireMinutes = config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

            return (new MailMessage)
                ->subject('Atur Ulang Kata Sandi Akun - SevenKost')
                ->greeting('Halo, '.($notifiable->name ?? 'Pengguna SevenKost').'!')
                ->line('Kami menerima permintaan pengaturan ulang kata sandi untuk akun SevenKost Anda.')
                ->action('Atur Ulang Kata Sandi', $resetUrl)
                ->line("Tautan reset kata sandi ini akan kedaluwarsa dalam waktu {$expireMinutes} menit.")
                ->line('Jika Anda tidak merasa melakukan permintaan ini, silakan abaikan email ini dan akun Anda tetap aman.')
                ->salutation("Salam hangat,\nTim SevenKost");
        });
    }
}
