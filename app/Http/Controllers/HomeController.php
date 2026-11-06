<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Tampilkan halaman Beranda (Homepage).
     */
    public function index(): View
    {
        $provinces = Province::orderBy('name')->get(['id', 'name']);

        // Kota Populer untuk kartu shortcut
        $popularCityIds = ['3171', '3471', '3273', '3578', '3573', '5171']; // Jaksel, Kota Yogya, Bandung, Surabaya, Malang, Denpasar
        $popularCities = Regency::whereIn('id', $popularCityIds)
            ->with('province')
            ->get();

        // Ambil Kos Unggulan & Rekomendasi langsung dari database
        $featuredKosts = Kost::with(['province', 'regency', 'facilities', 'rooms', 'primaryImage'])
            ->where('is_available', true)
            ->orderBy('is_featured', 'desc')
            ->latest()
            ->take(6)
            ->get()
            ->map(function ($k) {
                return [
                    'id' => $k->id,
                    'title' => $k->name,
                    'slug' => $k->slug,
                    'city' => $k->regency->name ?? 'Indonesia',
                    'province' => $k->province->name ?? '',
                    'type' => $k->type,
                    'price' => $k->price_start,
                    'rentType' => $k->rent_type,
                    'isVerified' => $k->verification_status === 'verified',
                    'isFeatured' => (bool) $k->is_featured,
                    'availableRooms' => $k->rooms->sum('available_rooms'),
                    'facilities' => $k->facilities->pluck('name')->toArray(),
                    'image' => $k->primaryImage ? asset('storage/' . $k->primaryImage->file_path) : null,
                ];
            });

        return view('welcome', compact('provinces', 'popularCities', 'featuredKosts'));
    }
}
