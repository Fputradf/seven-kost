<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use App\Services\XenditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class WalletController extends Controller
{
    /**
     * Tampilkan halaman dompet digital, saldo aktif dan riwayat mutasi.
     */
    public function index(XenditService $xenditService): View
    {
        /** @var User $user */
        $user = auth()->user();

        // Sinkronkan status topup pending yang memiliki invoice Xendit
        $pendingTopups = Transaction::where('user_id', $user->id)
            ->where('type', 'topup')
            ->where('status', 'pending')
            ->whereNotNull('xendit_invoice_id')
            ->get();

        foreach ($pendingTopups as $pending) {
            $xenditService->checkInvoiceStatus($pending);
        }

        $user->refresh();

        $transactions = $user->walletTransactions()
            ->latest()
            ->paginate(15);

        return view('tenant.wallet.index', compact('user', 'transactions'));
    }

    /**
     * Ajukan permintaan Top Up saldo dompet via Xendit Gateway.
     */
    public function topup(Request $request, XenditService $xenditService): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000|max:50000000',
        ], [
            'amount.min' => 'Nominal top up minimal Rp 10.000.',
            'amount.max' => 'Nominal top up maksimal Rp 50.000.000.',
        ]);

        $amount = (float) $validated['amount'];
        $referenceId = 'TOPUP-'.strtoupper(Str::random(8));

        // Buat record transaksi gateway
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'reference_id' => $referenceId,
            'type' => 'topup',
            'item_id' => (string) $user->id,
            'title' => 'Top Up Saldo Dompet SevenKost - Rp '.number_format($amount, 0, ',', '.'),
            'amount' => $amount,
            'payment_channel' => 'all',
            'status' => 'pending',
            'payload' => [
                'user_id' => $user->id,
                'amount' => $amount,
            ],
        ]);

        // Buat invoice Xendit Sandbox
        $xenditService->createInvoice($transaction, [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '081234567890',
        ]);
        $transaction->refresh();

        return redirect()->route('tenant.wallet.payment', $transaction->reference_id);
    }

    /**
     * Tampilan checkout pembayaran Top Up (tersemat dalam aplikasi tanpa redirect keluar).
     */
    public function paymentCheckout(string $referenceId, XenditService $xenditService): View|RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        if ($transaction->isPaid()) {
            return redirect()->route('tenant.wallet.payment-success', $referenceId);
        }

        return view('tenant.wallet.checkout', compact('transaction'));
    }

    /**
     * Halaman konfirmasi sukses Top Up Saldo Dompet.
     */
    public function paymentSuccess(string $referenceId, XenditService $xenditService): View
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        return view('tenant.wallet.success', compact('transaction'));
    }

    /**
     * Simulasi pelunasan langsung untuk Sandbox & Pengujian Lokal.
     */
    public function paymentSimulate(string $referenceId, XenditService $xenditService): RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->simulatePayment($referenceId);

        return redirect()->route('tenant.wallet.payment-success', $referenceId)
            ->with('success', 'Top Up Saldo Dompet via Xendit Sandbox berhasil diselesaikan!');
    }

    /**
     * Tarik saldo (Withdrawal) ke rekening bank atau e-wallet pengguna.
     */
    public function withdraw(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $currentBalance = (float) $user->wallet_balance;

        $validated = $request->validate([
            'amount' => 'required|numeric|min:20000|max:'.$currentBalance,
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'account_holder' => 'required|string|max:150',
            'notes' => 'nullable|string|max:255',
        ], [
            'amount.min' => 'Nominal penarikan saldo minimal Rp 20.000.',
            'amount.max' => 'Nominal penarikan melebihi saldo dompet aktif Anda.',
            'bank_name.required' => 'Pilih bank atau e-wallet tujuan penarikan.',
            'account_number.required' => 'Nomor rekening atau nomor e-wallet wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $amount = (float) $validated['amount'];
        $refId = 'WD-'.strtoupper(Str::random(8));

        // Debit saldo user secara instan dan simpan riwayat mutasi
        $user->debitWallet(
            $amount,
            $refId,
            'withdrawal',
            'Penarikan Saldo ke '.$validated['bank_name'].' ('.$validated['account_number'].')',
            'Transfer Bank / E-Wallet',
            $validated['bank_name'],
            $validated['account_number'],
            $validated['account_holder'],
            'success'
        );

        return redirect()->route('tenant.wallet.index')
            ->with('success', 'Penarikan saldo sebesar Rp '.number_format($amount, 0, ',', '.').' ke '.$validated['bank_name'].' ('.$validated['account_number'].') an. '.$validated['account_holder'].' berhasil diproses!');
    }
}
