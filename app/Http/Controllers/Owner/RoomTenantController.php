<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Facility;
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
        if ($kost->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $rooms = $kost->rooms()->with('facilities')->latest()->get();
        $roomFacilities = Facility::where('category', 'kamar')->get();

        return view('owner.rooms.index', compact('kost', 'rooms', 'roomFacilities'));
    }

    /**
     * Tambah kamar baru ke kos.
     */
    public function storeRoom(Request $request, Kost $kost): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:100000',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'room_size' => 'nullable|string|max:50',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        if ($validated['available_rooms'] > $validated['total_rooms']) {
            return back()->withErrors(['available_rooms' => 'Jumlah kamar tersedia tidak boleh melebihi total unit kamar.'])->withInput();
        }

        $room = $kost->rooms()->create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'total_rooms' => $validated['total_rooms'],
            'available_rooms' => $validated['available_rooms'],
            'room_size' => $validated['room_size'] ?? null,
        ]);

        if (! empty($validated['facilities'])) {
            $room->facilities()->sync($validated['facilities']);
        }

        return back()->with('success', 'Tipe kamar baru beserta fasilitas berhasil ditambahkan.');
    }

    /**
     * Perbarui data kamar kos.
     */
    public function updateRoom(Request $request, Kost $kost, Room $room): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        if ($room->kost_id !== $kost->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:100000',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'room_size' => 'nullable|string|max:50',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        if ($validated['available_rooms'] > $validated['total_rooms']) {
            return back()->withErrors(['available_rooms' => 'Jumlah kamar tersedia tidak boleh melebihi total unit kamar.'])->withInput();
        }

        $room->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'total_rooms' => $validated['total_rooms'],
            'available_rooms' => $validated['available_rooms'],
            'room_size' => $validated['room_size'] ?? null,
        ]);

        $room->facilities()->sync($validated['facilities'] ?? []);

        return back()->with('success', 'Data tipe kamar dan fasilitas berhasil diperbarui.');
    }

    /**
     * Hapus kamar dari kos.
     */
    public function destroyRoom(Kost $kost, Room $room): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        if ($room->kost_id !== $kost->id) {
            abort(404);
        }

        $room->delete();

        return back()->with('success', 'Tipe kamar berhasil dihapus.');
    }

    /**
     * Tampilkan daftar penyewa & pengajuan sewa di semua kos milik pemilik.
     */
    public function tenants(Request $request): View
    {
        $ownerKostIds = auth()->user()->kosts()->pluck('id');
        $tab = $request->query('tab', 'all');

        $query = Booking::with(['kost', 'room', 'tenant'])
            ->whereIn('kost_id', $ownerKostIds);

        if ($tab === 'pending') {
            $query->where('status', 'pending');
        } elseif ($tab === 'approved') {
            $query->where('status', 'approved');
        } elseif ($tab === 'active') {
            $query->where('status', 'active');
        } elseif ($tab === 'history') {
            $query->whereIn('status', ['completed', 'cancelled', 'rejected']);
        }

        $bookings = $query->latest()->get();

        // Penghitung tab
        $counts = [
            'all' => Booking::whereIn('kost_id', $ownerKostIds)->count(),
            'pending' => Booking::whereIn('kost_id', $ownerKostIds)->where('status', 'pending')->count(),
            'approved' => Booking::whereIn('kost_id', $ownerKostIds)->where('status', 'approved')->count(),
            'active' => Booking::whereIn('kost_id', $ownerKostIds)->where('status', 'active')->count(),
            'history' => Booking::whereIn('kost_id', $ownerKostIds)->whereIn('status', ['completed', 'cancelled', 'rejected'])->count(),
        ];

        return view('owner.tenants.index', compact('bookings', 'tab', 'counts'));
    }

    /**
     * Ubah status pengajuan sewa (Terima / Tolak / Selesai).
     */
    public function updateBookingStatus(Request $request, Booking $booking): RedirectResponse
    {
        if ($booking->kost->user_id !== auth()->id() && ! auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'status' => 'required|in:approved,rejected,active,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStatus = $booking->status;
        $newStatus = $validated['status'];

        $updateData = ['status' => $newStatus];
        if (isset($validated['notes'])) {
            $updateData['notes'] = $validated['notes'];
        }

        $booking->update($updateData);

        // Sinkronisasi ketersediaan kamar yang akurat:
        // 1. Jika disetujui (dan sebelumnya belum disetujui/aktif), kurangi 1 unit kamar siap huni
        if ($newStatus === 'approved' && ! in_array($oldStatus, ['approved', 'active'])) {
            if ($booking->room && $booking->room->available_rooms > 0) {
                $booking->room->decrement('available_rooms');
            }
        }
        // 2. Jika sebelumnya disetujui/aktif lalu dibatalkan, diselesaikan, atau ditolak, kembalikan ketersediaan kamar
        elseif (in_array($newStatus, ['cancelled', 'completed', 'rejected']) && in_array($oldStatus, ['approved', 'active'])) {
            if ($booking->room && $booking->room->available_rooms < $booking->room->total_rooms) {
                $booking->room->increment('available_rooms');
            }
        }

        return back()->with('success', 'Status pengajuan sewa berhasil diperbarui menjadi '.ucfirst($newStatus));
    }
}
