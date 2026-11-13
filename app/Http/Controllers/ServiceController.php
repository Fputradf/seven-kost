<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Tampilkan halaman katalog layanan kebutuhan kos (Layanan Mitra SevenKost).
     */
    public function index(): View
    {
        // Banner Iklan Komersial Aktif untuk Layanan
        $banners = Banner::where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->take(4)
            ->get();

        $services = [
            [
                'id' => 'laundry',
                'category' => 'laundry',
                'title' => 'Seven Laundry Kos',
                'badge' => 'Paling Populer',
                'is_available' => true,
                'image' => asset('images/frontend/service-laundry.jpg'),
                'description' => 'Layanan cuci, setrika rapi, dan lipat dengan pewangi premium. Pakaian diambil dan diantar langsung ke depan pintu kosmu.',
                'price_start' => 7000,
                'price_unit' => '/ kg',
                'rating' => 4.9,
                'reviews_count' => 124,
                'features' => [
                    'Gratis jemput & antar langsung ke kos',
                    'Deterjen anti-bakteri & pewangi grade hotel',
                    'Pemisahan pakaian putih dan berwarna',
                    'Estimasi selesai tepat waktu (2-3 hari / express 24 jam)',
                ],
                'packages' => [
                    ['name' => 'Laundry Kiloan Reguler (2-3 Hari)', 'price' => 7000, 'unit' => '/kg'],
                    ['name' => 'Laundry Express Kilat (24 Jam)', 'price' => 12000, 'unit' => '/kg'],
                    ['name' => 'Cuci Bedcover & Sprei Kos', 'price' => 25000, 'unit' => '/set'],
                    ['name' => 'Cuci Sepatu & Sneakers', 'price' => 30000, 'unit' => '/pasang'],
                ],
            ],
            [
                'id' => 'cleaning',
                'category' => 'cleaning',
                'title' => 'Seven Clean Kamar & Toilet',
                'badge' => 'Rekomendasi',
                'is_available' => true,
                'image' => asset('images/frontend/service-cleaning.jpg'),
                'description' => 'Jasa pembersihan kamar kos dan pembersihan kerak kamar mandi oleh staf terlatih menggunakan peralatan dan disinfektan profesional.',
                'price_start' => 50000,
                'price_unit' => '/ sesi',
                'rating' => 4.8,
                'reviews_count' => 98,
                'features' => [
                    'Pembersihan kerak kloset & dinding kamar mandi',
                    'Sapu, pel wangi, dan vakum debu menyeluruh',
                    'Pembersihan kaca jendela & perabot meja/lemari',
                    'Penyemprotan disinfektan ruangan higienis',
                ],
                'packages' => [
                    ['name' => 'Paket Kamar Resik (Sapu, Pel & Debu)', 'price' => 50000, 'unit' => '/sesi'],
                    ['name' => 'Deep Clean Kamar Mandi Kos', 'price' => 65000, 'unit' => '/sesi'],
                    ['name' => 'Paket Komplit Kamar + Toilet', 'price' => 95000, 'unit' => '/sesi'],
                    ['name' => 'General Cleaning Pindahan Kos', 'price' => 135000, 'unit' => '/sesi'],
                ],
            ],
            [
                'id' => 'catering',
                'category' => 'catering',
                'title' => 'Seven Katering Harian',
                'badge' => 'Coming Soon',
                'is_available' => false,
                'image' => asset('images/frontend/service-catering.jpg'),
                'description' => 'Paket makan siang & malam praktis, sehat, dan variatif diantar hangat tepat jam makan ke kosanmu.',
                'price_start' => 25000,
                'price_unit' => '/ hari',
                'rating' => 4.7,
                'reviews_count' => 65,
                'features' => [
                    'Menu berganti setiap hari (bebas bosan)',
                    'Bebas MSG berlebih, higienis & halal',
                    'Pengantaran tepat waktu makan siang (11.30) & malam (17.30)',
                    'Tersedia opsi nasi merah & sayur porsi ekstra',
                ],
                'packages' => [
                    ['name' => 'Paket Mahasiswa Hemat (Siang & Malam)', 'price' => 25000, 'unit' => '/hari'],
                    ['name' => 'Paket Lengkap Pekerja (Lauk Utama + Jus)', 'price' => 35000, 'unit' => '/hari'],
                    ['name' => 'Paket Mingguan (5 Hari Kerja)', 'price' => 120000, 'unit' => '/minggu'],
                    ['name' => 'Paket Bulanan Super Hemat (20 Hari)', 'price' => 450000, 'unit' => '/bulan'],
                ],
            ],
            [
                'id' => 'maintenance',
                'category' => 'maintenance',
                'title' => 'Seven Care Teknisi & AC',
                'badge' => 'Teknisi Berpengalaman',
                'is_available' => true,
                'image' => asset('images/frontend/cta-kost-room.jpg'),
                'description' => 'Layanan perbaikan kelistrikan, servis cuci AC dingin, dan perbaikan perabot kamar kos oleh teknisi tersertifikasi.',
                'price_start' => 40000,
                'price_unit' => '/ sesi',
                'rating' => 4.9,
                'reviews_count' => 42,
                'features' => [
                    'Cuci AC hemat freon & pengecekan kebocoran',
                    'Perbaikan lampu, colokan listrik & saklar kos',
                    'Perbaikan engsel pintu, kunci kamar & shower',
                    'Garansi servis 14 hari kerja',
                ],
                'packages' => [
                    ['name' => 'Cuci AC Standar 0.5 - 1 PK', 'price' => 75000, 'unit' => '/unit'],
                    ['name' => 'Isi Ulang Freon AC R32/R410A', 'price' => 150000, 'unit' => '/unit'],
                    ['name' => 'Cek Kelistrikan & Pasang Lampu', 'price' => 40000, 'unit' => '/sesi'],
                    ['name' => 'Perbaikan Pipa & Kran Bocor', 'price' => 50000, 'unit' => '/titik'],
                ],
            ],
        ];

        return view('layanan', compact('services', 'banners'));
    }
}
