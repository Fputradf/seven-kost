<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Kost;
use App\Models\KostImage;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class KostController extends Controller
{
    /**
     * Tampilkan daftar iklan kos milik pemilik yang sedang login.
     */
    public function index(): View
    {
        $kosts = auth()->user()->kosts()
            ->with(['province', 'regency', 'rooms', 'primaryImage'])
            ->latest()
            ->get();

        return view('owner.kosts.index', compact('kosts'));
    }

    /**
     * Form pembuatan iklan kos baru.
     */
    public function create(): View
    {
        $provinces = Province::orderBy('name')->get(['id', 'name']);
        $facilitiesByCategory = Facility::all()->groupBy('category');

        return view('owner.kosts.create', compact('provinces', 'facilitiesByCategory'));
    }

    /**
     * Simpan data iklan kos baru ke database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|string|size:2|exists:provinces,id',
            'regency_id' => 'required|string|size:4|exists:regencies,id',
            'type' => 'required|in:putra,putri,campur',
            'rent_type' => 'required|in:bulanan,tahunan,harian',
            'price_start' => 'required|numeric|min:100000',
            'address' => 'required|string|max:1000',
            'description' => 'nullable|string|max:5000',
            'rules' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'action_type' => 'required|in:draft,pending',

            // Data Kamar Awal
            'room_name' => 'required|string|max:255',
            'room_size' => 'nullable|string|max:50',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',

            // Fasilitas
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',

            // Foto cover (opsional di MVP tahap ini)
            'cover_image' => 'nullable|image|max:3072', // Max 3MB
        ]);

        DB::transaction(function () use ($validated, $request) {
            $user = auth()->user();

            // Pastikan user memiliki role pemilik jika belum ada
            if (!$user->hasAnyRole(['pemilik', 'admin', 'superadmin'])) {
                $user->assignRole('pemilik');
            }

            // 1. Simpan Data Kos
            $kost = Kost::create([
                'user_id' => $user->id,
                'province_id' => $validated['province_id'],
                'regency_id' => $validated['regency_id'],
                'name' => $validated['name'],
                'type' => $validated['type'],
                'rent_type' => $validated['rent_type'],
                'price_start' => $validated['price_start'],
                'address' => $validated['address'],
                'description' => $validated['description'] ?? null,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'verification_status' => $validated['action_type'] === 'pending' ? 'pending' : 'draft',
                'is_available' => true,
                'is_featured' => false,
            ]);

            // 2. Hubungkan Fasilitas
            if (!empty($validated['facilities'])) {
                $kost->facilities()->sync($validated['facilities']);
            }

            // 3. Buat Kamar Awal
            Room::create([
                'kost_id' => $kost->id,
                'name' => $validated['room_name'],
                'price' => $validated['price_start'],
                'total_rooms' => $validated['total_rooms'],
                'available_rooms' => $validated['available_rooms'],
                'room_size' => $validated['room_size'] ?? '3x4 m',
            ]);

            // 4. Unggah Foto Cover jika ada
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('kosts/covers', 'public');
                KostImage::create([
                    'kost_id' => $kost->id,
                    'file_path' => $path,
                    'is_primary' => true,
                    'sort_order' => 0,
                ]);
            }
        });

        $message = $request->input('action_type') === 'pending'
            ? 'Iklan kos berhasil diajukan! Tim admin kami akan segera memverifikasi kelengkapan kos Anda.'
            : 'Draft iklan kos berhasil disimpan. Anda dapat melengkapinya kapan saja.';

        return redirect()->route('owner.kosts.index')->with('success', $message);
    }

    /**
     * Form edit iklan kos.
     */
    public function edit(Kost $kost): View
    {
        // Pastikan hanya pemilik yang bisa mengedit
        if ($kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengedit iklan ini.');
        }

        $provinces = Province::orderBy('name')->get(['id', 'name']);
        $regencies = Regency::where('province_id', $kost->province_id)->orderBy('name')->get(['id', 'name']);
        $facilitiesByCategory = Facility::all()->groupBy('category');
        $selectedFacilityIds = $kost->facilities()->pluck('facilities.id')->toArray();
        $primaryRoom = $kost->rooms()->first();

        return view('owner.kosts.edit', compact('kost', 'provinces', 'regencies', 'facilitiesByCategory', 'selectedFacilityIds', 'primaryRoom'));
    }

    /**
     * Perbarui data iklan kos.
     */
    public function update(Request $request, Kost $kost): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province_id' => 'required|string|size:2|exists:provinces,id',
            'regency_id' => 'required|string|size:4|exists:regencies,id',
            'type' => 'required|in:putra,putri,campur',
            'rent_type' => 'required|in:bulanan,tahunan,harian',
            'price_start' => 'required|numeric|min:100000',
            'address' => 'required|string|max:1000',
            'description' => 'nullable|string|max:5000',
            'rules' => 'nullable|string|max:2000',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'action_type' => 'required|in:draft,pending',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,id',
        ]);

        DB::transaction(function () use ($validated, $kost, $request) {
            $kost->update([
                'province_id' => $validated['province_id'],
                'regency_id' => $validated['regency_id'],
                'name' => $validated['name'],
                'type' => $validated['type'],
                'rent_type' => $validated['rent_type'],
                'price_start' => $validated['price_start'],
                'address' => $validated['address'],
                'description' => $validated['description'] ?? null,
                'rules' => $validated['rules'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'verification_status' => $validated['action_type'] === 'pending' ? 'pending' : $kost->verification_status,
            ]);

            if (isset($validated['facilities'])) {
                $kost->facilities()->sync($validated['facilities']);
            }
        });

        return redirect()->route('owner.kosts.index')->with('success', 'Perubahan iklan kos berhasil disimpan!');
    }

    /**
     * Hapus iklan kos.
     */
    public function destroy(Kost $kost): RedirectResponse
    {
        if ($kost->user_id !== auth()->id() && !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            abort(403);
        }

        $kost->delete();

        return redirect()->route('owner.kosts.index')->with('success', 'Iklan kos berhasil dihapus.');
    }
}
