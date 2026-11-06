<?php

namespace App\Http\Controllers;

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

        // Data Mock Kos Unggulan (fase sebelum CRUD Kos pemilik dibuat di Minggu 2)
        $featuredKosts = [
            [
                'title' => 'Seven Kost Melati Residence Exclusive',
                'slug' => 'seven-kost-melati-residence-exclusive',
                'type' => 'campur',
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'price' => 1850000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 2,
                'facilities' => ['AC', 'WiFi 100Mbps', 'K. Mandi Dalam', 'Water Heater'],
                'image' => 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Griya Jasmine Putri Dekat Kampus UGM',
                'slug' => 'griya-jasmine-putri-ugm',
                'type' => 'putri',
                'city' => 'Sleman',
                'province' => 'DI Yogyakarta',
                'price' => 1200000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 4,
                'facilities' => ['AC', 'WiFi Cepat', 'Dapur Bersama', 'Parkir Mobil'],
                'image' => 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'D’Executive Paviliun Pasteur',
                'slug' => 'd-executive-paviliun-pasteur',
                'type' => 'putra',
                'city' => 'Bandung',
                'province' => 'Jawa Barat',
                'price' => 1600000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => false,
                'availableRooms' => 1,
                'facilities' => ['AC', 'K. Mandi Dalam', 'Security 24 Jam', 'Kasur King'],
                'image' => 'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Surabaya Barat Modern Smart Kost',
                'slug' => 'surabaya-barat-modern-smart-kost',
                'type' => 'campur',
                'city' => 'Surabaya',
                'province' => 'Jawa Timur',
                'price' => 2100000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 3,
                'facilities' => ['Smart Lock', 'AC', 'Balkon', 'Gym Area'],
                'image' => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Kost Bunga Mawar Syariah',
                'slug' => 'kost-bunga-mawar-syariah',
                'type' => 'putri',
                'city' => 'Malang',
                'province' => 'Jawa Timur',
                'price' => 850000,
                'rentType' => 'bulan',
                'isVerified' => false,
                'isFeatured' => false,
                'availableRooms' => 5,
                'facilities' => ['WiFi', 'Kasur Springbed', 'Lemari', 'CCTV'],
                'image' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80',
            ],
            [
                'title' => 'Sunset Bali Sunset Road Residence',
                'slug' => 'sunset-bali-sunset-road-residence',
                'type' => 'campur',
                'city' => 'Badung',
                'province' => 'Bali',
                'price' => 2750000,
                'rentType' => 'bulan',
                'isVerified' => true,
                'isFeatured' => true,
                'availableRooms' => 2,
                'facilities' => ['Kolam Renang', 'AC', 'Kitchen Set', 'Housekeeping'],
                'image' => 'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=800&q=80',
            ],
        ];

        return view('welcome', compact('provinces', 'popularCities', 'featuredKosts'));
    }
}
