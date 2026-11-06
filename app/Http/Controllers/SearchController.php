<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Tampilkan halaman hasil pencarian kos dengan filter.
     */
    public function index(Request $request): View
    {
        $provinces = Province::orderBy('name')->get(['id', 'name']);

        // Tangkap input filter dari request
        $keyword = $request->query('q', '');
        $selectedProvinceId = $request->query('provinsi', '');
        $selectedRegencyId = $request->query('kota', '');
        $selectedType = $request->query('tipe', '');
        $minPrice = $request->query('min_price');
        $maxPrice = $request->query('max_price');
        $selectedFacilities = (array) $request->query('fasilitas', []);
        $sort = $request->query('sort', 'unggulan'); // unggulan, termurah, termahal, terbaru

        // Kabupaten untuk sidebar jika provinsi dipilih
        $regencies = [];
        if ($selectedProvinceId) {
            $regencies = Regency::where('province_id', $selectedProvinceId)
                ->orderBy('name')
                ->get(['id', 'name', 'type']);
        }

        // Query Database Eloquent
        $query = Kost::with(['province', 'regency', 'facilities', 'rooms', 'primaryImage']);

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($selectedProvinceId) {
            $query->where('province_id', $selectedProvinceId);
        }

        if ($selectedRegencyId) {
            $query->where('regency_id', $selectedRegencyId);
        }

        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        if ($minPrice) {
            $query->where('price_start', '>=', (float) $minPrice);
        }

        if ($maxPrice) {
            $query->where('price_start', '<=', (float) $maxPrice);
        }

        if (!empty($selectedFacilities)) {
            foreach ($selectedFacilities as $facilityName) {
                $query->whereHas('facilities', function ($f) use ($facilityName) {
                    $f->where('name', 'like', "%{$facilityName}%");
                });
            }
        }

        // Pengurutan (Sorting)
        match ($sort) {
            'termurah' => $query->orderBy('price_start', 'asc'),
            'termahal' => $query->orderBy('price_start', 'desc'),
            'terbaru' => $query->latest(),
            default => $query->orderBy('is_featured', 'desc')->latest(),
        };

        $dbKosts = $query->get();

        // Transformasi ke format konsisten untuk komponen Blade
        $kosts = $dbKosts->map(function ($k) {
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

        $totalCount = $kosts->count();

        // Nama wilayah terpilih untuk heading
        $selectedProvinceName = $selectedProvinceId ? Province::find($selectedProvinceId)?->name : null;
        $selectedRegencyName = $selectedRegencyId ? Regency::find($selectedRegencyId)?->name : null;

        return view('search', compact(
            'provinces',
            'regencies',
            'kosts',
            'totalCount',
            'keyword',
            'selectedProvinceId',
            'selectedRegencyId',
            'selectedProvinceName',
            'selectedRegencyName',
            'selectedType',
            'minPrice',
            'maxPrice',
            'selectedFacilities',
            'sort'
        ));
    }
}
