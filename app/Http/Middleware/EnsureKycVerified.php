<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureKycVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return $next($request);
        }

        if ($user->hasAnyRole(['superadmin', 'admin'])) {
            return $next($request);
        }

        // Biarkan rute mitra diproses oleh otorisasi controller jika pengguna bukan mitra (akan menghasilkan 403 Forbidden)
        if ($request->routeIs('partner.*') && ! $user->hasRole('mitra') && ($user->role ?? null) !== 'mitra') {
            return $next($request);
        }

        $isMitra = $user->hasRole('mitra') || ($user->role ?? null) === 'mitra';

        if ($isMitra && ! $user->isKycVerified()) {
            if ($request->routeIs('kyc.*', 'logout')) {
                return $next($request);
            }

            return redirect()->route('kyc.index')->with('warning', 'Harap selesaikan verifikasi identitas resmi (KYC) Anda terlebih dahulu. Seluruh fitur operasional terkunci hingga verifikasi disetujui Admin.');
        }

        return $next($request);
    }
}
