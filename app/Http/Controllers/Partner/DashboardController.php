<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\ServicePartner;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dasbor utama portal mitra layanan (/mitra/dashboard).
     */
    public function index(): View|RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->hasRole('mitra')) {
            abort(403, 'Akses terbatas untuk akun Mitra Layanan SevenKost.');
        }

        // Ambil atau inisialisasi profil usaha mitra
        $partner = $user->servicePartner;
        if (! $partner) {
            $partner = ServicePartner::create([
                'user_id' => $user->id,
                'business_name' => 'Mitra Layanan '.$user->name,
                'category' => 'laundry',
                'phone' => $user->phone,
                'is_open' => true,
                'verification_status' => 'verified',
            ]);
        }

        // Ambil transaksi pesanan layanan yang relevan
        $ordersQuery = Transaction::query()
            ->where('type', 'service')
            ->where(function ($query) use ($partner) {
                $query->where('partner_id', $partner->id)
                    ->orWhere(function ($q) use ($partner) {
                        $q->whereNull('partner_id')
                            ->where('item_id', $partner->category);
                    });
            });

        // 1. Metrik Operasional
        $allOrders = (clone $ordersQuery)->latest()->get();

        $pendingCount = $allOrders->filter(function ($trx) {
            $status = $trx->payload['service_order_status'] ?? 'pending';

            return in_array($status, ['pending', 'awaiting_confirmation']) && $trx->status !== 'failed';
        })->count();

        $processingCount = $allOrders->filter(function ($trx) {
            $status = $trx->payload['service_order_status'] ?? '';

            return in_array($status, ['confirmed', 'on_the_way', 'in_progress']);
        })->count();

        $completedTodayCount = $allOrders->filter(function ($trx) {
            $status = $trx->payload['service_order_status'] ?? '';
            $completedAt = $trx->payload['completed_at'] ?? null;

            return $status === 'completed' && $completedAt && str_starts_with($completedAt, now()->toDateString());
        })->count();

        $totalEarnings = $allOrders->filter(function ($trx) {
            return ($trx->payload['service_order_status'] ?? '') === 'completed';
        })->sum('amount');

        // 2. Pesanan Mendesak (Menunggu konfirmasi & sedang diproses)
        $urgentOrders = $allOrders->filter(function ($trx) {
            $status = $trx->payload['service_order_status'] ?? 'pending';

            return in_array($status, ['pending', 'awaiting_confirmation', 'confirmed', 'on_the_way']) && $trx->status !== 'failed';
        })->take(5);

        return view('partner.dashboard', compact(
            'partner',
            'pendingCount',
            'processingCount',
            'completedTodayCount',
            'totalEarnings',
            'urgentOrders'
        ));
    }
}
