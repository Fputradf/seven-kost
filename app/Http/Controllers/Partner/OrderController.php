<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Tampilkan daftar pesanan layanan untuk mitra (/mitra/pesanan).
     */
    public function index(Request $request): View
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

        $tab = $request->input('tab', 'all');
        $search = $request->input('q');

        $ordersQuery = Transaction::query()
            ->where('type', 'service')
            ->where(function ($query) use ($partner) {
                $query->where('partner_id', $partner->id)
                    ->orWhere(function ($q) use ($partner) {
                        $q->whereNull('partner_id')
                            ->where('item_id', $partner->category);
                    });
            });

        if ($search) {
            $ordersQuery->where(function ($q) use ($search) {
                $q->where('reference_id', 'LIKE', "%{$search}%")
                    ->orWhere('title', 'LIKE', "%{$search}%")
                    ->orWhere('payload->occupant_name', 'LIKE', "%{$search}%")
                    ->orWhere('payload->occupant_phone', 'LIKE', "%{$search}%")
                    ->orWhere('payload->kost_address', 'LIKE', "%{$search}%");
            });
        }

        $allOrders = $ordersQuery->latest()->get();

        // Filter berdasarkan tab
        $filteredOrders = $allOrders->filter(function ($trx) use ($tab) {
            $status = $trx->payload['service_order_status'] ?? ($trx->status === 'paid' ? 'confirmed' : 'pending');

            return match ($tab) {
                'pending' => in_array($status, ['pending', 'awaiting_confirmation']) && $trx->status !== 'failed',
                'processing' => in_array($status, ['confirmed', 'on_the_way', 'in_progress', 'ready_for_pickup']),
                'completed' => $status === 'completed',
                'cancelled' => in_array($status, ['cancelled', 'rejected']) || $trx->status === 'failed',
                default => true,
            };
        });

        $counts = [
            'all' => $allOrders->count(),
            'pending' => $allOrders->filter(fn ($t) => in_array($t->payload['service_order_status'] ?? ($t->status === 'paid' ? 'confirmed' : 'pending'), ['pending', 'awaiting_confirmation']) && $t->status !== 'failed')->count(),
            'processing' => $allOrders->filter(fn ($t) => in_array($t->payload['service_order_status'] ?? '', ['confirmed', 'on_the_way', 'in_progress', 'ready_for_pickup']))->count(),
            'completed' => $allOrders->filter(fn ($t) => ($t->payload['service_order_status'] ?? '') === 'completed')->count(),
            'cancelled' => $allOrders->filter(fn ($t) => in_array($t->payload['service_order_status'] ?? '', ['cancelled', 'rejected']) || $t->status === 'failed')->count(),
        ];

        return view('partner.orders.index', compact('partner', 'filteredOrders', 'tab', 'search', 'counts'));
    }

    /**
     * Tampilkan rincian pesanan layanan (/mitra/pesanan/{referenceId}).
     */
    public function show(string $referenceId): View
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->hasRole('mitra')) {
            abort(403, 'Akses terbatas untuk akun Mitra Layanan SevenKost.');
        }

        $partner = $user->servicePartner;
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];

        return view('partner.orders.show', compact('partner', 'transaction', 'payload'));
    }

    /**
     * Terima pesanan layanan oleh mitra.
     */
    public function accept(string $referenceId): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $payload['service_order_status'] = 'confirmed';
        $payload['confirmed_at'] = now()->toDateTimeString();
        $payload['partner_name'] = $partner->business_name;

        $transaction->update([
            'partner_id' => $partner->id,
            'payload' => $payload,
        ]);

        return redirect()->route('partner.orders.show', $referenceId)
            ->with('success', "Pesanan #{$referenceId} berhasil diterima! Silakan tugaskan kurir / petugas pelaksana di formulir bawah ini.");
    }

    /**
     * Tugaskan petugas / kurir untuk menuju ke lokasi kos penghuni.
     */
    public function assignCourier(Request $request, string $referenceId): RedirectResponse
    {
        $validated = $request->validate([
            'courier_name' => 'required|string|max:100',
            'courier_phone' => 'required|string|max:30',
            'vehicle_info' => 'nullable|string|max:100',
        ], [
            'courier_name.required' => 'Nama kurir / petugas pelaksana wajib diisi.',
            'courier_phone.required' => 'Nomor WhatsApp kurir / petugas wajib diisi.',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $payload['courier_name'] = $validated['courier_name'];
        $payload['courier_phone'] = $validated['courier_phone'];
        $payload['vehicle_info'] = $validated['vehicle_info'] ?? 'Kendaraan Operasional Mitra';
        $payload['service_order_status'] = 'on_the_way';
        $payload['dispatched_at'] = now()->toDateTimeString();
        $payload['partner_name'] = $partner->business_name;

        $transaction->update([
            'partner_id' => $partner->id,
            'payload' => $payload,
        ]);

        return back()->with('success', "Petugas {$validated['courier_name']} berhasil ditugaskan dan status pesanan diperbarui menjadi 'Mitra Menuju Lokasi Kos'.");
    }

    /**
     * Tandai pesanan mulai dikerjakan di lokasi kamar kos penghuni.
     */
    public function startWork(string $referenceId): RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $payload['service_order_status'] = 'in_progress';
        $payload['started_at'] = now()->toDateTimeString();

        $transaction->update(['payload' => $payload]);

        return back()->with('success', "Pesanan #{$referenceId} sekarang berstatus 'Sedang Dikerjakan'.");
    }

    /**
     * Tandai cucian / pesanan telah selesai dikerjakan dan siap diambil oleh penghuni di outlet toko mitra.
     */
    public function markReady(string $referenceId): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $payload['service_order_status'] = 'ready_for_pickup';
        $payload['ready_at'] = now()->toDateTimeString();

        $transaction->update([
            'partner_id' => $partner->id,
            'payload' => $payload,
        ]);

        return back()->with('success', "Pesanan #{$referenceId} berhasil ditandai 'Siap Diambil di Outlet'. Penghuni kos dapat langsung datang mengambil cucian/layanannya.");
    }

    /**
     * Tandai pengerjaan pesanan telah selesai dan unggah foto bukti pengerjaan.
     */
    public function complete(Request $request, string $referenceId): RedirectResponse
    {
        $request->validate([
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'completion_notes' => 'nullable|string|max:500',
        ]);

        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $isAlreadyCompleted = ($payload['service_order_status'] ?? '') === 'completed';

        if ($isAlreadyCompleted) {
            return back()->with('error', 'Pesanan ini sudah ditandai selesai sebelumnya.');
        }

        if ($request->hasFile('proof_image')) {
            $path = $request->file('proof_image')->store('proofs/services', 'public');
            $payload['proof_image'] = $path;
        }

        $payload['completion_notes'] = $request->input('completion_notes');
        $payload['service_order_status'] = 'completed';
        $payload['completed_at'] = now()->toDateTimeString();

        $transaction->update([
            'partner_id' => $partner->id,
            'payload' => $payload,
        ]);

        // Tambahkan dana pendapatan ke dompet mitra
        $amount = (float) $transaction->amount;
        if ($amount > 0) {
            $earningsRef = 'MITRA-PAY-'.strtoupper(Str::random(10));
            $user->creditWallet(
                $amount,
                $earningsRef,
                "Pendapatan penyelesaian pesanan {$transaction->title} (Ref: #{$referenceId})",
                'service_earnings',
                "Invoice: #{$referenceId}"
            );
        }

        return back()->with('success', "Selamat! Pesanan #{$referenceId} telah berhasil diselesaikan dan dana sebesar Rp ".number_format($amount, 0, ',', '.').' telah ditambahkan ke Dompet Mitra Anda.');
    }

    /**
     * Tolak pesanan layanan dan kembalikan dana ke penghuni jika telah dibayar.
     */
    public function reject(Request $request, string $referenceId): RedirectResponse
    {
        $request->validate([
            'reject_reason' => 'required|string|max:255',
        ], [
            'reject_reason.required' => 'Mohon sertakan alasan penolakan pesanan.',
        ]);

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('type', 'service')
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $amount = (float) $transaction->amount;

        $payload['service_order_status'] = 'rejected';
        $payload['rejected_at'] = now()->toDateTimeString();
        $payload['reject_reason'] = $request->input('reject_reason');

        $transaction->update([
            'status' => 'failed',
            'payload' => $payload,
        ]);

        // Refund 100% ke saldo dompet penghuni jika transaksi sebelumnya sudah berbayar/lunas
        $tenant = $transaction->user;
        if ($tenant && $amount > 0 && in_array($transaction->status, ['paid', 'failed'])) {
            $refundRef = 'REF-MITRA-'.strtoupper(Str::random(10));
            $tenant->creditWallet(
                $amount,
                $refundRef,
                "Pengembalian dana (Refund 100%) karena pesanan {$transaction->title} ditolak mitra: {$payload['reject_reason']}",
                'wallet_refund',
                "Invoice: #{$referenceId}"
            );
        }

        return back()->with('success', "Pesanan #{$referenceId} berhasil ditolak dan dana telah dikembalikan ke saldo dompet pemesan.");
    }
}
