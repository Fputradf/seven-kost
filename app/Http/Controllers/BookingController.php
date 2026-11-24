<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kost;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use App\Services\XenditService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Tampilkan riwayat pengajuan sewa milik penghuni yang sedang login.
     */
    public function myBookings(XenditService $xenditService): View
    {
        $user = auth()->user();

        // Sinkronkan status transaksi pending yang memiliki invoice Xendit aktif
        $pendingTransactions = Transaction::where('user_id', $user->id)
            ->whereIn('type', ['booking', 'renewal'])
            ->where('status', 'pending')
            ->whereNotNull('xendit_invoice_id')
            ->get();

        foreach ($pendingTransactions as $pendingTx) {
            $xenditService->checkInvoiceStatus($pendingTx);
        }

        $bookings = $user->bookings()
            ->with(['kost.province', 'kost.regency', 'kost.owner', 'kost.primaryImage', 'kost.images', 'room'])
            ->latest()
            ->get();

        return view('tenant.bookings', compact('bookings'));
    }

    /**
     * Tampilkan detail sewa & kamar milik penghuni dalam tampilan mobile portal penghuni.
     */
    public function show(Booking $booking, XenditService $xenditService): View
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Jika booking masih pending / approved, cek apakah invoice Xendit sudah dibayar
        if (in_array($booking->status, ['approved', 'pending'], true)) {
            $pendingTx = Transaction::where('type', 'booking')
                ->where('item_id', (string) $booking->id)
                ->where('status', 'pending')
                ->whereNotNull('xendit_invoice_id')
                ->latest()
                ->first();

            if ($pendingTx) {
                $xenditService->checkInvoiceStatus($pendingTx);
                $booking->refresh();
            }
        }

        // Cek juga jika renewal sedang pending
        $pendingRenewal = Transaction::where('type', 'renewal')
            ->where('item_id', (string) $booking->id)
            ->where('status', 'pending')
            ->whereNotNull('xendit_invoice_id')
            ->latest()
            ->first();

        if ($pendingRenewal) {
            $xenditService->checkInvoiceStatus($pendingRenewal);
            $booking->refresh();
        }

        $booking->load([
            'kost.province',
            'kost.regency',
            'kost.owner',
            'kost.primaryImage',
            'kost.images',
            'kost.facilities',
            'room.facilities',
        ]);

        return view('tenant.bookings.show', compact('booking'));
    }

    /**
     * Simpan pengajuan sewa (booking) dari halaman detail kos.
     */
    public function store(Request $request, Kost $kost): RedirectResponse
    {
        $maxStartDate = now()->addDays(7)->toDateString();

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today|before_or_equal:'.$maxStartDate,
            'duration_months' => 'required|integer|min:1|max:24',
            'notes' => 'nullable|string|max:500',
            'is_for_other' => 'nullable|boolean',
            'tenant_name' => 'nullable|string|max:100',
            'tenant_phone' => 'nullable|string|min:9|max:16|regex:/^[0-9+]{9,16}$/',
            'tenant_nik' => 'nullable|string|size:16|regex:/^[0-9]{16}$/',
            'tenant_gender' => 'nullable|in:pria,wanita',
            'tenant_job' => 'nullable|string|max:100',
            'emergency_phone' => 'nullable|string|min:9|max:16|regex:/^[0-9+]{9,16}$/',
        ], [
            'start_date.before_or_equal' => 'Tanggal masuk kamar maksimal 7 hari ke depan dari hari ini.',
            'start_date.after_or_equal' => 'Tanggal masuk kamar minimal adalah hari ini.',
            'tenant_name.max' => 'Nama penghuni maksimal 100 karakter.',
            'tenant_phone.min' => 'Nomor WhatsApp penghuni minimal 9 karakter/digit.',
            'tenant_phone.max' => 'Nomor WhatsApp penghuni maksimal 16 karakter/digit.',
            'tenant_phone.regex' => 'Format nomor WhatsApp penghuni tidak valid (hanya angka dan tanda +).',
            'tenant_nik.size' => 'NIK KTP harus tepat 16 digit angka.',
            'tenant_nik.regex' => 'Format NIK KTP harus 16 digit angka.',
            'tenant_gender.in' => 'Pilihan jenis kelamin tidak valid (pria/wanita).',
            'emergency_phone.min' => 'Nomor kontak darurat minimal 9 karakter/digit.',
            'emergency_phone.max' => 'Nomor kontak darurat maksimal 16 karakter/digit.',
            'emergency_phone.regex' => 'Format nomor kontak darurat tidak valid (hanya angka dan tanda +).',
        ]);

        $room = Room::where('kost_id', $kost->id)->findOrFail($validated['room_id']);

        if ($room->available_rooms <= 0) {
            return back()->with('error', 'Maaf, kamar pada tipe ini sedang penuh.');
        }

        $user = auth()->user();
        $durationMonths = (int) $validated['duration_months'];
        $subtotal = $room->price * $durationMonths;
        $tenantFee = (float) setting('fee_booking_tenant', 0);
        $totalAmount = $subtotal + $tenantFee;
        $startDate = Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($durationMonths);

        $isForOther = $request->boolean('is_for_other');
        $tenantName = ($isForOther && ! empty($validated['tenant_name']))
            ? $validated['tenant_name']
            : (! empty($validated['tenant_name']) ? $validated['tenant_name'] : $user->name);
        $tenantPhone = ($isForOther && ! empty($validated['tenant_phone']))
            ? $validated['tenant_phone']
            : (! empty($validated['tenant_phone']) ? $validated['tenant_phone'] : ($user->phone ?? ''));

        if (empty($user->phone) && ! empty($tenantPhone) && ! $isForOther) {
            $user->update(['phone' => $tenantPhone]);
        }

        Booking::create([
            'user_id' => $user->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_months' => $durationMonths,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'is_for_other' => $isForOther,
            'tenant_name' => $tenantName,
            'tenant_phone' => $tenantPhone,
            'tenant_nik' => $validated['tenant_nik'] ?? null,
            'tenant_gender' => $validated['tenant_gender'] ?? null,
            'tenant_job' => $validated['tenant_job'] ?? null,
            'emergency_phone' => $validated['emergency_phone'] ?? null,
        ]);

        return redirect()->route('tenant.bookings.index')
            ->with('success', 'Pengajuan booking berhasil dikirim ke pemilik kos! Pantau statusnya di sini.');
    }

    /**
     * Bayar sewa kamar yang telah disetujui pemilik via Payment Gateway.
     */
    public function pay(Request $request, Booking $booking, XenditService $xenditService): RedirectResponse
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'active') {
            return redirect()->route('tenant.bookings.show', $booking)
                ->with('success', 'Sewa kamar ini sudah lunas dan aktif.');
        }

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Pembayaran hanya dapat dilakukan setelah pengajuan disetujui oleh pemilik kos.');
        }

        // Cari transaksi pending sebelumnya jika ada, atau buat baru
        $transaction = Transaction::where('type', 'booking')
            ->where('item_id', (string) $booking->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (! $transaction) {
            $referenceId = 'BK-'.$booking->id.'-'.strtoupper(Str::random(6));
            $roomName = $booking->room?->name ?? 'Kamar Standar';
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'reference_id' => $referenceId,
                'type' => 'booking',
                'item_id' => (string) $booking->id,
                'title' => 'Sewa '.$booking->kost->name.' ('.$roomName.' - '.$booking->duration_months.' Bulan)',
                'amount' => $booking->total_amount,
                'payment_channel' => 'all',
                'status' => 'pending',
            ]);

            $xenditService->createInvoice($transaction, [
                'name' => $booking->tenant_name ?: auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $booking->tenant_phone ?: auth()->user()->phone,
            ]);
            $transaction->refresh();
        } elseif ($transaction->payment_channel !== 'all' || empty($transaction->xendit_invoice_url)) {
            $transaction->update(['payment_channel' => 'all']);
            $xenditService->createInvoice($transaction, [
                'name' => $booking->tenant_name ?: auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $booking->tenant_phone ?: auth()->user()->phone,
            ]);
            $transaction->refresh();
        }

        // Arahkan langsung ke halaman pembayaran mobile penghuni (Xendit Sandbox tersemat di dalam aplikasi tanpa redirect keluar)
        return redirect()->route('tenant.bookings.payment', $transaction->reference_id);
    }

    /**
     * Bayar sewa kamar yang telah disetujui menggunakan Saldo Dompet Digital.
     */
    public function payWithWallet(Request $request, Booking $booking): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status === 'active') {
            return redirect()->route('tenant.bookings.show', $booking)
                ->with('success', 'Sewa kamar ini sudah lunas dan aktif.');
        }

        if ($booking->status !== 'approved') {
            return back()->with('error', 'Pembayaran hanya dapat dilakukan setelah pengajuan disetujui oleh pemilik kos.');
        }

        $totalAmount = (float) $booking->total_amount;
        if (! $user->hasSufficientBalance($totalAmount)) {
            $formattedNeed = 'Rp '.number_format($totalAmount, 0, ',', '.');
            $formattedHave = 'Rp '.number_format((float) $user->wallet_balance, 0, ',', '.');

            return back()->with('error', "Saldo dompet tidak mencukupi (Saldo Anda: {$formattedHave}, Dibutuhkan: {$formattedNeed}). Silakan Top Up terlebih dahulu atau bayar via Transfer/E-Wallet.");
        }

        $refId = 'WLPAY-'.$booking->id.'-'.strtoupper(Str::random(6));
        $roomName = $booking->room?->name ?? 'Kamar Standar';
        $title = 'Sewa '.$booking->kost->name.' ('.$roomName.' - '.$booking->duration_months.' Bulan)';

        // Debit saldo user
        $user->debitWallet(
            $totalAmount,
            $refId,
            'payment',
            'Pembayaran '.$title,
            'Saldo Dompet SevenKost'
        );

        // Catat transaksi gateway lunas
        Transaction::create([
            'user_id' => $user->id,
            'reference_id' => $refId,
            'type' => 'booking',
            'item_id' => (string) $booking->id,
            'title' => $title,
            'amount' => $totalAmount,
            'payment_channel' => 'wallet',
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $booking->update(['status' => 'active']);

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Pembayaran sewa menggunakan Saldo Dompet berhasil! Kamar Anda resmi aktif.');
    }

    /**
     * Bayar tagihan dan perpanjang masa sewa kamar kos via Payment Gateway.
     */
    public function renew(Request $request, Booking $booking, XenditService $xenditService): RedirectResponse
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'active') {
            return back()->with('error', 'Hanya kamar sewa aktif yang dapat diperpanjang.');
        }

        if (! $booking->isDueForRenewal()) {
            return back()->with('error', 'Tagihan perpanjangan sewa belum aktif untuk kamar ini.');
        }

        $validated = $request->validate([
            'duration_months' => 'required|integer|min:1|max:24',
        ]);

        $durationMonths = (int) $validated['duration_months'];
        $calc = $booking->calculateRenewalTotal($durationMonths);

        // Cari transaksi pending renewal yang belum kadaluarsa atau buat baru
        $transaction = Transaction::where('type', 'renewal')
            ->where('item_id', (string) $booking->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        $referenceId = 'RN-'.$booking->id.'-'.strtoupper(Str::random(6));
        $roomName = $booking->room?->name ?? 'Kamar Standar';
        $title = 'Perpanjangan Sewa '.$booking->kost->name.' ('.$roomName.' - '.$durationMonths.' Bulan)';

        $payload = [
            'booking_id' => $booking->id,
            'duration_months' => $durationMonths,
            'room_price' => $calc['room_price'],
            'subtotal' => $calc['subtotal'],
            'fee_booking_tenant' => $calc['fee_booking_tenant'],
            'late_fee' => $calc['late_fee'],
            'late_days' => $calc['late_fee_days'],
            'overdue_days' => $calc['overdue_days'],
        ];

        if (! $transaction || (float) $transaction->amount !== (float) $calc['grand_total'] || empty($transaction->xendit_invoice_url)) {
            if ($transaction) {
                $transaction->update(['status' => 'expired']);
            }

            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'reference_id' => $referenceId,
                'type' => 'renewal',
                'item_id' => (string) $booking->id,
                'title' => $title,
                'amount' => $calc['grand_total'],
                'payment_channel' => 'all',
                'status' => 'pending',
                'payload' => $payload,
            ]);

            $xenditService->createInvoice($transaction, [
                'name' => $booking->tenant_name ?: auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => $booking->tenant_phone ?: auth()->user()->phone,
            ]);
            $transaction->refresh();
        }

        return redirect()->route('tenant.bookings.payment', $transaction->reference_id);
    }

    /**
     * Bayar perpanjangan sewa menggunakan Saldo Dompet Digital.
     */
    public function renewWithWallet(Request $request, Booking $booking): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if ($booking->user_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'active') {
            return back()->with('error', 'Hanya kamar sewa aktif yang dapat diperpanjang.');
        }

        if (! $booking->isDueForRenewal()) {
            return back()->with('error', 'Tagihan perpanjangan sewa belum aktif untuk kamar ini.');
        }

        $validated = $request->validate([
            'duration_months' => 'required|integer|min:1|max:24',
        ]);

        $durationMonths = (int) $validated['duration_months'];
        $calc = $booking->calculateRenewalTotal($durationMonths);
        $grandTotal = (float) $calc['grand_total'];

        if (! $user->hasSufficientBalance($grandTotal)) {
            $formattedNeed = 'Rp '.number_format($grandTotal, 0, ',', '.');
            $formattedHave = 'Rp '.number_format((float) $user->wallet_balance, 0, ',', '.');

            return back()->with('error', "Saldo dompet tidak mencukupi (Saldo Anda: {$formattedHave}, Dibutuhkan: {$formattedNeed}). Silakan Top Up terlebih dahulu atau bayar via Transfer/E-Wallet.");
        }

        $refId = 'WLRN-'.$booking->id.'-'.strtoupper(Str::random(6));
        $roomName = $booking->room?->name ?? 'Kamar Standar';
        $title = 'Perpanjangan Sewa '.$booking->kost->name.' ('.$roomName.' - '.$durationMonths.' Bulan)';

        // Debit saldo user
        $user->debitWallet(
            $grandTotal,
            $refId,
            'payment',
            'Pembayaran '.$title,
            'Saldo Dompet SevenKost'
        );

        // Catat transaksi lunas
        Transaction::create([
            'user_id' => $user->id,
            'reference_id' => $refId,
            'type' => 'renewal',
            'item_id' => (string) $booking->id,
            'title' => $title,
            'amount' => $grandTotal,
            'payment_channel' => 'wallet',
            'status' => 'paid',
            'paid_at' => now(),
            'payload' => [
                'booking_id' => $booking->id,
                'duration_months' => $durationMonths,
                'room_price' => $calc['room_price'],
                'subtotal' => $calc['subtotal'],
                'fee_booking_tenant' => $calc['fee_booking_tenant'],
                'late_fee' => $calc['late_fee'],
                'late_days' => $calc['late_fee_days'],
                'overdue_days' => $calc['overdue_days'],
            ],
        ]);

        // Perpanjang sewa
        $currentEnd = $booking->end_date ?? now();
        $newEndDate = $currentEnd->copy()->addMonths($durationMonths);

        $booking->update([
            'end_date' => $newEndDate,
            'duration_months' => $booking->duration_months + $durationMonths,
            'status' => 'active',
        ]);

        return redirect()->route('tenant.bookings.show', $booking)
            ->with('success', 'Perpanjangan sewa via Saldo Dompet berhasil dilunasi! Masa sewa kamar Anda telah resmi diperpanjang.');
    }

    /**
     * Tampilan checkout pembayaran mobile khusus portal penghuni.
     */
    public function paymentCheckout(string $referenceId, XenditService $xenditService): View|RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        if ($transaction->isPaid()) {
            return redirect()->route('tenant.bookings.payment-success', $referenceId);
        }

        $booking = Booking::with(['kost', 'room'])->find($transaction->item_id);

        return view('tenant.payments.checkout', compact('transaction', 'booking'));
    }

    /**
     * Tampilan konfirmasi pembayaran sukses mobile khusus portal penghuni.
     */
    public function paymentSuccess(string $referenceId, XenditService $xenditService): View
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        $booking = Booking::with(['kost', 'room'])->find($transaction->item_id);

        return view('tenant.payments.success', compact('transaction', 'booking'));
    }

    /**
     * Simulasi pelunasan langsung untuk Sandbox & Pengujian Lokal di portal penghuni.
     */
    public function paymentSimulate(string $referenceId, XenditService $xenditService): RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->simulatePayment($referenceId);

        return redirect()->route('tenant.bookings.payment-success', $referenceId)
            ->with('success', 'Pembayaran via Xendit Sandbox berhasil diselesaikan! Kamar Anda telah resmi aktif.');
    }

    /**
     * Batalkan booking yang masih berstatus pending.
     */
    public function cancel(Booking $booking): RedirectResponse
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'pending') {
            $booking->update(['status' => 'cancelled']);

            return back()->with('success', 'Pengajuan sewa berhasil dibatalkan.');
        }

        return back()->with('error', 'Pengajuan sewa ini tidak dapat dibatalkan secara sepihak.');
    }

    /**
     * Akhiri masa sewa kamar kos / Check-Out (tidak memperpanjang sewa).
     */
    public function terminateLease(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status !== 'active') {
            return back()->with('error', 'Hanya hunian aktif yang dapat diakhiri masa sewanya.');
        }

        $reason = $request->input('reason', 'Penghuni memutuskan mengakhiri sewa / Check-Out.');

        $notes = $booking->notes
            ? ($booking->notes."\n[Check-Out: ".now()->format('d M Y H:i').'] '.$reason)
            : '[Check-Out: '.now()->format('d M Y H:i').'] '.$reason;

        $booking->update([
            'status' => 'completed',
            'notes' => $notes,
        ]);

        // Kembalikan kuota ketersediaan kamar
        if ($booking->room) {
            $booking->room->increment('available_rooms');
        }

        // Batalkan tagihan perpanjangan otomatis jika ada
        Transaction::where('type', 'renewal')
            ->where('item_id', (string) $booking->id)
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        return redirect()->route('tenant.dashboard')
            ->with('success', 'Masa sewa kamar telah resmi diakhiri (Check-Out). Tagihan perpanjangan otomatis telah dinonaktifkan.');
    }
}
