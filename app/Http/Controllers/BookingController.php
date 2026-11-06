<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Kost;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Tampilkan riwayat pengajuan sewa milik penghuni yang sedang login.
     */
    public function myBookings(): View
    {
        $bookings = auth()->user()->bookings()
            ->with(['kost.province', 'kost.regency', 'kost.owner', 'room'])
            ->latest()
            ->get();

        return view('tenant.bookings', compact('bookings'));
    }

    /**
     * Simpan pengajuan sewa (booking) dari halaman detail kos.
     */
    public function store(Request $request, Kost $kost): RedirectResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:24',
            'notes' => 'nullable|string|max:500',
        ]);

        $room = Room::where('kost_id', $kost->id)->findOrFail($validated['room_id']);

        if ($room->available_rooms <= 0) {
            return back()->with('error', 'Maaf, kamar pada tipe ini sedang penuh.');
        }

        $user = auth()->user();
        $totalAmount = $room->price * $validated['duration_months'];
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        $endDate = $startDate->copy()->addMonths($validated['duration_months']);

        Booking::create([
            'user_id' => $user->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'duration_months' => $validated['duration_months'],
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? null,
            'tenant_name' => $user->name,
            'tenant_phone' => $user->phone ?? '',
        ]);

        return redirect()->route('tenant.bookings.index')
            ->with('success', 'Pengajuan booking berhasil dikirim ke pemilik kos! Pantau statusnya di sini.');
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
}
