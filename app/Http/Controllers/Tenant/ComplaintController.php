<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ComplaintController extends Controller
{
    /**
     * Tampilkan riwayat keluhan & perbaikan fasilitas milik penghuni.
     */
    public function index(Request $request): View
    {
        $status = $request->query('status');
        $query = Report::where('user_id', auth()->id())
            ->with(['kost.province', 'kost.regency', 'kost.owner', 'room']);

        if ($status && in_array($status, ['pending', 'reviewed', 'resolved', 'rejected'])) {
            $query->where('status', $status);
        }

        $complaints = $query->latest()->paginate(10);

        $counts = [
            'all' => Report::where('user_id', auth()->id())->count(),
            'pending' => Report::where('user_id', auth()->id())->where('status', 'pending')->count(),
            'reviewed' => Report::where('user_id', auth()->id())->where('status', 'reviewed')->count(),
            'resolved' => Report::where('user_id', auth()->id())->where('status', 'resolved')->count(),
        ];

        return view('tenant.complaints.index', compact('complaints', 'counts', 'status'));
    }

    /**
     * Halaman form pengajuan keluhan fasilitas kos baru.
     */
    public function create(Request $request): View
    {
        $selectedKostId = $request->query('kost_id');

        // Ambil daftar kos yang pernah/sedang disewa penghuni
        $myBookings = auth()->user()->bookings()
            ->with(['kost', 'room'])
            ->latest()
            ->get();

        return view('tenant.complaints.create', compact('myBookings', 'selectedKostId'));
    }

    /**
     * Simpan keluhan fasilitas kamar ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'room_id' => 'nullable|exists:rooms,id',
            'category' => 'required|string|in:kamar,kamar_mandi,listrik_elektronik,kebersihan,keamanan,lainnya',
            'reason' => 'required|string|max:255',
            'details' => 'required|string|max:1500',
        ]);

        Report::create([
            'user_id' => auth()->id(),
            'kost_id' => $validated['kost_id'],
            'room_id' => $validated['room_id'] ?? null,
            'category' => $validated['category'],
            'reason' => $validated['reason'],
            'details' => $validated['details'],
            'status' => 'pending',
        ]);

        return redirect()->route('tenant.complaints.index')
            ->with('success', 'Keluhan fasilitas Anda berhasil dikirim ke pemilik kos. Pantau respon dan perkembangannya di sini.');
    }
}
