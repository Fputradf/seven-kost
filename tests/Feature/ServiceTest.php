<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RolePermissionSeeder::class,
        ]);
    }

    /**
     * Test bahwa halaman Layanan Mitra Kos dapat diakses dan merender seluruh katalog paket.
     */
    public function test_services_catalog_page_renders_successfully(): void
    {
        $response = $this->get('/layanan');

        $response->assertStatus(200);

        // Header & Judul
        $response->assertSee('Layanan Kebutuhan Kos', false);
        $response->assertSee('Mitra Siap Melayani Hari Ini');

        // Kategori Filter Tab
        $response->assertSee('Semua Layanan');
        $response->assertSee('Laundry Kiloan');
        $response->assertSee('Kebersihan Kamar');
        $response->assertSee('Katering Harian');
        $response->assertSee('Teknisi');
        $response->assertSee('Coming Soon');

        // Katalog Layanan
        $response->assertSee('Seven Laundry Kos');
        $response->assertSee('Seven Clean Kamar');
        $response->assertSee('Seven Katering Harian');
        $response->assertSee('Seven Care Teknisi');

        // Paket & Tombol Pemesanan (Arahkan ke Login untuk Guest)
        $response->assertSee('Laundry Kiloan Reguler');
        $response->assertSee('Deep Clean Kamar Mandi Kos');
        $response->assertSee('Pesan Sekarang');
        $response->assertDontSee('Teruskan ke WhatsApp');

        // Alur & Mitra
        $response->assertSee('Iklan Sponsor');
        $response->assertSee('Bagaimana Cara Pesan Layanan?');
        $response->assertSee('Punya Usaha Laundry');
    }

    /**
     * Test bahwa akun dengan role penghuni dapat mengakses form pemesanan layanan.
     */
    public function test_authenticated_penghuni_can_view_order_modal(): void
    {
        $penghuni = User::role('penghuni')->first();
        if ($penghuni) {
            $response = $this->actingAs($penghuni)->get('/layanan');
            $response->assertStatus(200);
            $response->assertSee('Form Pemesanan Layanan');
            $response->assertSee('Konfirmasi');
        }
    }

    /**
     * Test bahwa pengunjung atau penghuni tanpa kamar aktif melihat panduan jangkauan regional.
     */
    public function test_guest_sees_regional_coverage_guidance_and_city_selector(): void
    {
        $response = $this->get('/layanan');

        $response->assertStatus(200);
        $response->assertSee('Jangkauan Berbasis Lokasi Kos');
        $response->assertSee('Layanan Mitra Khusus Penghuni Kamar Aktif');
        $response->assertSee('Cek Mitra di:');
        $response->assertSee('Cari Kamar Kos');
    }

    /**
     * Test bahwa penghuni dengan kamar aktif otomatis terdeteksi lokasi kos & mitranya.
     */
    public function test_tenant_with_active_booking_sees_detected_room_and_local_partner(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $owner = User::firstOrCreate(
            ['email' => 'owner.service@sevenkos.com'],
            ['name' => 'Owner Service', 'password' => bcrypt('password'), 'phone' => '081234567899']
        );

        $province = Province::first() ?? Province::create(['id' => '32', 'name' => 'Jawa Barat']);
        $regency = Regency::firstOrCreate(
            ['id' => '3273'],
            ['province_id' => $province->id, 'name' => 'Kota Bandung']
        );

        $kost = Kost::firstOrCreate(
            ['slug' => 'kost-bandung-service-test'],
            [
                'name' => 'Seven Kost Dago Asri',
                'user_id' => $owner->id,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'type' => 'campur',
                'price_start' => 1200000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dago Asri No. 10, Bandung',
                'verification_status' => 'verified',
                'is_available' => true,
            ]
        );

        $room = Room::firstOrCreate(
            ['kost_id' => $kost->id, 'name' => 'Kamar Deluxe 101'],
            [
                'price' => 1200000,
                'total_rooms' => 3,
                'available_rooms' => 1,
            ]
        );

        Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'duration_months' => 3,
            'total_amount' => 3600000,
            'status' => 'approved',
            'tenant_name' => $tenant->name,
            'tenant_phone' => '08123456789',
        ]);

        $response = $this->actingAs($tenant)->get('/layanan');

        $response->assertStatus(200);
        $response->assertSee('Kamar Aktif Terverifikasi');
        $response->assertSee('Seven Kost Dago Asri');
        $response->assertSee('Kamar Deluxe 101');
        $response->assertSee('Seven Laundry Dago Pasteur');
    }

    /**
     * Test bahwa penghuni dengan 2 kamar aktif di region berbeda dapat berpindah kamar tujuan layanan.
     */
    public function test_tenant_with_multiple_active_bookings_can_switch_rooms_and_regions(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $owner = User::firstOrCreate(
            ['email' => 'owner.multistay@sevenkos.com'],
            ['name' => 'Owner Multi Stay', 'password' => bcrypt('password'), 'phone' => '081234567888']
        );

        $provJabar = Province::firstOrCreate(['id' => '32'], ['name' => 'Jawa Barat']);
        $regBandung = Regency::firstOrCreate(['id' => '3273'], ['province_id' => '32', 'name' => 'Kota Bandung']);
        $regDepok = Regency::firstOrCreate(['id' => '3276'], ['province_id' => '32', 'name' => 'Kota Depok']);

        // Kost 1: Bandung
        $kostBandung = Kost::firstOrCreate(
            ['slug' => 'kost-multi-bandung'],
            [
                'name' => 'Seven Kost Dago Suite Bandung',
                'user_id' => $owner->id,
                'province_id' => $provJabar->id,
                'regency_id' => $regBandung->id,
                'type' => 'campur',
                'price_start' => 1500000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Dago No. 100, Bandung',
                'verification_status' => 'verified',
                'is_available' => true,
            ]
        );
        $roomBandung = Room::firstOrCreate(['kost_id' => $kostBandung->id, 'name' => 'Deluxe 201'], ['price' => 1500000, 'total_rooms' => 2, 'available_rooms' => 1]);

        $bookingBandung = Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kostBandung->id,
            'room_id' => $roomBandung->id,
            'start_date' => now()->subDays(10)->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'duration_months' => 3,
            'total_amount' => 4500000,
            'status' => 'approved',
            'tenant_name' => 'Anak Pertama Budi',
            'tenant_phone' => '081211112222',
        ]);

        // Kost 2: Depok
        $kostDepok = Kost::firstOrCreate(
            ['slug' => 'kost-multi-depok'],
            [
                'name' => 'Seven Kost Margonda UI Depok',
                'user_id' => $owner->id,
                'province_id' => $provJabar->id,
                'regency_id' => $regDepok->id,
                'type' => 'campur',
                'price_start' => 1300000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Margonda Raya No. 50, Depok',
                'verification_status' => 'verified',
                'is_available' => true,
            ]
        );
        $roomDepok = Room::firstOrCreate(['kost_id' => $kostDepok->id, 'name' => 'Standard 105'], ['price' => 1300000, 'total_rooms' => 2, 'available_rooms' => 1]);

        $bookingDepok = Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kostDepok->id,
            'room_id' => $roomDepok->id,
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addMonths(3)->toDateString(),
            'duration_months' => 3,
            'total_amount' => 3900000,
            'status' => 'active',
            'tenant_name' => 'Anak Kedua Siti',
            'tenant_phone' => '081233334444',
        ]);

        // 1. Akses dengan kamar Depok
        $resDepok = $this->actingAs($tenant)->get(route('services.index', ['kamar_id' => $bookingDepok->id]));
        $resDepok->assertStatus(200);
        $resDepok->assertSee('Seven Kost Margonda UI Depok');
        $resDepok->assertSee('Anak Kedua Siti');
        $resDepok->assertSee('Seven Laundry UI Depok');
        $resDepok->assertSee('Pilih Kamar Tujuan Layanan:');

        // 2. Akses dengan kamar Bandung
        $resBandung = $this->actingAs($tenant)->get(route('services.index', ['kamar_id' => $bookingBandung->id]));
        $resBandung->assertStatus(200);
        $resBandung->assertSee('Seven Kost Dago Suite Bandung');
        $resBandung->assertSee('Anak Pertama Budi');
        $resBandung->assertSee('Seven Laundry Dago Pasteur');
    }
}
