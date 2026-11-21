<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\XenditService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Tampilkan halaman checkout invoice pembayaran Xendit.
     */
    public function checkout(string $referenceId, XenditService $xenditService): View|RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        if ($transaction->isPaid()) {
            return redirect()->route('payments.success', $referenceId);
        }

        // Tentukan data pembayaran simulasi / live sesuai saluran yang dipilih
        $paymentDetails = match ($transaction->payment_channel) {
            'qris' => [
                'type' => 'QRIS',
                'label' => 'QRIS Dinamis Otomatis',
                'qr_string' => '00020101021226670016ID.CO.XENDIT.WWW01189360099900000000000215'.$transaction->id.'5204581253033605802ID5910SEVEN KOST6007JAKARTA62070703A016304'.substr(md5($transaction->reference_id), 0, 4),
            ],
            'bca_va' => [
                'type' => 'VA',
                'bank' => 'BCA',
                'va_number' => '88019'.str_pad($transaction->id, 8, '0', STR_PAD_LEFT),
            ],
            'mandiri_va' => [
                'type' => 'VA',
                'bank' => 'Mandiri',
                'va_number' => '89022'.str_pad($transaction->id, 8, '0', STR_PAD_LEFT),
            ],
            'bri_va' => [
                'type' => 'VA',
                'bank' => 'BRI',
                'va_number' => '12888'.str_pad($transaction->id, 8, '0', STR_PAD_LEFT),
            ],
            'bni_va' => [
                'type' => 'VA',
                'bank' => 'BNI',
                'va_number' => '98888'.str_pad($transaction->id, 8, '0', STR_PAD_LEFT),
            ],
            'ewallet' => [
                'type' => 'EWALLET',
                'label' => 'GoPay, OVO, ShopeePay, DANA',
                'phone' => auth()->user()?->phone ?? '081234567890',
            ],
            default => [
                'type' => 'QRIS',
                'label' => 'QRIS Universal',
                'qr_string' => '00020101021226670016ID.CO.XENDIT.WWW01189360099900000000000215'.$transaction->id,
            ],
        };

        return view('payments.checkout', compact('transaction', 'paymentDetails'));
    }

    /**
     * Halaman konfirmasi pembayaran berhasil.
     */
    public function success(string $referenceId, XenditService $xenditService): View
    {
        $transaction = Transaction::where('reference_id', $referenceId)->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        return view('payments.success', compact('transaction'));
    }

    /**
     * Simulasi pelunasan langsung untuk Sandbox & Pengujian di Localhost.
     */
    public function simulate(string $referenceId, XenditService $xenditService): RedirectResponse
    {
        $xenditService->simulatePayment($referenceId);

        return redirect()->route('payments.success', $referenceId)
            ->with('success', 'Pembayaran via Xendit Payment Gateway berhasil diselesaikan! Layanan Anda telah aktif.');
    }

    /**
     * Endpoint API status transaksi untuk polling status di checkout.
     */
    public function status(string $referenceId, XenditService $xenditService): JsonResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)->first();

        if (! $transaction) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $xenditService->checkInvoiceStatus($transaction);

        return response()->json([
            'status' => $transaction->status,
            'is_paid' => $transaction->isPaid(),
            'paid_at' => $transaction->paid_at?->format('d M Y H:i:s'),
        ]);
    }

    /**
     * Endpoint Webhook Resmi Xendit Gateway.
     */
    public function webhook(Request $request, XenditService $xenditService): JsonResponse
    {
        $token = $request->header('x-callback-token');
        $success = $xenditService->handleWebhook($request->all(), $token);

        return response()->json([
            'status' => $success ? 'success' : 'ignored',
        ]);
    }

    /**
     * Unggah foto/gambar bukti transfer manual oleh pengguna.
     */
    public function uploadProof(Request $request, string $referenceId): RedirectResponse
    {
        $user = auth()->user();

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sender_name' => 'nullable|string|max:150',
            'sender_bank' => 'nullable|string|max:100',
        ], [
            'payment_proof.image' => 'Berkas harus berupa gambar (JPG, PNG, atau WEBP).',
            'payment_proof.max' => 'Ukuran berkas bukti transfer maksimal 5 MB.',
            'proof_image.image' => 'Berkas harus berupa gambar (JPG, PNG, atau WEBP).',
            'proof_image.max' => 'Ukuran berkas bukti transfer maksimal 5 MB.',
        ]);

        $file = $request->file('payment_proof') ?? $request->file('proof_image');
        if (! $file) {
            return back()->with('error', 'Silakan pilih berkas foto bukti transfer Anda.');
        }

        $path = $file->store('proofs/payments', 'public');

        $payload = $transaction->payload ?? [];
        $payload['payment_proof_image'] = $path;
        $payload['payment_proof_uploaded_at'] = now()->toDateTimeString();
        $payload['payment_sender_name'] = $request->input('sender_name');
        $payload['payment_sender_bank'] = $request->input('sender_bank');
        $payload['payment_confirmation_status'] = 'awaiting_confirmation';

        $transaction->update([
            'payload' => $payload,
        ]);

        return back()->with('success', 'Foto bukti pembayaran berhasil diunggah! Mohon tunggu konfirmasi verifikasi dari tim admin SevenKost.');
    }
}
