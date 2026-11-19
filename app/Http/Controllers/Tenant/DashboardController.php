<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Transaction;
use App\Services\XenditService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan beranda khusus penghuni / penyewa kos.
     */
    public function index(XenditService $xenditService): View
    {
        $user = auth()->user();

        // 0. Sinkronisasi transaksi pending Xendit untuk user (booking, renewal, topup)
        $pendingTransactions = Transaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->whereNotNull('xendit_invoice_id')
            ->get();

        foreach ($pendingTransactions as $pendingTx) {
            $xenditService->checkInvoiceStatus($pendingTx);
        }

        $user->refresh();

        // 1. Kamar hunian aktif / disetujui
        $activeBooking = $user->bookings()
            ->whereIn('status', ['approved', 'active'])
            ->with(['kost.province', 'kost.regency', 'kost.owner', 'kost.primaryImage', 'kost.images', 'room'])
            ->latest()
            ->first();

        // 2. Tagihan perpanjangan otomatis jika hunian aktif memasuki periode H-7 / lewat jatuh tempo
        $renewalBill = null;
        if ($activeBooking && $activeBooking->status === 'active' && $activeBooking->isDueForRenewal()) {
            $renewalBill = $activeBooking->calculateRenewalTotal(1);
        }

        // 3. Tagihan sewa awal yang disetujui dan menunggu pembayaran
        $initialBill = null;
        if ($activeBooking && $activeBooking->status === 'approved') {
            $initialBill = [
                'total_amount' => (float) $activeBooking->total_amount,
                'duration_months' => $activeBooking->duration_months,
            ];
        }

        // 4. Daftar riwayat booking terbaru
        $recentBookings = $user->bookings()
            ->with(['kost.province', 'kost.regency', 'room'])
            ->latest()
            ->take(4)
            ->get();

        // 5. Kos favorit (wishlist)
        $favoriteKosts = $user->favoriteKosts()
            ->with(['province', 'regency', 'primaryImage', 'facilities'])
            ->latest('wishlists.created_at')
            ->take(3)
            ->get();

        // 6. Tiket keluhan fasilitas kamar terakhir
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
            'has_active_stay' => $activeBooking !== null && $activeBooking->status === 'active',
            'wallet_balance' => (float) ($user->wallet_balance ?? 0),
        ];

        return view('tenant.dashboard', compact(
            'user',
            'activeBooking',
            'renewalBill',
            'initialBill',
            'recentBookings',
            'favoriteKosts',
            'recentComplaints',
            'stats'
        ));
    }
}
