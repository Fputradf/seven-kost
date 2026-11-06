<?php

namespace App\Http\Controllers;

use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MapController extends Controller
{
    /**
     * Tampilkan halaman Peta Pencarian Nasional (Leaflet + Marker Cluster).
     */
    public function index(Request $request): View
    {
        $provinces = Province::orderBy('name')->get(['id', 'name']);

        $selectedProvinceId = $request->query('provinsi', '');
        $selectedRegencyId = $request->query('kota', '');
        $selectedType = $request->query('tipe', '');

        $regencies = [];
        if ($selectedProvinceId) {
            $regencies = Regency::where('province_id', $selectedProvinceId)->orderBy('name')->get(['id', 'name']);
        }

        // Ambil kos yang memiliki koordinat latitude dan longitude
        $query = Kost::with(['province', 'regency', 'primaryImage'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('verification_status', 'verified');

        if ($selectedProvinceId) {
            $query->where('province_id', $selectedProvinceId);
        }

        if ($selectedRegencyId) {
            $query->where('regency_id', $selectedRegencyId);
        }

        if ($selectedType) {
            $query->where('type', $selectedType);
        }

        $kosts = $query->get()->map(function ($k) {
            return [
                'id' => $k->id,
                'name' => $k->name,
                'slug' => $k->slug,
                'type' => $k->type,
                'price' => (float) $k->price_start,
                'price_formatted' => 'Rp ' . number_format($k->price_start, 0, ',', '.'),
                'lat' => (float) $k->latitude,
                'lng' => (float) $k->longitude,
                'city' => $k->regency->name ?? '',
                'address' => $k->address,
                'image' => $k->primaryImage ? asset('storage/' . $k->primaryImage->file_path) : 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=600&q=80',
                'url' => route('kost.detail', $k->slug),
            ];
        });

        return view('map-search', compact(
            'provinces',
            'regencies',
            'kosts',
            'selectedProvinceId',
            'selectedRegencyId',
            'selectedType'
        ));
    }
}
