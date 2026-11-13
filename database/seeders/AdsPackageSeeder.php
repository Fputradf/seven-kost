<?php

namespace Database\Seeders;

use App\Models\AdsPackage;
use App\Models\Banner;
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

        // Seed Banner Iklan Komersial (BR-11, Penayangan Beranda, Layanan, & Marketplace)
        $banners = [
            [
                'title' => 'Laundry Express Mahasiswa - Diskon 20% Jemput Antar Kos',
                'image_path' => 'images/frontend/banner-jakarta-wide.jpg',
                'target_url' => url('/layanan#laundry'),
                'is_active' => true,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(28),
            ],
            [
                'title' => 'Toko Meubel & Kasur Kos Nusantara - Gratis Kirim Area Kampus',
                'image_path' => 'images/frontend/banner-bandung-wide.jpg',
                'target_url' => url('/marketplace?kategori=kasur'),
                'is_active' => true,
                'starts_at' => now()->subDays(1),
                'ends_at' => now()->addDays(60),
            ],
            [
                'title' => 'Paket Wifi Hemat Anak Kos 50 Mbps - Langganan Cepat Bebas FUP',
                'image_path' => 'images/frontend/banner-yogyakarta-wide.jpg',
                'target_url' => url('/paket-iklan'),
                'is_active' => true,
                'starts_at' => now(),
                'ends_at' => now()->addDays(90),
            ],
        ];

        foreach ($banners as $b) {
            Banner::firstOrCreate(['title' => $b['title']], $b);
        }
    }
}
