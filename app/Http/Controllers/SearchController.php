<?php

namespace App\Http\Controllers;

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

        // Koleksi dataset kos (komprehensif untuk pengujian filter sebelum modul CRUD kos aktif)
        $dataset = collect([
            [
                'id' => 1,
                'title' => 'Seven Kost Melati Residence Exclusive',
                'slug' => 'seven-kost-melati-residence-exclusive',
                'province_id' => '31', // DKI Jakarta
                'regency_id' => '3171', // Kota Jakarta Selatan
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Selatan',
                'type' => 'campur',
                'price' => 1850000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 2,
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Water Heater', 'Parkir Mobil'],
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(2),
            ],
            [
                'id' => 2,
                'title' => 'Griya Jasmine Putri Dekat Kampus UGM',
                'slug' => 'griya-jasmine-putri-ugm',
                'province_id' => '34', // DI Yogyakarta
                'regency_id' => '3404', // Sleman
                'province' => 'DI Yogyakarta',
                'city' => 'Sleman',
                'type' => 'putri',
                'price' => 1200000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 4,
                'facilities' => ['AC', 'WiFi', 'Dapur Bersama', 'Parkir Mobil', 'Kasur'],
                'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(5),
            ],
            [
                'id' => 3,
                'title' => 'D’Executive Paviliun Pasteur',
                'slug' => 'd-executive-paviliun-pasteur',
                'province_id' => '32', // Jawa Barat
                'regency_id' => '3273', // Kota Bandung
                'province' => 'Jawa Barat',
                'city' => 'Bandung',
                'type' => 'putra',
                'price' => 1600000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => false,
                'availableRooms' => 1,
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Kasur', 'Lemari'],
                'image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(1),
            ],
            [
                'id' => 4,
                'title' => 'Surabaya Barat Modern Smart Kost',
                'slug' => 'surabaya-barat-modern-smart-kost',
                'province_id' => '35', // Jawa Timur
                'regency_id' => '3578', // Kota Surabaya
                'province' => 'Jawa Timur',
                'city' => 'Surabaya',
                'type' => 'campur',
                'price' => 2100000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 3,
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Parkir Mobil', 'Water Heater'],
                'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(10),
            ],
            [
                'id' => 5,
                'title' => 'Kost Bunga Mawar Syariah Asri',
                'slug' => 'kost-bunga-mawar-syariah-asri',
                'province_id' => '35', // Jawa Timur
                'regency_id' => '3573', // Kota Malang
                'province' => 'Jawa Timur',
                'city' => 'Malang',
                'type' => 'putri',
                'price' => 850000,
                'rentType' => 'bulan',
                'isVerified' => false,
                'isFeatured' => false,
                'availableRooms' => 5,
                'facilities' => ['WiFi', 'Kasur', 'Lemari', 'Dapur Bersama'],
                'image' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(15),
            ],
            [
                'id' => 6,
                'title' => 'Sunset Bali Residence Sunset Road',
                'slug' => 'sunset-bali-residence-sunset-road',
                'province_id' => '51', // Bali
                'regency_id' => '5171', // Denpasar / Badung
                'province' => 'Bali',
                'city' => 'Denpasar',
                'type' => 'campur',
                'price' => 2750000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 2,
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Parkir Mobil', 'Water Heater', 'Dapur Bersama'],
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(3),
            ],
            [
                'id' => 7,
                'title' => 'Kost Simpang Lima Hebat Nyaman',
                'slug' => 'kost-simpang-lima-hebat-nyaman',
                'province_id' => '33', // Jawa Tengah
                'regency_id' => '3374', // Kota Semarang
                'province' => 'Jawa Tengah',
                'city' => 'Semarang',
                'type' => 'putra',
                'price' => 950000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => false,
                'availableRooms' => 3,
                'facilities' => ['WiFi', 'Kasur', 'Lemari', 'Dapur Bersama'],
                'image' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(7),
            ],
            [
                'id' => 8,
                'title' => 'Medan Polonia Mansion Exclusive',
                'slug' => 'medan-polonia-mansion-exclusive',
                'province_id' => '12', // Sumatera Utara
                'regency_id' => '1271', // Kota Medan
                'province' => 'Sumatera Utara',
                'city' => 'Medan',
                'type' => 'campur',
                'price' => 1700000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 1,
                'facilities' => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'Parkir Mobil'],
                'image' => 'https://images.unsplash.com/photo-1618773928121-c32242e63f39?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(4),
            ],
        ]);

        // Terapkan filter dinamis
        $filtered = $dataset->filter(function ($item) use ($keyword, $selectedProvinceId, $selectedRegencyId, $selectedType, $minPrice, $maxPrice, $selectedFacilities) {
            // Filter keyword
            if ($keyword) {
                $searchStr = strtolower($item['title'] . ' ' . $item['city'] . ' ' . $item['province']);
                if (!str_contains($searchStr, strtolower($keyword))) {
                    return false;
                }
            }

            // Filter Provinsi
            if ($selectedProvinceId && $item['province_id'] !== $selectedProvinceId) {
                return false;
            }

            // Filter Kota/Kabupaten
            if ($selectedRegencyId && $item['regency_id'] !== $selectedRegencyId) {
                return false;
            }

            // Filter Tipe
            if ($selectedType && $item['type'] !== strtolower($selectedType)) {
                return false;
            }

            // Filter Harga Minimal
            if ($minPrice && $item['price'] < (int) $minPrice) {
                return false;
            }

            // Filter Harga Maksimal
            if ($maxPrice && $item['price'] > (int) $maxPrice) {
                return false;
            }

            // Filter Fasilitas (harus memiliki fasilitas yang dicentang)
            if (!empty($selectedFacilities)) {
                foreach ($selectedFacilities as $facility) {
                    $hasFacility = collect($item['facilities'])->contains(function ($fac) use ($facility) {
                        return str_contains(strtolower($fac), strtolower($facility));
                    });
                    if (!$hasFacility) {
                        return false;
                    }
                }
            }

            return true;
        });

        // Pengurutan (Sorting)
        $sorted = match($sort) {
            'termurah' => $filtered->sortBy('price'),
            'termahal' => $filtered->sortByDesc('price'),
            'terbaru' => $filtered->sortByDesc('created_at'),
            default => $filtered->sortByDesc('isFeatured')->values(), // Unggulan selalu di atas
        };

        $kosts = $sorted->values();
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
