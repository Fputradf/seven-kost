<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Kost;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class KostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pemilik = User::where('email', 'pemilik@sevenkos.com')->first();
        if (!$pemilik) {
            return;
        }

        $allFacilities = Facility::all();

        $sampleKosts = [
            [
                'name' => 'Seven Kost Melati Residence Exclusive',
                'province_id' => '31', // DKI Jakarta
                'regency_id' => '3171', // Kota Jakarta Selatan
                'type' => 'campur',
                'price_start' => 1850000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. RS Fatmawati No. 45, Cilandak, Jakarta Selatan',
                'description' => 'Kos eksklusif modern berfasilitas hotel dekat Stasiun MRT Fatmawati dan Cilandak Town Square. Sangat cocok bagi profesional muda dan eksekutif.',
                'rules' => 'Jam malam tamu pukul 23:00 WIB, dilarang membawa hewan peliharaan, dilarang merokok di dalam kamar.',
                'latitude' => -6.2925,
                'longitude' => 106.7972,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'room' => ['name' => 'Kamar Deluxe Springbed', 'price' => 1850000, 'total' => 8, 'available' => 3, 'size' => '3x4 m'],
            ],
            [
                'name' => 'Griya Jasmine Putri Dekat Kampus UGM',
                'province_id' => '34', // DI Yogyakarta
                'regency_id' => '3404', // Sleman
                'type' => 'putri',
                'price_start' => 1200000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Kaliurang KM 5.2, Gang Pandega Marta, Sleman, Yogyakarta',
                'description' => 'Hunian nyaman, bersih, dan asri khusus mahasiswi. Hanya 5 menit berkendara ke Kampus UGM dan UNY.',
                'rules' => 'Khusus putri, tamu pria hanya diperbolehkan di ruang tamu bersama, gerbang dikunci pukul 22:30 WIB.',
                'latitude' => -7.7600,
                'longitude' => 110.3750,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'room' => ['name' => 'Tipe Single Balkon', 'price' => 1200000, 'total' => 10, 'available' => 4, 'size' => '3x3.5 m'],
            ],
            [
                'name' => 'D’Executive Paviliun Pasteur Bandung',
                'province_id' => '32', // Jawa Barat
                'regency_id' => '3273', // Kota Bandung
                'type' => 'putra',
                'price_start' => 1600000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dr. Djunjunan No. 120, Sukajadi, Kota Bandung',
                'description' => 'Akses langsung ke Gerbang Tol Pasteur, dekat Mall PVJ dan RSHS Bandung. Fasilitas lengkap dan parkir mobil luas.',
                'rules' => 'Dilarang membawa miras dan obat terlarang. Tamu wajib lapor petugas keamanan.',
                'latitude' => -6.8910,
                'longitude' => 107.5850,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => false,
                'room' => ['name' => 'Paviliun Single', 'price' => 1600000, 'total' => 6, 'available' => 1, 'size' => '4x4 m'],
            ],
            [
                'name' => 'Kost Asri Mawar Melati Surabaya',
                'province_id' => '35', // Jawa Timur
                'regency_id' => '3578', // Kota Surabaya
                'type' => 'campur',
                'price_start' => 1350000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dharmawangsa Barat No. 12, Gubeng, Surabaya',
                'description' => 'Lokasi strategis dekat Kampus B UNAIR dan RSUD Dr. Soetomo Surabaya. Suasana tenang dan aman.',
                'rules' => 'Menjaga ketenangan dan kebersihan bersama.',
                'latitude' => -7.2750,
                'longitude' => 112.7560,
                'verification_status' => 'pending',
                'is_available' => true,
                'is_featured' => false,
                'room' => ['name' => 'Tipe Standar AC', 'price' => 1350000, 'total' => 5, 'available' => 2, 'size' => '3x3 m'],
            ],
        ];

        foreach ($sampleKosts as $data) {
            $roomData = $data['room'];
            unset($data['room']);

            $data['user_id'] = $pemilik->id;

            $kost = Kost::firstOrCreate(
                ['name' => $data['name']],
                $data
            );

            // Buat Kamar
            Room::firstOrCreate(
                ['kost_id' => $kost->id, 'name' => $roomData['name']],
                [
                    'price' => $roomData['price'],
                    'total_rooms' => $roomData['total'],
                    'available_rooms' => $roomData['available'],
                    'room_size' => $roomData['size'],
                ]
            );

            // Pasang 5 fasilitas acak
            if ($kost->facilities()->count() === 0 && $allFacilities->count() > 0) {
                $randomFacs = $allFacilities->random(min(6, $allFacilities->count()))->pluck('id');
                $kost->facilities()->sync($randomFacs);
            }
        }
    }
}

