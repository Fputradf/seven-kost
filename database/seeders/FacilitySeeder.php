<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            // Kategori Kamar
            ['name' => 'AC', 'icon' => 'ac', 'category' => 'kamar'],
            ['name' => 'Kamar Mandi Dalam', 'icon' => 'bath', 'category' => 'kamar'],
            ['name' => 'Water Heater', 'icon' => 'shower', 'category' => 'kamar'],
            ['name' => 'Kasur / Springbed', 'icon' => 'bed', 'category' => 'kamar'],
            ['name' => 'Lemari Pakaian', 'icon' => 'wardrobe', 'category' => 'kamar'],
            ['name' => 'Meja & Kursi Belajar', 'icon' => 'desk', 'category' => 'kamar'],
            ['name' => 'Jendela Luar', 'icon' => 'window', 'category' => 'kamar'],
            ['name' => 'Kloset Duduk', 'icon' => 'toilet', 'category' => 'kamar'],
            ['name' => 'Smart Lock', 'icon' => 'lock', 'category' => 'kamar'],

            // Kategori Fasilitas Umum
            ['name' => 'WiFi Cepat', 'icon' => 'wifi', 'category' => 'umum'],
            ['name' => 'Parkir Motor Luas', 'icon' => 'bike', 'category' => 'umum'],
            ['name' => 'Parkir Mobil', 'icon' => 'car', 'category' => 'umum'],
            ['name' => 'Dapur Bersama', 'icon' => 'kitchen', 'category' => 'umum'],
            ['name' => 'Kulkas Bersama', 'icon' => 'fridge', 'category' => 'umum'],
            ['name' => 'Mesin Cuci', 'icon' => 'laundry', 'category' => 'umum'],
            ['name' => 'Dispenser Air Minum', 'icon' => 'water', 'category' => 'umum'],
            ['name' => 'CCTV 24 Jam', 'icon' => 'cctv', 'category' => 'umum'],
            ['name' => 'Security / Penjaga Kos', 'icon' => 'security', 'category' => 'umum'],
            ['name' => 'Ruang Santai / Tamu', 'icon' => 'couch', 'category' => 'umum'],
            ['name' => 'Akses 24 Jam', 'icon' => 'clock', 'category' => 'umum'],

            // Kategori Fasilitas Sekitar
            ['name' => 'Dekat Halte / Stasiun KRL', 'icon' => 'train', 'category' => 'sekitar'],
            ['name' => 'Dekat Kampus', 'icon' => 'school', 'category' => 'sekitar'],
            ['name' => 'Dekat Minimarket / Warung', 'icon' => 'store', 'category' => 'sekitar'],
            ['name' => 'Dekat Rumah Sakit / Apotek', 'icon' => 'hospital', 'category' => 'sekitar'],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(['name' => $facility['name']], $facility);
        }
    }
}

