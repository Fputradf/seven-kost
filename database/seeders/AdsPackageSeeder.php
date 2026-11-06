<?php

namespace Database\Seeders;

use App\Models\AdsPackage;
use Illuminate\Database\Seeder;

class AdsPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Paket Highlight 7 Hari',
                'duration_days' => 7,
                'price' => 49000,
                'description' => 'Iklan kos Anda diberi lencana Unggulan dan diposisikan di baris teratas hasil pencarian selama 1 minggu.',
                'is_active' => true,
            ],
            [
                'name' => 'Paket Premium 14 Hari',
                'duration_days' => 14,
                'price' => 89000,
                'description' => 'Mendapatkan lencana Unggulan 2 minggu penuh dan tampil di rekomendasi kota populer.',
                'is_active' => true,
            ],
            [
                'name' => 'Paket Sultan 30 Hari',
                'duration_days' => 30,
                'price' => 149000,
                'description' => 'Tampil di banner halaman utama, prioritas pertama di peta nasional, dan lencana Unggulan selama 1 bulan penuh.',
                'is_active' => true,
            ],
        ];

        foreach ($packages as $pkg) {
            AdsPackage::firstOrCreate(['name' => $pkg['name']], $pkg);
        }
    }
}
