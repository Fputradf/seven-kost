<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserNotBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();

            if ($user->isBanned()) {
                $reason = $user->moderation_reason ?: 'Pelanggaran terhadap syarat dan ketentuan layanan Seven Kost.';
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->with('error', "Akun Anda telah diblokir permanen oleh Administrator. Alasan: {$reason}");
            }

            if ($user->status === 'suspended') {
                if ($user->suspended_until && now()->lessThan($user->suspended_until)) {
                    $until = $user->suspended_until->translatedFormat('d F Y H:i');
                    $reason = $user->moderation_reason ?: 'Peninjauan operasional atau pelanggaran aturan.';
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();

                    return redirect()->route('login')->with('error', "Akun Anda sedang ditangguhkan (suspend) hingga {$until}. Alasan: {$reason}");
                }

                // Jika durasi suspend sudah terlewati, aktifkan kembali akun secara otomatis
                $user->update([
                    'status' => 'active',
                    'suspended_until' => null,
                    'moderation_reason' => null,
                ]);
            }
        }

        return $next($request);
    }
}
