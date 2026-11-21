<?php

namespace App\Http\Controllers;

use App\Models\AppSetting;
use App\Models\Banner;
use App\Models\Transaction;
use App\Models\User;
use App\Services\XenditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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
                'hub_lat' => -6.2615,
                'hub_lon' => 106.8106,
                'laundry' => [
                    'partner_name' => 'Seven Laundry Melati Jakarta',
                    'coverage_area' => 'Area Kemang, Fatmawati, Kuningan, Tebet',
                    'default_distance' => 1.4,
                    'delivery_time' => 'Estimasi jemput: 20-35 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Jakarta Selatan',
                    'coverage_area' => 'Area Jaksel & Jakpus',
                    'default_distance' => 2.3,
                    'delivery_time' => 'Estimasi tiba: 35 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Jakarta (Segera Hadir)',
                    'coverage_area' => 'Area Perkantoran & Kos Jaksel',
                    'default_distance' => 2.5,
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care AC & Listrik Jakarta',
                    'coverage_area' => 'Radius 6 km area Jaksel & Jakpus',
                    'default_distance' => 2.9,
                    'delivery_time' => 'Teknisi profesional bergaransi',
                ],
            ],
            'depok' => [
                'city_label' => 'Kota Depok (Area Kampus UI & Margonda)',
                'badge_city' => 'Depok',
                'hub_lat' => -6.3686,
                'hub_lon' => 106.8331,
                'laundry' => [
                    'partner_name' => 'Seven Laundry UI Depok',
                    'coverage_area' => 'Area Kukusan, Margonda, Pondok Cina, Beji',
                    'default_distance' => 1.8,
                    'delivery_time' => 'Estimasi jemput: 20-30 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Margonda Resik',
                    'coverage_area' => 'Area Beji, Margonda, Sukmajaya',
                    'default_distance' => 2.2,
                    'delivery_time' => 'Estimasi tiba: 30-45 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Depok (Segera Hadir)',
                    'coverage_area' => 'Area Kampus UI & Gunadarma',
                    'default_distance' => 2.5,
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi AC Depok',
                    'coverage_area' => 'Radius 6 km area Depok',
                    'default_distance' => 3.0,
                    'delivery_time' => 'Jadwal fleksibel (Pagi/Siang/Sore)',
                ],
            ],
            'bandung' => [
                'city_label' => 'Kota Bandung (Area Dago, Dipatiukur, Pasteur)',
                'badge_city' => 'Bandung',
                'hub_lat' => -6.8920,
                'hub_lon' => 107.6105,
                'laundry' => [
                    'partner_name' => 'Seven Laundry Dago Pasteur',
                    'coverage_area' => 'Area Coblong, Cidadap, Sukasari, Pasteur',
                    'default_distance' => 2.1,
                    'delivery_time' => 'Estimasi jemput: 25-35 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Bandung Parahyangan',
                    'coverage_area' => 'Area Dago, Pasteur, Sukajadi',
                    'default_distance' => 2.8,
                    'delivery_time' => 'Estimasi tiba: 35-45 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Bandung (Segera Hadir)',
                    'coverage_area' => 'Area Dago & Dipatiukur',
                    'default_distance' => 2.0,
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi Dago Bandung',
                    'coverage_area' => 'Area Bandung Utara & Tengah',
                    'default_distance' => 2.5,
                    'delivery_time' => 'Garansi 14 hari pengerjaan',
                ],
            ],
            'yogyakarta' => [
                'city_label' => 'Yogyakarta & Sleman (Area UGM, UNY, Seturan)',
                'badge_city' => 'Yogyakarta',
                'hub_lat' => -7.7713,
                'hub_lon' => 110.3770,
                'laundry' => [
                    'partner_name' => 'Seven Laundry Pogung Sleman',
                    'coverage_area' => 'Area Pogung, Seturan, Condongcatur, Gejayan',
                    'default_distance' => 1.5,
                    'delivery_time' => 'Estimasi jemput: 20-30 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Jogja Istimewa',
                    'coverage_area' => 'Area Sleman & Kota Yogyakarta',
                    'default_distance' => 2.4,
                    'delivery_time' => 'Estimasi tiba: 30 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen UGM (Segera Hadir)',
                    'coverage_area' => 'Area Kampus UGM & UNY',
                    'default_distance' => 1.8,
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi AC Sleman-Jogja',
                    'coverage_area' => 'Radius 5 km Jogja & Sleman',
                    'default_distance' => 3.1,
                    'delivery_time' => 'Teknisi bersertifikat resmi',
                ],
            ],
            'surabaya' => [
                'city_label' => 'Kota Surabaya (Area UNAIR, ITS, Gubeng)',
                'badge_city' => 'Surabaya',
                'hub_lat' => -7.2655,
                'hub_lon' => 112.7521,
                'laundry' => [
                    'partner_name' => 'Seven Laundry Gubeng Surabaya',
                    'coverage_area' => 'Area Gubeng, Mulyorejo, Sukolilo',
                    'default_distance' => 2.0,
                    'delivery_time' => 'Estimasi jemput: 30 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Surabaya Kilap',
                    'coverage_area' => 'Area Surabaya Timur & Selatan',
                    'default_distance' => 2.7,
                    'delivery_time' => 'Estimasi tiba: 35 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Surabaya (Segera Hadir)',
                    'coverage_area' => 'Area UNAIR & ITS',
                    'default_distance' => 2.2,
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care Teknisi AC Surabaya',
                    'coverage_area' => 'Area Surabaya Timur & Pusat',
                    'default_distance' => 3.3,
                    'delivery_time' => 'Teknisi spesialis kos',
                ],
            ],
            'malang' => [
                'city_label' => 'Kota Malang (Area Kampus UB, UM, Lowokwaru)',
                'badge_city' => 'Malang',
                'hub_lat' => -7.9525,
                'hub_lon' => 112.6135,
                'laundry' => [
                    'partner_name' => 'Seven Laundry Soekarno-Hatta Malang',
                    'coverage_area' => 'Area Lowokwaru, Suhat, Klojen',
                    'default_distance' => 1.7,
                    'delivery_time' => 'Estimasi jemput: 25 menit',
                ],
                'cleaning' => [
                    'partner_name' => 'Seven Clean Malang Sejuk',
                    'coverage_area' => 'Area Lowokwaru & Sumbersari',
                    'default_distance' => 2.1,
                    'delivery_time' => 'Estimasi tiba: 30 menit',
                ],
                'catering' => [
                    'partner_name' => 'Seven Kitchen Malang (Segera Hadir)',
                    'coverage_area' => 'Area Kampus UB & UM',
                    'default_distance' => 1.9,
                    'delivery_time' => 'Coming Soon',
                ],
                'maintenance' => [
                    'partner_name' => 'Seven Care AC & Listrik Malang',
                    'coverage_area' => 'Radius 5 km Kota Malang',
                    'default_distance' => 2.8,
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

        // 3. Perhitungan Jarak Kos ke Mitra & Batas Jangkauan Maksimal 5 KM
        $forcedDistance = request('jarak') ? (float) request('jarak') : (request('simulasi_jarak') ? (float) request('simulasi_jarak') : null);

        $calculatedDistance = null;
        if ($activeBooking && $activeBooking->kost && $activeBooking->kost->latitude && $activeBooking->kost->longitude && isset($currentRegional['hub_lat'])) {
            $calculatedDistance = $this->calculateDistance(
                (float) $activeBooking->kost->latitude,
                (float) $activeBooking->kost->longitude,
                (float) $currentRegional['hub_lat'],
                (float) $currentRegional['hub_lon']
            );
        }

        $maxDistance = (float) AppSetting::get('service_max_distance_km', 5.0);
        $hasOutOfRangeService = false;
        foreach (['laundry', 'cleaning', 'catering', 'maintenance'] as $serviceKey) {
            $baseDistance = $currentRegional[$serviceKey]['default_distance'] ?? 2.0;
            $dist = $forcedDistance ?? ($calculatedDistance ?? $baseDistance);
            $isInRange = $dist <= $maxDistance;

            if (! $isInRange) {
                $hasOutOfRangeService = true;
            }

            $currentRegional[$serviceKey]['distance_km'] = $dist;
            $currentRegional[$serviceKey]['is_in_range'] = $isInRange;
            if ($isInRange) {
                $currentRegional[$serviceKey]['distance_approx'] = "{$dist} km dari kos Anda";
            } else {
                $currentRegional[$serviceKey]['distance_approx'] = "{$dist} km (Di luar jangkauan > {$maxDistance} km)";
            }
        }

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
                    'Layanan antar-jemput langsung ke pintu kamar kos',
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

        foreach ($services as &$svc) {
            $cat = $svc['category'];
            $partner = $currentRegional[$cat] ?? null;
            $svc['local_partner'] = $partner;
            $svc['distance_km'] = $partner['distance_km'] ?? 2.0;
            $svc['is_in_range'] = $partner['is_in_range'] ?? true;
        }
        unset($svc);

        $serviceOrders = collect();
        if ($user) {
            $serviceOrders = Transaction::where('user_id', $user->id)
                ->where('type', 'service')
                ->latest()
                ->get();
        }

        $deliveryConfig = [
            'base_fee' => (int) AppSetting::get('service_delivery_base_fee', 5000),
            'base_km' => (float) AppSetting::get('service_delivery_base_km', 2.0),
            'per_km' => (int) AppSetting::get('service_delivery_per_km', 2000),
            'max_km' => (float) AppSetting::get('service_max_distance_km', 5.0),
        ];

        $isXenditActive = (bool) AppSetting::get('payment_xendit_enabled', false);

        return view('layanan', compact(
            'services',
            'banners',
            'activeBookings',
            'activeBooking',
            'hasActiveBooking',
            'hasOutOfRangeService',
            'tenantRegionName',
            'tenantKostName',
            'tenantRoomName',
            'tenantAddress',
            'tenantOccupantName',
            'tenantOccupantPhone',
            'regionalPartners',
            'currentRegional',
            'cityKey',
            'selectedCity',
            'serviceOrders',
            'deliveryConfig',
            'isXenditActive'
        ));
    }

    /**
     * Hitung jarak Haversine antara dua koordinat geografis dalam satuan kilometer.
     */
    private function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 1);
    }

    /**
     * Hitung ongkos antar-jemput mitra berdasarkan konfigurasi tarif platform dari Superadmin:
     * - Jarak <= Radius Dasar (default 2.0 km): Dikenakan Tarif Dasar (default Rp 5.000)
     * - Jarak > Radius Dasar: Tarif Dasar + (selisih km * tarif per km berikutnya).
     */
    public function calculateDeliveryFee(float $distanceKm): int
    {
        $baseFee = (int) AppSetting::get('service_delivery_base_fee', 5000);
        $baseKm = (float) AppSetting::get('service_delivery_base_km', 2.0);
        $perKm = (int) AppSetting::get('service_delivery_per_km', 2000);

        if ($distanceKm <= $baseKm) {
            return $baseFee;
        }

        $extraKm = ceil($distanceKm - $baseKm);

        return $baseFee + (int) ($extraKm * $perKm);
    }

    /**
     * Proses pemesanan paket layanan kamar kos dengan pilihan Payment Gateway (Xendit / Saldo Dompet).
     */
    public function order(Request $request, XenditService $xenditService): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user || ! $user->hasRole('penghuni')) {
            abort(403, 'Hanya akun penghuni kos yang dapat memesan layanan kebutuhan kamar.');
        }

        $validated = $request->validate([
            'service_id' => 'required|string|in:laundry,cleaning,catering,maintenance',
            'package_name' => 'required|string|max:150',
            'package_price' => 'required|numeric|min:1000',
            'package_unit' => 'nullable|string|max:50',
            'quantity' => 'required|integer|min:1|max:100',
            'kost_address' => 'required|string|max:255',
            'room_number' => 'required|string|max:100',
            'occupant_name' => 'required|string|max:150',
            'occupant_phone' => 'required|string|max:30',
            'order_date' => 'required|date|after_or_equal:today',
            'order_time' => 'required|string|max:20',
            'order_notes' => 'nullable|string|max:500',
            'partner_name' => 'nullable|string|max:150',
            'payment_method' => 'required|string|in:xendit,wallet',
        ], [
            'service_id.required' => 'Pilih jenis layanan yang ingin dipesan.',
            'package_name.required' => 'Pilih paket layanan yang ingin dipesan.',
            'package_price.required' => 'Harga paket tidak valid.',
            'quantity.min' => 'Jumlah pesanan minimal 1 unit.',
            'kost_address.required' => 'Nama kos dan alamat lengkap wajib diisi.',
            'room_number.required' => 'Nomor kamar kos wajib diisi.',
            'occupant_name.required' => 'Nama penghuni wajib diisi.',
            'occupant_phone.required' => 'Nomor WhatsApp penghuni wajib diisi.',
            'order_date.required' => 'Tentukan tanggal kunjungan layanan.',
            'order_date.after_or_equal' => 'Tanggal layanan tidak boleh di masa lampau.',
            'payment_method.required' => 'Pilih metode pembayaran (Xendit atau Saldo Dompet).',
        ]);

        $serviceTitles = [
            'laundry' => 'Seven Laundry Kos',
            'cleaning' => 'Seven Clean Kamar & Toilet',
            'catering' => 'Seven Katering Harian',
            'maintenance' => 'Seven Care Teknisi & AC',
        ];
        $serviceTitle = $serviceTitles[$validated['service_id']] ?? 'Layanan Mitra Kos';

        $unitPrice = (float) $validated['package_price'];
        $qty = (int) $validated['quantity'];
        $subtotal = $unitPrice * $qty;
        $unitLabel = $validated['package_unit'] ?: 'unit';

        $distanceKm = (float) $request->input('distance_km', 1.4);
        $deliveryFee = $this->calculateDeliveryFee($distanceKm);
        $totalAmount = $subtotal + $deliveryFee;

        $refId = 'SRV-'.strtoupper(Str::random(10));

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'reference_id' => $refId,
            'type' => 'service',
            'item_id' => $validated['service_id'],
            'title' => "{$serviceTitle}: {$validated['package_name']} ({$qty} {$unitLabel})",
            'amount' => $totalAmount,
            'payment_channel' => $validated['payment_method'] === 'wallet' ? 'wallet' : 'all',
            'status' => 'pending',
            'payload' => [
                'service_id' => $validated['service_id'],
                'service_title' => $serviceTitle,
                'package_name' => $validated['package_name'],
                'package_price' => $unitPrice,
                'package_unit' => $unitLabel,
                'quantity' => $qty,
                'subtotal' => $subtotal,
                'distance_km' => round($distanceKm, 1),
                'delivery_fee' => $deliveryFee,
                'total_amount' => $totalAmount,
                'partner_name' => $validated['partner_name'] ?? 'Mitra Resmi SevenKost',
                'kost_address' => $validated['kost_address'],
                'room_number' => $validated['room_number'],
                'occupant_name' => $validated['occupant_name'],
                'occupant_phone' => $validated['occupant_phone'],
                'order_date' => $validated['order_date'],
                'order_time' => $validated['order_time'],
                'order_notes' => $validated['order_notes'] ?? null,
            ],
        ]);

        // Skenario 1: Bayar langsung via Saldo Dompet Digital
        if ($validated['payment_method'] === 'wallet') {
            if (! $user->hasSufficientBalance($totalAmount)) {
                $transaction->delete();
                $formattedNeed = 'Rp '.number_format($totalAmount, 0, ',', '.');
                $formattedHave = 'Rp '.number_format((float) $user->wallet_balance, 0, ',', '.');

                return back()->with('error', "Saldo dompet tidak mencukupi (Saldo Anda: {$formattedHave}, Dibutuhkan: {$formattedNeed}). Silakan gunakan Xendit Payment Gateway atau Top Up saldo terlebih dahulu.");
            }

            $user->debitWallet(
                $totalAmount,
                $refId,
                'payment',
                "Pembayaran {$transaction->title}",
                'Saldo Dompet SevenKost'
            );

            $transaction->update([
                'status' => 'paid',
                'paid_at' => now(),
            ]);

            return redirect()->route('tenant.services.payment-success', $transaction->reference_id)
                ->with('success', 'Pembayaran pesanan layanan berhasil menggunakan Saldo Dompet!');
        }

        // Skenario 2: Bayar via Gateway Online (Xendit) atau Transfer Bank & QRIS Manual
        $isXenditEnabled = (bool) AppSetting::get('payment_xendit_enabled', false);

        if ($validated['payment_method'] === 'xendit' && $isXenditEnabled) {
            try {
                $xenditService->createInvoice($transaction, [
                    'name' => $validated['occupant_name'],
                    'email' => $user->email,
                    'phone' => $validated['occupant_phone'],
                ]);
                $transaction->refresh();
            } catch (\Throwable $e) {
                Log::warning('Xendit invoice creation skipped or failed: '.$e->getMessage());
            }
        } else {
            $transaction->update(['payment_channel' => 'manual']);
        }

        return redirect()->route('tenant.services.payment', $transaction->reference_id);
    }

    /**
     * Halaman checkout invoice pembayaran layanan SevenKost (Custom UI Pembayaran Mandiri).
     */
    public function paymentCheckout(string $referenceId, XenditService $xenditService): View|RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $isXenditEnabled = (bool) AppSetting::get('payment_xendit_enabled', false);
        if ($isXenditEnabled) {
            try {
                $xenditService->checkInvoiceStatus($transaction);
            } catch (\Throwable $e) {
                // Ignore API status hiccups
            }
        }

        if ($transaction->isPaid()) {
            return redirect()->route('tenant.services.payment-success', $referenceId);
        }

        $paymentSettings = [
            'xendit_enabled' => $isXenditEnabled,
            'manual_enabled' => (bool) AppSetting::get('payment_manual_enabled', true),
            'qr_image' => AppSetting::get('payment_manual_qr_image'),
            'banks' => AppSetting::get('payment_manual_banks', [
                ['bank_name' => 'BCA', 'account_number' => '8730829102', 'account_holder' => 'PT Seven Kost Indonesia', 'is_active' => true],
                ['bank_name' => 'Bank Mandiri', 'account_number' => '1370019283741', 'account_holder' => 'PT Seven Kost Indonesia', 'is_active' => true],
            ]),
            'instructions' => AppSetting::get('payment_manual_instructions', 'Silakan lakukan transfer tepat sesuai total nominal. Setelah berhasil, unggah foto bukti transfer di bawah ini.'),
        ];

        return view('tenant.services.checkout', compact('transaction', 'paymentSettings'));
    }

    /**
     * Unggah foto/gambar bukti transfer manual oleh penghuni kos.
     */
    public function uploadProof(Request $request, string $referenceId): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'proof_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'sender_name' => 'nullable|string|max:150',
            'sender_bank' => 'nullable|string|max:100',
        ], [
            'proof_image.required' => 'Pilih berkas foto bukti transfer Anda.',
            'proof_image.image' => 'Berkas harus berupa gambar (JPG, PNG, atau WEBP).',
            'proof_image.max' => 'Ukuran berkas bukti transfer maksimal 5 MB.',
        ]);

        $path = $request->file('proof_image')->store('proofs/payments', 'public');

        $payload = $transaction->payload ?? [];
        $payload['payment_proof_image'] = $path;
        $payload['payment_proof_uploaded_at'] = now()->toDateTimeString();
        $payload['payment_sender_name'] = $request->input('sender_name');
        $payload['payment_sender_bank'] = $request->input('sender_bank');
        $payload['payment_confirmation_status'] = 'awaiting_confirmation';

        $transaction->update([
            'payload' => $payload,
        ]);

        return back()->with('success', 'Foto bukti pembayaran berhasil diunggah! Mohon tunggu konfirmasi verifikasi dari tim admin SevenKost.');
    }

    /**
     * Halaman konfirmasi sukses pemesanan dan pelunasan layanan.
     */
    public function paymentSuccess(string $referenceId, XenditService $xenditService): View
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->checkInvoiceStatus($transaction);

        return $this->orderTracking($referenceId);
    }

    /**
     * Halaman Lacak Pesanan Langsung (Live Order Tracking ala ShopeeFood/GrabFood).
     */
    public function orderTracking(string $referenceId): View
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $serviceId = $payload['service_id'] ?? 'laundry';
        $driver = $this->getDriverInfo($serviceId, $referenceId);

        // Cek apakah jadwal kedatangan mitra telah terlewati
        $isOverdue = false;
        $minutesOverdue = 0;
        if (! empty($payload['order_date']) && ! empty($payload['order_time'])) {
            try {
                $scheduledAt = Carbon::parse($payload['order_date'].' '.$payload['order_time']);
                if (now()->greaterThan($scheduledAt) && in_array($transaction->status, ['paid', 'pending'])) {
                    $isOverdue = true;
                    $minutesOverdue = (int) now()->diffInMinutes($scheduledAt);
                }
            } catch (\Throwable $e) {
                // Abaikan kesalahan parsing tanggal
            }
        }

        return view('tenant.services.tracking', compact('transaction', 'payload', 'driver', 'isOverdue', 'minutesOverdue'));
    }

    /**
     * Jadwalkan ulang (reschedule) waktu kedatangan mitra layanan.
     */
    public function reschedule(Request $request, string $referenceId): RedirectResponse
    {
        $request->validate([
            'order_date' => ['required', 'date', 'after_or_equal:today'],
            'order_time' => ['required', 'string', 'max:20'],
        ]);

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $oldDate = $payload['order_date'] ?? '-';
        $oldTime = $payload['order_time'] ?? '-';

        $payload['order_date'] = $request->input('order_date');
        $payload['order_time'] = $request->input('order_time');
        $payload['rescheduled_at'] = now()->toDateTimeString();
        $payload['reschedule_history'][] = [
            'from_date' => $oldDate,
            'from_time' => $oldTime,
            'to_date' => $payload['order_date'],
            'to_time' => $payload['order_time'],
            'at' => now()->toDateTimeString(),
        ];

        $transaction->update(['payload' => $payload]);

        return redirect()->route('tenant.services.tracking', $referenceId)
            ->with('success', "Jadwal kedatangan mitra berhasil diperbarui menjadi {$payload['order_date']} pukul {$payload['order_time']} WIB.");
    }

    /**
     * Batalkan pesanan layanan dan kembalikan dana (refund 100%) ke saldo dompet penghuni jika mitra terlambat / melewati jadwal.
     */
    public function cancelRefund(string $referenceId): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $isAlreadyCancelled = ($payload['service_order_status'] ?? null) === 'cancelled';

        if ($isAlreadyCancelled) {
            return back()->with('error', 'Pesanan ini sudah dibatalkan sebelumnya.');
        }

        $amount = (float) $transaction->amount;
        $refundRefId = 'REF-'.strtoupper(Str::random(10));

        // Update status transaksi (enum database: pending, paid, expired, failed)
        $payload['service_order_status'] = 'cancelled';
        $payload['cancelled_at'] = now()->toDateTimeString();
        $payload['cancel_reason'] = 'Mitra terlambat melewati jadwal estimasi kunjungan kamar kos.';
        $payload['refund_amount'] = $amount;
        $payload['refund_reference_id'] = $refundRefId;

        $transaction->update([
            'status' => 'failed',
            'payload' => $payload,
        ]);

        // Refund dana 100% ke saldo dompet jika transaksi berbayar
        if ($amount > 0) {
            $user->creditWallet(
                $amount,
                $refundRefId,
                "Pengembalian dana (Refund 100%) pembatalan pesanan {$transaction->title} karena mitra melewati jadwal kedatangan.",
                'wallet_refund',
                "Invoice: #{$referenceId}"
            );
        }

        return redirect()->route('tenant.services.tracking', $referenceId)
            ->with('success', "Pesanan #{$referenceId} berhasil dibatalkan. Dana sebesar Rp ".number_format($amount, 0, ',', '.').' telah dikembalikan 100% ke Saldo Dompet Anda.');
    }

    /**
     * Perbarui catatan untuk mitra pada pesanan layanan.
     */
    public function updateNotes(Request $request, string $referenceId): RedirectResponse
    {
        $request->validate([
            'order_notes' => ['nullable', 'string', 'max:500'],
        ]);

        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $payload = $transaction->payload ?? [];
        $payload['order_notes'] = $request->input('order_notes');
        $transaction->update(['payload' => $payload]);

        return redirect()->route('tenant.services.tracking', $referenceId)
            ->with('success', 'Catatan untuk mitra berhasil diperbarui.');
    }

    /**
     * Data profil mitra kurir / petugas pelaksana untuk live tracker ala ShopeeFood.
     */
    private function getDriverInfo(string $serviceId, string $referenceId): array
    {
        $drivers = [
            'laundry' => [
                'name' => 'Budi Santoso',
                'role' => 'Kurir Antar-Jemput Laundry',
                'rating' => '4.9',
                'reviews_count' => 184,
                'vehicle' => 'Honda Vario 125 • B 4921 TZL',
                'phone' => '081234567890',
                'initials' => 'BS',
                'avatar_color' => 'from-blue-600 to-indigo-600',
                'status_headline' => 'Mitra Sedang Menuju Kos Anda',
                'status_desc' => 'Kurir sedang dalam perjalanan ke lokasi kos Anda untuk menjemput cucian.',
            ],
            'cleaning' => [
                'name' => 'Agus Supriyanto',
                'role' => 'Spesialis Kebersihan Kamar Kos',
                'rating' => '4.95',
                'reviews_count' => 212,
                'vehicle' => 'Yamaha NMAX • B 3810 KLG',
                'phone' => '081298765432',
                'initials' => 'AS',
                'avatar_color' => 'from-emerald-600 to-teal-700',
                'status_headline' => 'Mitra Bersiap Menuju Kamar Kos',
                'status_desc' => 'Spesialis kebersihan sedang menuju kamar Anda dengan perlengkapan dan chemical aman.',
            ],
            'catering' => [
                'name' => 'Rian Hidayat',
                'role' => 'Kurir Katering Cepat Seven',
                'rating' => '4.85',
                'reviews_count' => 96,
                'vehicle' => 'Honda Beat • B 6214 POX',
                'phone' => '081345678901',
                'initials' => 'RH',
                'avatar_color' => 'from-amber-500 to-orange-600',
                'status_headline' => 'Makanan Sedang Diantar ke Kos',
                'status_desc' => 'Paket makanan sedang dibawa kurir dalam kotak termal agar tetap hangat.',
            ],
            'maintenance' => [
                'name' => 'Dedi Suryana',
                'role' => 'Teknisi AC & Listrik Berlisensi',
                'rating' => '4.9',
                'reviews_count' => 140,
                'vehicle' => 'Honda Supra X • B 5129 URE',
                'phone' => '081223344556',
                'initials' => 'DS',
                'avatar_color' => 'from-indigo-600 to-blue-800',
                'status_headline' => 'Teknisi Menuju Lokasi Kos',
                'status_desc' => 'Teknisi sedang meluncur ke kamar Anda untuk inspeksi dan pengerjaan.',
            ],
        ];

        return $drivers[$serviceId] ?? $drivers['laundry'];
    }

    /**
     * Simulasi pelunasan langsung untuk Sandbox & Pengujian di Localhost.
     */
    public function paymentSimulate(string $referenceId, XenditService $xenditService): RedirectResponse
    {
        $transaction = Transaction::where('reference_id', $referenceId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $xenditService->simulatePayment($referenceId);

        return redirect()->route('tenant.services.payment-success', $referenceId)
            ->with('success', 'Pembayaran layanan via Xendit Sandbox berhasil diselesaikan!');
    }
}
