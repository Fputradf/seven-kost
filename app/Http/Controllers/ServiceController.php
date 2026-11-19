<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\View\View;

class ServiceController extends Controller
{
    /**
     * Tampilkan halaman katalog layanan kebutuhan kos (Layanan Mitra SevenKost).
     * Terintegrasi dengan deteksi kamar kos aktif dan jangkauan mitra lokal di sekitar kos.
     */
    public function index(): View
    {
        // 1. Deteksi Kamar Kos Aktif Penghuni (Mendukung Multi-Kamar di Region Berbeda)
        $user = auth()->user();
        $activeBookings = collect();
        $activeBooking = null;

        if ($user) {
            $activeBookings = $user->bookings()
                ->whereIn('status', ['approved', 'active'])
                ->with(['kost.province', 'kost.regency', 'room'])
                ->latest()
                ->get();

            // Cek jika ada request spesifik kamar_id
            $selectedKamarId = request('kamar_id');
            if ($selectedKamarId) {
                $activeBooking = $activeBookings->firstWhere('id', (int) $selectedKamarId);
            }

            // Fallback ke kamar aktif terbaru
            if (! $activeBooking && $activeBookings->isNotEmpty()) {
                $activeBooking = $activeBookings->first();
            }
        }

        // Tentukan Region & Info Hunian Penghuni
        $hasActiveBooking = $activeBooking !== null;
        $tenantRegionName = null;
        $tenantKostName = null;
        $tenantRoomName = null;
        $tenantAddress = null;
        $tenantOccupantName = null;
        $tenantOccupantPhone = null;

        if ($hasActiveBooking && $activeBooking->kost) {
            $tenantRegionName = $activeBooking->kost->regency?->name ?? 'Kota Jakarta Selatan';
            $tenantKostName = $activeBooking->kost->name;
            $tenantRoomName = $activeBooking->room?->name ?? 'Kamar Hunian';
            $tenantAddress = $activeBooking->kost->address ?: ($tenantKostName.', '.$tenantRegionName);
            $tenantOccupantName = $activeBooking->tenant_name ?: $user->name;
            $tenantOccupantPhone = $activeBooking->tenant_phone ?: ($user->phone ?? '');
            $selectedCity = $tenantRegionName;
        } else {
            $selectedCity = request('kota', 'Jakarta');
        }

        // 2. Data Jaringan Mitra Regional (Coverage Radius per Kota/Wilayah)
        $regionalPartners = [
            'jakarta' => [
                'city_label' => 'DKI Jakarta (Jakarta Selatan & Pusat)',
                'badge_city' => 'Jakarta',
                'laundry' => [
                    'partner_name' => 'Seven Laundry Melati Jakarta',
                    'coverage_area' => 'Area Kemang, Fatmawati, Kuningan, Tebet',
                    'distance_approx' => '1.4 km dari kos Anda',
                    'delivery_time' => 'Estimasi jemput: 20-35 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Jakarta Selatan',
                    'coverage_area' => 'Area Jaksel & Jakpus',
                    'distance_approx' => '2.3 km dari kos Anda',
                    'delivery_time' => 'Estimasi tiba: 35 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Jakarta (Segera Hadir)',
                    'coverage_area' => 'Area Perkantoran & Kos Jaksel',
                    'distance_approx' => '2.5 km',
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care AC & Listrik Jakarta',
                    'coverage_area' => 'Radius 6 km area Jaksel & Jakpus',
                    'distance_approx' => '2.9 km dari kos Anda',
                    'delivery_time' => 'Teknisi profesional bergaransi',
                ],
            ],
            'depok' => [
                'city_label' => 'Kota Depok (Area Kampus UI & Margonda)',
                'badge_city' => 'Depok',
                'laundry' => [
                    'partner_name' => 'Seven Laundry UI Depok',
                    'coverage_area' => 'Area Kukusan, Margonda, Pondok Cina, Beji',
                    'distance_approx' => '1.8 km dari kos Anda',
                    'delivery_time' => 'Estimasi jemput: 20-30 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Margonda Resik',
                    'coverage_area' => 'Area Beji, Margonda, Sukmajaya',
                    'distance_approx' => '2.2 km dari kos Anda',
                    'delivery_time' => 'Estimasi tiba: 30-45 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Depok (Segera Hadir)',
                    'coverage_area' => 'Area Kampus UI & Gunadarma',
                    'distance_approx' => '2.5 km',
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi AC Depok',
                    'coverage_area' => 'Radius 6 km area Depok',
                    'distance_approx' => '3.0 km dari kos Anda',
                    'delivery_time' => 'Jadwal fleksibel (Pagi/Siang/Sore)',
                ],
            ],
            'bandung' => [
                'city_label' => 'Kota Bandung (Area Dago, Dipatiukur, Pasteur)',
                'badge_city' => 'Bandung',
                'laundry' => [
                    'partner_name' => 'Seven Laundry Dago Pasteur',
                    'coverage_area' => 'Area Coblong, Cidadap, Sukasari, Pasteur',
                    'distance_approx' => '2.1 km dari kos Anda',
                    'delivery_time' => 'Estimasi jemput: 25-35 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Bandung Parahyangan',
                    'coverage_area' => 'Area Dago, Pasteur, Sukajadi',
                    'distance_approx' => '2.8 km dari kos Anda',
                    'delivery_time' => 'Estimasi tiba: 35-45 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Bandung (Segera Hadir)',
                    'coverage_area' => 'Area Dago & Dipatiukur',
                    'distance_approx' => '2.0 km',
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi Dago Bandung',
                    'coverage_area' => 'Area Bandung Utara & Tengah',
                    'distance_approx' => '2.5 km dari kos Anda',
                    'delivery_time' => 'Garansi 14 hari pengerjaan',
                ],
            ],
            'yogyakarta' => [
                'city_label' => 'Yogyakarta & Sleman (Area UGM, UNY, Seturan)',
                'badge_city' => 'Yogyakarta',
                'laundry' => [
                    'partner_name' => 'Seven Laundry Pogung Sleman',
                    'coverage_area' => 'Area Pogung, Seturan, Condongcatur, Gejayan',
                    'distance_approx' => '1.5 km dari kos Anda',
                    'delivery_time' => 'Estimasi jemput: 20-30 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Jogja Istimewa',
                    'coverage_area' => 'Area Sleman & Kota Yogyakarta',
                    'distance_approx' => '2.4 km dari kos Anda',
                    'delivery_time' => 'Estimasi tiba: 30 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen UGM (Segera Hadir)',
                    'coverage_area' => 'Area Kampus UGM & UNY',
                    'distance_approx' => '1.8 km',
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi AC Sleman-Jogja',
                    'coverage_area' => 'Radius 5 km Jogja & Sleman',
                    'distance_approx' => '3.1 km dari kos Anda',
                    'delivery_time' => 'Teknisi bersertifikat resmi',
                ],
            ],
            'surabaya' => [
                'city_label' => 'Kota Surabaya (Area UNAIR, ITS, Gubeng)',
                'badge_city' => 'Surabaya',
                'laundry' => [
                    'partner_name' => 'Seven Laundry Gubeng Surabaya',
                    'coverage_area' => 'Area Gubeng, Mulyorejo, Sukolilo',
                    'distance_approx' => '2.0 km dari kos Anda',
                    'delivery_time' => 'Estimasi jemput: 30 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Surabaya Kilap',
                    'coverage_area' => 'Area Surabaya Timur & Selatan',
                    'distance_approx' => '2.7 km dari kos Anda',
                    'delivery_time' => 'Estimasi tiba: 35 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Surabaya (Segera Hadir)',
                    'coverage_area' => 'Area UNAIR & ITS',
                    'distance_approx' => '2.2 km',
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi AC Surabaya',
                    'coverage_area' => 'Area Surabaya Timur & Pusat',
                    'distance_approx' => '3.3 km dari kos Anda',
                    'delivery_time' => 'Teknisi spesialis kos',
                ],
            ],
            'malang' => [
                'city_label' => 'Kota Malang (Area Kampus UB, UM, Lowokwaru)',
                'badge_city' => 'Malang',
                'laundry' => [
                    'partner_name' => 'Seven Laundry Soekarno-Hatta Malang',
                    'coverage_area' => 'Area Lowokwaru, Suhat, Klojen',
                    'distance_approx' => '1.7 km dari kos Anda',
                    'delivery_time' => 'Estimasi jemput: 25 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Malang Sejuk',
                    'coverage_area' => 'Area Lowokwaru & Sumbersari',
                    'distance_approx' => '2.1 km dari kos Anda',
                    'delivery_time' => 'Estimasi tiba: 30 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Malang (Segera Hadir)',
                    'coverage_area' => 'Area Kampus UB & UM',
                    'distance_approx' => '1.9 km',
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care AC & Listrik Malang',
                    'coverage_area' => 'Radius 5 km Kota Malang',
                    'distance_approx' => '2.8 km dari kos Anda',
                    'delivery_time' => 'Garansi teknisi lokal',
                ],
            ],
        ];

        // Resolusi City Key
        $searchKey = strtolower($selectedCity);
        $cityKey = 'jakarta';

        if (str_contains($searchKey, 'depok')) {
            $cityKey = 'depok';
        } elseif (str_contains($searchKey, 'bandung')) {
            $cityKey = 'bandung';
        } elseif (str_contains($searchKey, 'sleman') || str_contains($searchKey, 'yogyakarta') || str_contains($searchKey, 'jogja')) {
            $cityKey = 'yogyakarta';
        } elseif (str_contains($searchKey, 'surabaya')) {
            $cityKey = 'surabaya';
        } elseif (str_contains($searchKey, 'malang')) {
            $cityKey = 'malang';
        }

        $currentRegional = $regionalPartners[$cityKey];

        // 3. Banner Iklan Komersial Aktif untuk Layanan
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

        // 4. Katalog Layanan dengan Injeksi Mitra Lokal Terdekat
        $services = [
            [
                'id' => 'laundry',
                'category' => 'laundry',
                'title' => 'Seven Laundry Kos',
                'badge' => 'Paling Populer',
                'is_available' => true,
                'image' => asset('images/frontend/service-laundry.jpg'),
                'description' => 'Layanan cuci, setrika rapi, dan lipat dengan pewangi premium. Pakaian diambil dan diantar langsung ke depan pintu kamar kosmu.',
                'price_start' => 7000,
                'price_unit' => '/ kg',
                'rating' => 4.9,
                'reviews_count' => 124,
                'local_partner' => $currentRegional['laundry'],
                'features' => [
                    'Gratis jemput & antar langsung ke pintu kamar kos',
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
                'local_partner' => $currentRegional['cleaning'],
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
                'local_partner' => $currentRegional['catering'],
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
                'local_partner' => $currentRegional['maintenance'],
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

        return view('layanan', compact(
            'services',
            'banners',
            'activeBookings',
            'activeBooking',
            'hasActiveBooking',
            'tenantRegionName',
            'tenantKostName',
            'tenantRoomName',
            'tenantAddress',
            'tenantOccupantName',
            'tenantOccupantPhone',
            'regionalPartners',
            'currentRegional',
            'cityKey',
            'selectedCity'
        ));
    }
}
