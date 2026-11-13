<?php

namespace Database\Seeders;

use App\Models\MarketplaceItem;
use App\Models\MarketplaceItemImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class MarketplaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Pastikan folder storage marketplace tersedia
        $storageDir = storage_path('app/public/marketplace');
        if (! File::exists($storageDir)) {
            File::makeDirectory($storageDir, 0755, true);
        }

        $frontendImgDir = public_path('images/frontend');
        if (File::exists($frontendImgDir)) {
            foreach (File::files($frontendImgDir) as $file) {
                $dest = $storageDir.'/'.$file->getFilename();
                if (! File::exists($dest)) {
                    File::copy($file->getPathname(), $dest);
                }
            }
        }

        // 2. Akun Penjual Anak Kos / Mitra Pemilik
        if (class_exists(Role::class)) {
            Role::firstOrCreate(['name' => 'penghuni', 'guard_name' => 'web']);
            Role::firstOrCreate(['name' => 'pemilik', 'guard_name' => 'web']);
        }

        $sellerRizky = User::updateOrCreate(
            ['email' => 'rizky.pratama@sevenkos.com'],
            [
                'name' => 'Rizky Pratama',
                'phone' => '081234567891',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (method_exists($sellerRizky, 'syncRoles')) {
            $sellerRizky->syncRoles(['penghuni']);
        }

        $sellerSiti = User::updateOrCreate(
            ['email' => 'siti.aisyah@sevenkos.com'],
            [
                'name' => 'Siti Aisyah',
                'phone' => '081234567892',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (method_exists($sellerSiti, 'syncRoles')) {
            $sellerSiti->syncRoles(['penghuni']);
        }

        $sellerDimas = User::updateOrCreate(
            ['email' => 'dimas.saputra@sevenkos.com'],
            [
                'name' => 'Dimas Saputra',
                'phone' => '081234567893',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (method_exists($sellerDimas, 'syncRoles')) {
            $sellerDimas->syncRoles(['penghuni']);
        }

        $sellerAndi = User::updateOrCreate(
            ['email' => 'andi.wijaya@sevenkos.com'],
            [
                'name' => 'Andi Wijaya',
                'phone' => '081234567894',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        if (method_exists($sellerAndi, 'syncRoles')) {
            $sellerAndi->syncRoles(['pemilik']);
        }

        // 3. Data Barang Kos Sesuai Desain Mock-up
        $itemsData = [
            [
                'user' => $sellerRizky,
                'title' => 'Kasur Busa 120x200 Premium',
                'category' => 'kasur',
                'price' => 450000,
                'condition' => 'bekas_seperti_baru',
                'meetup_location' => 'Lobby Kost / Area Parkir Kampus UI Depok',
                'city' => 'Depok',
                'description' => 'Kasur busa ukuran 120x200 cm, kondisi masih sangat bagus, empuk dan tidak kempes. Cocok untuk kamar kos atau kontrakan mahasiswa. COD langsung cek sepuasnya.',
                'is_featured' => true,
                'image' => 'marketplace-hd.jpg',
            ],
            [
                'user' => $sellerSiti,
                'title' => 'Kipas Angin Cosmos 16 Inch Stand Fan',
                'category' => 'kipas',
                'price' => 250000,
                'condition' => 'bekas_baik',
                'meetup_location' => 'Depan Minimarket Kampus UIN Ciputat',
                'city' => 'Jakarta Selatan',
                'description' => 'Kipas angin berdiri 16 inch merek Cosmos, 3 level kecepatan putaran kencang, osilasi normal. Dijual karena sudah lulus dan mau pulang kampung. Pembayaran tunai/COD saat temu.',
                'is_featured' => false,
                'image' => 'marketplace-items.png',
            ],
            [
                'user' => $sellerDimas,
                'title' => 'Rak Besi Serbaguna 4 Susun Minimalis',
                'category' => 'rak',
                'price' => 180000,
                'condition' => 'bekas_baik',
                'meetup_location' => 'Lobby Kost Green Rawamangun dekat UNJ',
                'city' => 'Jakarta Timur',
                'description' => 'Rak besi putih 4 susun kuat untuk tempat buku, pakaian, perlengkapan mandi, dan helm kos. Bisa dibongkar pasang mudah dibawa motor. Sistem COD langsung bayar di tempat.',
                'is_featured' => false,
                'image' => 'kost-1.png',
            ],
            [
                'user' => $sellerAndi,
                'title' => 'Rice Cooker Miyako 1.8L Magic Com',
                'category' => 'elektronik',
                'price' => 300000,
                'condition' => 'bekas_seperti_baru',
                'meetup_location' => 'Gerbang Utama ITB Ganesha Bandung',
                'city' => 'Bandung',
                'description' => 'Rice cooker Miyako kapasitas 1.8 liter, fungsi menanak dan menghangatkan nasi sangat normal, panci anti lengket tanpa gores. Lengkap dengan centong dan kabel colokan.',
                'is_featured' => false,
                'image' => 'kost-2.png',
            ],
            [
                'user' => $sellerRizky,
                'title' => 'Meja Belajar Lesehan Lipat Kayu Kokoh',
                'category' => 'perabot',
                'price' => 120000,
                'condition' => 'bekas_baik',
                'meetup_location' => 'Area Parkir Perpustakaan UGM Yogyakarta',
                'city' => 'Yogyakarta',
                'description' => 'Meja belajar lipat kayu solid tebal, cocok untuk belajar di kasur/lantai kamar kos. Kaki besi kokoh tidak goyang. Langsung cek kondisi saat COD.',
                'is_featured' => false,
                'image' => 'kost-3.png',
            ],
            [
                'user' => $sellerSiti,
                'title' => 'Dispenser Air Minum Miyako Hot & Normal',
                'category' => 'elektronik',
                'price' => 190000,
                'condition' => 'bekas_baik',
                'meetup_location' => 'Pos Satpam Kampus B UNAIR Surabaya',
                'city' => 'Surabaya',
                'description' => 'Dispenser air galon atas dua kran (panas & normal), pemanas air cepat untuk seduh mi instan dan kopi anak kos. Bersih terawat, COD sekitaran kampus.',
                'is_featured' => false,
                'image' => 'kost-4.png',
            ],
        ];

        foreach ($itemsData as $data) {
            $item = MarketplaceItem::updateOrCreate(
                [
                    'user_id' => $data['user']->id,
                    'title' => $data['title'],
                ],
                [
                    'slug' => Str::slug($data['title']),
                    'category' => $data['category'],
                    'price' => $data['price'],
                    'condition' => $data['condition'],
                    'meetup_location' => $data['meetup_location'],
                    'city' => $data['city'],
                    'description' => $data['description'],
                    'is_featured' => $data['is_featured'],
                    'status' => 'active',
                    'views_count' => rand(15, 140),
                ]
            );

            // Tambahkan gambar utama
            MarketplaceItemImage::updateOrCreate(
                [
                    'marketplace_item_id' => $item->id,
                    'order' => 1,
                ],
                [
                    'image_path' => 'marketplace/'.$data['image'],
                    'is_primary' => true,
                ]
            );
        }
    }
}
