<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dasbor utama portal pemilik kos (/owner/dashboard).
     */
    public function index(): View
    {
        $user = auth()->user();

        // Verifikasi hak akses peran pemilik / admin / superadmin
        if (! $user->hasAnyRole(['pemilik', 'admin', 'superadmin'])) {
            if ($user->kosts()->exists()) {
                $user->assignRole('pemilik');
            } else {
                abort(403, 'Akses terbatas untuk Mitra Pemilik Kos.');
            }
        }

        // Ambil semua properti kos milik pemilik beserta kamar dan relasi terkait
        $kosts = $user->kosts()
            ->with(['rooms', 'province', 'regency', 'primaryImage'])
            ->latest()
            ->get();

        $kostIds = $kosts->pluck('id');

        // 1. Metrik Okupansi Kamar
        $totalRooms = $kosts->flatMap->rooms->sum('total_rooms');
        $availableRooms = $kosts->flatMap->rooms->sum('available_rooms');
        $occupiedRooms = max(0, $totalRooms - $availableRooms);
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100, 1) : 0;

        // 2. Penyewa Aktif & Estimasi Pendapatan Sewa Bulanan
        $activeBookings = Booking::with(['kost', 'room', 'tenant'])
            ->whereIn('kost_id', $kostIds)
            ->whereIn('status', ['approved', 'active'])
            ->get();

        $activeTenantsCount = $activeBookings->count();
        $monthlyRevenue = $activeBookings->sum(function (Booking $booking) {
            return (float) ($booking->room?->price ?? 0);
        });

        // 3. Pengajuan Sewa Masuk (Menunggu Konfirmasi Segera)
        $pendingBookings = Booking::with(['kost', 'room', 'tenant'])
            ->whereIn('kost_id', $kostIds)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $pendingBookingsCount = $pendingBookings->count();

        // 4. Pengingat Jatuh Tempo Sewa (H-7)
        $expiringBookings = Booking::with(['kost', 'room', 'tenant'])
            ->whereIn('kost_id', $kostIds)
            ->whereIn('status', ['approved', 'active'])
            ->whereNotNull('end_date')
            ->where('end_date', '<=', now()->addDays(7))
            ->orderBy('end_date')
            ->get();

        return view('owner.dashboard', compact(
            'kosts',
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'occupancyRate',
            'activeTenantsCount',
            'monthlyRevenue',
            'pendingBookings',
            'pendingBookingsCount',
            'expiringBookings'
        ));
    }
}
