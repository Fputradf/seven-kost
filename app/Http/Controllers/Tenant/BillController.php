<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BillController extends Controller
{
    /**
     * Tampilkan riwayat tagihan dan transaksi pembayaran penghuni.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        // Sinkronkan transaksi pending yang telah melewati batas waktu kadaluarsa
        Transaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->get()
            ->each(function ($bill) {
                if ($bill->isExpired()) {
                    $bill->update(['status' => 'expired']);
                }
            });

        // 1. Tagihan yang masih menunggu pembayaran (Pending)
        $pendingBills = Transaction::where('user_id', $user->id)
            ->where('status', 'pending')
            ->latest()
            ->get();

        // 2. Tagihan yang sudah lunas (Paid)
        $paidBills = Transaction::where('user_id', $user->id)
            ->where('status', 'paid')
            ->latest('paid_at')
            ->paginate(15, ['*'], 'paid_page');

        // 3. Tagihan dibatalkan / gagal
        $failedBills = Transaction::where('user_id', $user->id)
            ->whereIn('status', ['failed', 'expired', 'cancelled'])
            ->latest()
            ->take(10)
            ->get();

        return view('tenant.bills.index', compact('pendingBills', 'paidBills', 'failedBills'));
    }

    /**
     * Batalkan tagihan yang masih pending (misal user berubah pikiran).
     */
    public function cancel(string $referenceId): RedirectResponse
    {
        $user = auth()->user();

        $transaction = Transaction::where('user_id', $user->id)
            ->where('reference_id', $referenceId)
            ->where('status', 'pending')
            ->firstOrFail();

        $transaction->update([
            'status' => 'failed',
            'payload' => array_merge($transaction->payload ?? [], [
                'cancelled_by' => 'tenant',
                'cancelled_at' => now()->toIso8601String(),
            ]),
        ]);

        return back()->with('success', 'Tagihan #'.$transaction->reference_id.' berhasil dibatalkan.');
    }
}
