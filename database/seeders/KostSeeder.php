<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\Kost;
use App\Models\KostImage;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class KostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Ambil akun Pemilik Kos
        $pemilikJakarta = User::where('email', 'pemilik@sevenkos.com')->first() ?? User::first();
        $pemilikYogya   = User::where('email', 'pemilik.ratna@sevenkos.com')->first() ?? $pemilikJakarta;
        $pemilikDaerah  = User::where('email', 'pemilik.hendra@sevenkos.com')->first() ?? $pemilikJakarta;

        if (!$pemilikJakarta) {
            $this->command->error('Tidak ditemukan user pemilik di database. Jalankan RolePermissionSeeder terlebih dahulu.');
            return;
        }

        // 2. Siapkan file gambar di storage/app/public/kosts jika belum ada
        $storageKostsDir = storage_path('app/public/kosts');
        if (!File::exists($storageKostsDir)) {
            File::makeDirectory($storageKostsDir, 0755, true);
        }

        $frontendImgDir = public_path('images/frontend');
        if (File::exists($frontendImgDir)) {
            foreach (File::files($frontendImgDir) as $file) {
                $dest = $storageKostsDir . '/' . $file->getFilename();
                if (!File::exists($dest)) {
                    File::copy($file->getPathname(), $dest);
                }
            }
        }

        // Daftar file foto untuk galeri
        $availableImageFiles = [
            'kosts/kost-1-hd.jpg',
            'kosts/kost-2-hd.jpg',
            'kosts/kost-3-hd.jpg',
            'kosts/kost-4-hd.jpg',
            'kosts/cta-kost-room.jpg',
            'kosts/banner-bandung-hd.jpg',
            'kosts/banner-jakarta-hd.jpg',
            'kosts/banner-yogyakarta-hd.jpg',
        ];

        // 3. Pastikan minimal data Provinsi & Kota tersedia
        $dki = Province::firstOrCreate(['id' => '31'], ['name' => 'Dki Jakarta']);
        $jabar = Province::firstOrCreate(['id' => '32'], ['name' => 'Jawa Barat']);
        $yogya = Province::firstOrCreate(['id' => '34'], ['name' => 'Di Yogyakarta']);
        $jatim = Province::firstOrCreate(['id' => '35'], ['name' => 'Jawa Timur']);

        $jaksel = Regency::firstOrCreate(['id' => '3171'], ['province_id' => $dki->id, 'name' => 'Kota Jakarta Selatan', 'type' => 'Kota']);
        $jakbar = Regency::firstOrCreate(['id' => '3173'], ['province_id' => $dki->id, 'name' => 'Kota Jakarta Barat', 'type' => 'Kota']);
        $bandung = Regency::firstOrCreate(['id' => '3273'], ['province_id' => $jabar->id, 'name' => 'Kota Bandung', 'type' => 'Kota']);
        $depok = Regency::firstOrCreate(['id' => '3276'], ['province_id' => $jabar->id, 'name' => 'Kota Depok', 'type' => 'Kota']);
        $sleman = Regency::firstOrCreate(['id' => '3404'], ['province_id' => $yogya->id, 'name' => 'Kabupaten Sleman', 'type' => 'Kabupaten']);
        $surabaya = Regency::firstOrCreate(['id' => '3578'], ['province_id' => $jatim->id, 'name' => 'Kota Surabaya', 'type' => 'Kota']);
        $malang = Regency::firstOrCreate(['id' => '3573'], ['province_id' => $jatim->id, 'name' => 'Kota Malang', 'type' => 'Kota']);

        $allFacilities = Facility::all();

        // 4. Data Kos & Ragam Tipe Kamar
        $kostList = [
            [
                'user_id' => $pemilikJakarta->id,
                'name' => 'Seven Kost Melati Residence Exclusive',
                'province_id' => $dki->id,
                'regency_id' => $jaksel->id,
                'type' => 'campur',
                'rent_type' => 'bulanan',
                'address' => 'Jl. RS Fatmawati No. 45, Cilandak, Jakarta Selatan (Dekat MRT)',
                'description' => "Seven Kost Melati Residence Exclusive menawarkan hunian kos berstandar hotel premium di lokasi emas Jakarta Selatan.\n\nHanya 3 menit jalan kaki ke Stasiun MRT Fatmawati dan 5 menit ke Cilandak Town Square (Citos). Bangunan baru dengan sirkulasi udara bersih, keamanan 24 jam CCTV, dan fasilitas kartu akses pintar.",
                'rules' => "1. Jam malam bertamu maksimal pukul 23.00 WIB.\n2. Dilarang merokok di dalam kamar tidur.\n3. Dilarang membawa hewan peliharaan.\n4. Tamu menginap wajib lapor ke pengelola kos.",
                'latitude' => -6.2925,
                'longitude' => 106.7972,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'images' => ['kosts/kost-1-hd.jpg', 'kosts/cta-kost-room.jpg', 'kosts/kost-2-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Tipe Standard Single',
                        'price' => 1600000,
                        'total_rooms' => 6,
                        'available_rooms' => 2,
                        'room_size' => '3x3 m',
                    ],
                    [
                        'name' => 'Tipe Deluxe Springbed AC',
                        'price' => 1950000,
                        'total_rooms' => 8,
                        'available_rooms' => 3,
                        'room_size' => '3x4 m',
                    ],
                    [
                        'name' => 'Tipe Suite VIP Balkon Luar',
                        'price' => 2500000,
                        'total_rooms' => 4,
                        'available_rooms' => 1,
                        'room_size' => '4x5 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikYogya->id,
                'name' => 'Griya Jasmine Putri Dekat Kampus UGM',
                'province_id' => $yogya->id,
                'regency_id' => $sleman->id,
                'type' => 'putri',
                'rent_type' => 'bulanan',
                'address' => 'Jl. Kaliurang KM 5.2, Gang Pandega Marta No. 18, Sleman, Yogyakarta',
                'description' => "Hunian kos khusus mahasiswi dan karyawati dengan lingkungan tenang, bersih, serta asri bernuansa taman tropis.\n\nSangat strategis, hanya 5 menit ke Fakultas Teknik, Kedokteran, dan Pascasarjana UGM. Dekat aneka kuliner legendaris Jakal, minimarket, serta laundry.",
                'rules' => "1. Khusus putri, tamu pria hanya diperkenankan di ruang tamu umum lantai 1.\n2. Gerbang utama ditutup pukul 22.30 WIB.\n3. Menjaga ketertiban dan kebersihan dapur bersama.",
                'latitude' => -7.7600,
                'longitude' => 110.3750,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'images' => ['kosts/kost-2-hd.jpg', 'kosts/banner-yogyakarta-hd.jpg', 'kosts/kost-1-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Tipe Single Hemat',
                        'price' => 950000,
                        'total_rooms' => 6,
                        'available_rooms' => 2,
                        'room_size' => '3x3 m',
                    ],
                    [
                        'name' => 'Tipe Single Balkon AC',
                        'price' => 1250000,
                        'total_rooms' => 10,
                        'available_rooms' => 4,
                        'room_size' => '3x3.5 m',
                    ],
                    [
                        'name' => 'Tipe Mezzanine Mahasiswi Exclusive',
                        'price' => 1650000,
                        'total_rooms' => 4,
                        'available_rooms' => 1,
                        'room_size' => '3.5x4 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikDaerah->id,
                'name' => 'D’Executive Paviliun Pasteur Bandung',
                'province_id' => $jabar->id,
                'regency_id' => $bandung->id,
                'type' => 'putra',
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dr. Djunjunan No. 120, Sukajadi, Kota Bandung',
                'description' => "Kost eksekutif modern khusus pria dengan kemudahan akses langsung ke Gerbang Tol Pasteur Bandung.\n\nFasilitas lengkap dengan area parkir mobil luas, air panas water heater 24 jam, WiFi fiber 100 Mbps, serta kamar berukuran lapang.",
                'rules' => "1. Dilarang membawa minuman keras dan obat terlarang.\n2. Tamu menginap maksimal 2 malam dengan konfirmasi pengelola.\n3. Parkir rapi pada slot masing-masing.",
                'latitude' => -6.8910,
                'longitude' => 107.5850,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'images' => ['kosts/kost-3-hd.jpg', 'kosts/banner-bandung-hd.jpg', 'kosts/kost-4-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Paviliun Single Standard',
                        'price' => 1500000,
                        'total_rooms' => 5,
                        'available_rooms' => 2,
                        'room_size' => '3x4 m',
                    ],
                    [
                        'name' => 'Paviliun Deluxe King Bed',
                        'price' => 1850000,
                        'total_rooms' => 6,
                        'available_rooms' => 3,
                        'room_size' => '4x4 m',
                    ],
                    [
                        'name' => 'Paviliun VIP Parkir Mobil',
                        'price' => 2400000,
                        'total_rooms' => 3,
                        'available_rooms' => 1,
                        'room_size' => '4x5 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikJakarta->id,
                'name' => 'Seven Kost D’Green UI Kampus Depok',
                'province_id' => $jabar->id,
                'regency_id' => $depok->id,
                'type' => 'campur',
                'rent_type' => 'bulanan',
                'address' => 'Jl. K.H. M. Usman, Kukusan, Beji, Kota Depok (Dekat Pintu Tol Kukusan & UI)',
                'description' => "Pilihan kos terfavorit mahasiswa Universitas Indonesia (UI) dan Politeknik Negeri Jakarta (PNJ).\n\nDilengkapi area komunal rooftop nyaman, coworking space ber-AC, serta keamanan terintegrasi CCTV.",
                'rules' => "1. Menjaga ketenangan jam belajar malam (20.00 - 23.00).\n2. Membuang sampah pada tempat pemilahan daur ulang.\n3. Bebas asap rokok di kamar.",
                'latitude' => -6.3680,
                'longitude' => 106.8240,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'images' => ['kosts/kost-4-hd.jpg', 'kosts/kost-1-hd.jpg', 'kosts/banner-jakarta-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Smart Studio AC',
                        'price' => 1400000,
                        'total_rooms' => 8,
                        'available_rooms' => 3,
                        'room_size' => '3x3.5 m',
                    ],
                    [
                        'name' => 'Studio Plus Meja Belajar Lebar',
                        'price' => 1750000,
                        'total_rooms' => 8,
                        'available_rooms' => 2,
                        'room_size' => '3.5x4 m',
                    ],
                    [
                        'name' => 'Loft Room Modern Mezzanine',
                        'price' => 2200000,
                        'total_rooms' => 4,
                        'available_rooms' => 2,
                        'room_size' => '4x4.5 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikDaerah->id,
                'name' => 'Kost Asri Mawar Melati UNAIR Surabaya',
                'province_id' => $jatim->id,
                'regency_id' => $surabaya->id,
                'type' => 'campur',
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dharmawangsa Barat No. 12, Gubeng, Surabaya',
                'description' => "Lokasi prima di pusat pendidikan dan medis Surabaya. Bersebelahan langsung dengan Kampus B UNAIR dan RSUD Dr. Soetomo.\n\nSangat nyaman bagi dokter muda (koas), tenaga medis, dan mahasiswa yang membutuhkan akses cepat dan mobilitas tinggi.",
                'rules' => "1. Menjaga ketenangan untuk rekan medis yang istirahat shift malam.\n2. Wajib menjaga kebersihan dapur dan kulkas bersama.\n3. Tamu wajib lapor resepsionis/security.",
                'latitude' => -7.2750,
                'longitude' => 112.7560,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => false,
                'images' => ['kosts/cta-kost-room.jpg', 'kosts/kost-3-hd.jpg', 'kosts/kost-2-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Tipe Reguler Non-AC',
                        'price' => 850000,
                        'total_rooms' => 6,
                        'available_rooms' => 2,
                        'room_size' => '3x3 m',
                    ],
                    [
                        'name' => 'Tipe Standar AC Single',
                        'price' => 1350000,
                        'total_rooms' => 8,
                        'available_rooms' => 4,
                        'room_size' => '3x3.5 m',
                    ],
                    [
                        'name' => 'Tipe Deluxe Double Bed (Bisa Berdua)',
                        'price' => 1800000,
                        'total_rooms' => 4,
                        'available_rooms' => 1,
                        'room_size' => '3.5x4.5 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikYogya->id,
                'name' => 'Kost Putri Anugerah Brawijaya Malang',
                'province_id' => $jatim->id,
                'regency_id' => $malang->id,
                'type' => 'putri',
                'rent_type' => 'bulanan',
                'address' => 'Jl. MT Haryono Gang 4 No. 25, Lowokwaru, Kota Malang',
                'description' => "Hunian sejuk dan nyaman di Kota Malang, hanya 400 meter dari Gerbang Utama Universitas Brawijaya (UB) dan UIN Maulana Malik Ibrahim.\n\nBangunan sejuk dengan pencahayaan alami, fasilitas internet kencang untuk perkuliahan daring, serta dapur bersih lengkap.",
                'rules' => "1. Khusus putri/mahasiswi.\n2. Gerbang dikunci pukul 22.00 WIB demi keamanan bersama.\n3. Kebersihan kamar mandi wajib dijaga bersama.",
                'latitude' => -7.9520,
                'longitude' => 112.6140,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => false,
                'images' => ['kosts/kost-2-hd.jpg', 'kosts/kost-4-hd.jpg', 'kosts/kost-1-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Single Room Standar',
                        'price' => 750000,
                        'total_rooms' => 8,
                        'available_rooms' => 3,
                        'room_size' => '3x3 m',
                    ],
                    [
                        'name' => 'Single Deluxe Springbed',
                        'price' => 1100000,
                        'total_rooms' => 10,
                        'available_rooms' => 5,
                        'room_size' => '3x3.5 m',
                    ],
                    [
                        'name' => 'VIP Room Kamar Mandi Dalam',
                        'price' => 1500000,
                        'total_rooms' => 4,
                        'available_rooms' => 2,
                        'room_size' => '3.5x4 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikJakarta->id,
                'name' => 'Kost Putra Kemanggisan Dekat Kampus Binus',
                'province_id' => $dki->id,
                'regency_id' => $jakbar->id,
                'type' => 'putra',
                'rent_type' => 'bulanan',
                'address' => 'Jl. Anggrek Cakra No. 7, Kebon Jeruk / Kemanggisan, Jakarta Barat',
                'description' => "Kost khusus mahasiswa Binus University (Anggrek & Syahdan Campus). Cukup jalan kaki 3 menit ke kelas.\n\nFasilitas internet super stabil, meja belajar luas ergonomis, AC dingin, dan laundry pakaian harian siap antar.",
                'rules' => "1. Dilarang membawa miras atau obat terlarang.\n2. Tamu wanita dilarang masuk area lorong kamar.\n3. Wajib menjaga ketenangan setelah pukul 23.00 WIB.",
                'latitude' => -6.2010,
                'longitude' => 106.7820,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => false,
                'images' => ['kosts/kost-3-hd.jpg', 'kosts/banner-jakarta-hd.jpg', 'kosts/kost-4-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Tipe Mahasiswa Single AC',
                        'price' => 1450000,
                        'total_rooms' => 8,
                        'available_rooms' => 2,
                        'room_size' => '3x3 m',
                    ],
                    [
                        'name' => 'Tipe Deluxe Workstation (Dual Desk)',
                        'price' => 1850000,
                        'total_rooms' => 6,
                        'available_rooms' => 3,
                        'room_size' => '3.5x4 m',
                    ],
                ],
            ],
            [
                'user_id' => $pemilikDaerah->id,
                'name' => 'Seven Kost Panorama Dago Atas Bandung',
                'province_id' => $jabar->id,
                'regency_id' => $bandung->id,
                'type' => 'campur',
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dago Asri No. 28, Coblong, Kota Bandung (Dekat Kampus ITB & Unpad)',
                'description' => "Rasakan kenyamanan sejuknya udara Dago Atas Bandung. Pemandangan perbukitan yang indah dengan konsep hunian kos boutique.\n\nHanya 7 menit berkendara ke Kampus ITB Ganesha dan Simpang Dago.",
                'rules' => "1. Menjaga ketertiban dan toleransi antarpenghuni.\n2. Parkir mobil wajib pada area yang telah dialokasikan.\n3. Kebersihan kamar mandi dan balkon dijaga bersama.",
                'latitude' => -6.8730,
                'longitude' => 107.6180,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
                'images' => ['kosts/banner-bandung-hd.jpg', 'kosts/kost-1-hd.jpg', 'kosts/kost-4-hd.jpg'],
                'rooms' => [
                    [
                        'name' => 'Mountain View AC Standard',
                        'price' => 1750000,
                        'total_rooms' => 6,
                        'available_rooms' => 2,
                        'room_size' => '3.5x4 m',
                    ],
                    [
                        'name' => 'Sky Suite Balkon Pribadi',
                        'price' => 2600000,
                        'total_rooms' => 4,
                        'available_rooms' => 1,
                        'room_size' => '4x5 m',
                    ],
                ],
            ],
        ];

        // 5. Eksekusi Seeding Kos, Kamar-kamar, dan Foto
        foreach ($kostList as $data) {
            $roomsData = $data['rooms'];
            $imagesData = $data['images'];
            unset($data['rooms'], $data['images']);

            // Hitung harga mulai terendah dari kamar yang ada
            $minPrice = min(array_column($roomsData, 'price'));
            $data['price_start'] = $minPrice;
            $data['slug'] = Str::slug($data['name']);

            $kost = Kost::updateOrCreate(
                ['name' => $data['name']],
                $data
            );

            // Buat Kamar-kamar untuk tiap kos
            foreach ($roomsData as $r) {
                Room::updateOrCreate(
                    ['kost_id' => $kost->id, 'name' => $r['name']],
                    [
                        'price' => $r['price'],
                        'total_rooms' => $r['total_rooms'],
                        'available_rooms' => $r['available_rooms'],
                        'room_size' => $r['room_size'],
                    ]
                );
            }

            // Pasang Foto Kos
            if ($kost->images()->count() === 0) {
                foreach ($imagesData as $index => $imgPath) {
                    KostImage::create([
                        'kost_id' => $kost->id,
                        'file_path' => $imgPath,
                        'is_primary' => ($index === 0),
                        'sort_order' => $index,
                    ]);
                }
            }

            // Pasang Fasilitas Lengkap jika belum ada
            if ($kost->facilities()->count() === 0 && $allFacilities->count() > 0) {
                // Pilih 6 - 9 fasilitas acak yang relevan
                $sampleCount = min(8, $allFacilities->count());
                $randomFacIds = $allFacilities->random($sampleCount)->pluck('id');
                $kost->facilities()->sync($randomFacIds);
            }
        }

        $this->command->info('Sukses! ' . count($kostList) . ' Kos lengkap beserta berbagai variasi tipe kamar berhasil di-seed.');
    }
}
