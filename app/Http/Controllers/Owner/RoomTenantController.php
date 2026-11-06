<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomTenantController extends Controller
{
    /**
     * Tampilkan data kamar dari kos tertentu.
     */
    public function rooms(Kost $kost): View
    {
        if ($kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $rooms = $kost->rooms()->latest()->get();

        return view('owner.rooms.index', compact('kost', 'rooms'));
    }

    /**
     * Tambah kamar baru ke kos.
     */
    public function storeRoom(Request $request, Kost $kost): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:100000',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'room_size' => 'nullable|string|max:50',
        ]);

        $kost->rooms()->create($validated);

        return back()->with('success', 'Tipe kamar baru berhasil ditambahkan.');
    }

    /**
     * Hapus kamar dari kos.
     */
    public function destroyRoom(Kost $kost, Room $room): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $room->delete();

        return back()->with('success', 'Tipe kamar berhasil dihapus.');
    }

    /**
     * Tampilkan daftar penyewa & pengajuan sewa di semua kos milik pemilik.
     */
    public function tenants(): View
    {
        $ownerKostIds = auth()->user()->kosts()->pluck('id');

        $bookings = Booking::with(['kost', 'room', 'tenant'])
            ->whereIn('kost_id', $ownerKostIds)
            ->latest()
            ->get();

        return view('owner.tenants.index', compact('bookings'));
    }

    /**
     * Ubah status pengajuan sewa (Terima / Tolak / Selesai).
     */
    public function updateBookingStatus(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:approved,rejected,active,completed,cancelled',
        ]);

        $newStatus = $request->input('status');
        $booking->update(['status' => $newStatus]);

        // Jika disetujui, kurangi 1 kamar yang tersedia
        if ($newStatus === 'approved' && $booking->room->available_rooms > 0) {
            $booking->room->decrement('available_rooms');
        } elseif (in_array($newStatus, ['cancelled', 'completed', 'rejected'])) {
            // Jika selesai atau batal, kembalikan ketersediaan kamar
            $booking->room->increment('available_rooms');
        }

        return back()->with('success', 'Status pengajuan sewa berhasil diperbarui menjadi ' . ucfirst($newStatus));
    }
}
