<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class FinanceController extends Controller
{
    /**
     * Tampilkan riwayat keuangan dan dompet pendapatan mitra (/mitra/keuangan).
     */
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->hasRole('mitra')) {
            abort(403, 'Akses terbatas untuk akun Mitra Layanan SevenKost.');
        }

        $partner = $user->servicePartner;
        if (! $partner) {
            return redirect()->route('partner.dashboard');
        }

        $walletBalance = (float) $user->wallet_balance;

        // Riwayat transaksi dompet (mutasi masuk & penarikan)
        $walletTransactions = $user->walletTransactions()->latest()->get();

        // Riwayat transaksi pesanan layanan yang ditangani
        $completedOrders = Transaction::query()
            ->where('type', 'service')
            ->where(function ($q) use ($partner) {
                $q->where('partner_id', $partner->id)
                    ->orWhere(function ($sub) use ($partner) {
                        $sub->whereNull('partner_id')
                            ->where('item_id', $partner->category);
                    });
            })
            ->where('payload->service_order_status', 'completed')
            ->latest('updated_at')
            ->get();

        $totalEarningsLifetime = $completedOrders->sum('amount');
        $totalWithdrawn = $walletTransactions->where('type', 'withdrawal')->where('status', 'success')->sum('amount');

        return view('partner.finances.index', compact(
            'partner',
            'walletBalance',
            'walletTransactions',
            'completedOrders',
            'totalEarningsLifetime',
            'totalWithdrawn'
        ));
    }

    /**
     * Proses pengajuan pencairan saldo pendapatan (withdrawal) ke rekening bank mitra.
     */
    public function withdraw(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        $request->validate([
            'amount' => ['required', 'numeric', 'min:20000'],
            'bank_name' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:150'],
        ], [
            'amount.required' => 'Nominal penarikan wajib diisi.',
            'amount.min' => 'Batas minimum pencairan saldo adalah Rp 20.000.',
            'bank_name.required' => 'Nama bank tujuan pencairan wajib diisi.',
            'account_number.required' => 'Nomor rekening bank tujuan wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $amount = (float) $request->input('amount');

        if (! $user->hasSufficientBalance($amount)) {
            $formattedHave = 'Rp '.number_format((float) $user->wallet_balance, 0, ',', '.');
            $formattedNeed = 'Rp '.number_format($amount, 0, ',', '.');

            return back()->with('error', "Saldo dompet tidak mencukupi untuk ditarik (Saldo Anda: {$formattedHave}, Diminta: {$formattedNeed}).");
        }

        $withdrawalRef = 'WD-MITRA-'.strtoupper(Str::random(10));

        $user->debitWallet(
            $amount,
            $withdrawalRef,
            'withdrawal',
            "Pencairan Saldo Dompet Mitra ke {$request->bank_name} ({$request->account_number} a.n {$request->account_holder})",
            'Bank Transfer',
            $request->bank_name,
            $request->account_number,
            $request->account_holder,
            'success'
        );

        // Update profil bank mitra jika belum tersimpan
        if (! $partner->bank_account_number) {
            $partner->update([
                'bank_name' => $request->bank_name,
                'bank_account_number' => $request->account_number,
                'bank_account_holder' => $request->account_holder,
            ]);
        }

        return back()->with('success', 'Permintaan pencairan dana sebesar Rp '.number_format($amount, 0, ',', '.')." berhasil diproses ke rekening {$request->bank_name} {$request->account_number} a.n {$request->account_holder}.");
    }
}
