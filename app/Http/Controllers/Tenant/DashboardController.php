<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan beranda khusus penghuni / penyewa kos.
     */
    public function index(): View
    {
        $user = auth()->user();

        // 1. Kamar hunian aktif (jika sudah disetujui / aktif)
        $activeBooking = $user->bookings()
            ->whereIn('status', ['approved', 'active'])
            ->with(['kost.province', 'kost.regency', 'kost.owner', 'room'])
            ->latest()
            ->first();

        // 2. Daftar riwayat booking terbaru
        $recentBookings = $user->bookings()
            ->with(['kost.province', 'kost.regency', 'room'])
            ->latest()
            ->take(4)
            ->get();

        // 3. Kos favorit (wishlist)
        $favoriteKosts = $user->favoriteKosts()
            ->with(['province', 'regency', 'primaryImage', 'facilities'])
            ->latest('wishlists.created_at')
            ->take(3)
            ->get();

        // 4. Tiket keluhan fasilitas kamar terakhir
        $recentComplaints = Report::where('user_id', $user->id)
            ->with(['kost', 'room'])
            ->latest()
            ->take(4)
            ->get();

        // Ringkasan metrik
        $stats = [
            'total_bookings' => $user->bookings()->count(),
            'total_wishlists' => $user->wishlists()->count(),
            'total_complaints' => Report::where('user_id', $user->id)->count(),
            'has_active_stay' => $activeBooking !== null,
        ];

        return view('tenant.dashboard', compact(
            'user',
            'activeBooking',
            'recentBookings',
            'favoriteKosts',
            'recentComplaints',
            'stats'
        ));
    }
}
