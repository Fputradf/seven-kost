<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google and log them in.
     */
    public function callback(Request $request): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            if (!$googleUser || !$googleUser->getEmail()) {
                return redirect()->route('login')->with('error', 'Gagal memverifikasi akun Google Anda.');
            }

            $isNewUser = false;

            // Cari user berdasarkan google_id terlebih dahulu
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Jika belum ada google_id, cari berdasarkan email
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Hubungkan google_id ke akun yang sudah terdaftar
                    $user->google_id = $googleUser->getId();
                    if (!$user->avatar && $googleUser->getAvatar()) {
                        $user->avatar = $googleUser->getAvatar();
                    }
                    if (!$user->email_verified_at) {
                        $user->email_verified_at = now();
                    }
                    $user->save();
                } else {
                    // Daftarkan akun baru via Google tanpa role awal
                    $user = User::create([
                        'name' => $googleUser->getName() ?: ($googleUser->getNickname() ?: 'Pengguna Google'),
                        'email' => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                        'email_verified_at' => now(),
                        'password' => null,
                    ]);

                    $isNewUser = true;
                }
            } else {
                // Perbarui avatar jika ada perubahan
                if ($googleUser->getAvatar() && $user->avatar !== $googleUser->getAvatar()) {
                    $user->avatar = $googleUser->getAvatar();
                    $user->save();
                }
            }

            Auth::login($user, remember: true);

            $request->session()->regenerate();

            // Jika user baru atau belum memiliki peran, arahkan ke halaman Onboarding Role
            if ($isNewUser || (method_exists($user, 'roles') && $user->roles()->count() === 0)) {
                return redirect()->route('onboarding.role');
            }

            return redirect()->intended(route('dashboard', absolute: false));
        } catch (\Throwable $e) {
            Log::error('Google OAuth Error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return redirect()->route('login')->with('error', 'Terjadi kesalahan saat masuk dengan Google: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman pemilihan peran (Onboarding) untuk pengguna Google.
     */
    public function showRoleSelection(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Jika user sudah memiliki peran aktif, langsung arahkan ke dashboard
        if ($user && method_exists($user, 'roles') && $user->roles()->count() > 0) {
            return redirect()->route('dashboard');
        }

        return view('auth.onboarding-role', [
            'user' => $user,
        ]);
    }

    /**
     * Simpan peran yang dipilih pengguna dan arahkan ke dashboard.
     */
    public function storeRoleSelection(Request $request): RedirectResponse
    {
        $request->validate([
            'role' => ['required', 'string', 'in:penghuni,pemilik,mitra'],
        ]);

        $user = $request->user();

        if (method_exists($user, 'syncRoles')) {
            $user->syncRoles([$request->role]);
        }

        return redirect()->route('dashboard')->with('status', 'Peran akun berhasil dipilih! Selamat datang di SevenKost.');
    }
}
