<?php

namespace Tests\Feature;

use App\Models\AppSetting;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RolePermissionSeeder::class,
        ]);

        AppSetting::set('service_delivery_base_fee', 5000, 'services', 'number');
        AppSetting::set('service_delivery_base_km', 2.0, 'services', 'number');
        AppSetting::set('service_delivery_per_km', 2000, 'services', 'number');
        AppSetting::set('service_max_distance_km', 5.0, 'services', 'number');
        AppSetting::set('payment_xendit_enabled', false, 'payment', 'boolean');
        AppSetting::set('payment_manual_enabled', true, 'payment', 'boolean');
    }

    /**
     * Test bahwa halaman Layanan Mitra Kos da pat diakses dan merender seluruh katalog paket.
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
        $response->assertSee('Coming Soon');

        // Katalog Layanan
        $response->assertSee('Seven Laundry Kos');
        $response->assertSee('Seven Clean Kamar');
        $response->assertSee('Seven Katering Harian');
        $response->assertDontSee('Seven Care Teknisi');

        // Paket & Tombol Pemesanan (Arahkan ke Login untuk Guest)
        $response->assertSee('Laundry Kiloan Reguler');
        $response->assertSee('Deep Clean Kamar Mandi Kos');
        $response->assertSee('Pesan Sekarang');
        $response->assertDontSee('Teruskan ke WhatsApp');

        // Alur & Mitra
        $response->assertSee('Iklan Sponsor');
        $response->assertDontSee('Bagaimana Cara Pesan Layanan?');
        $response->assertDontSee('Punya Usaha Laundry');
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
            $response->assertSee('Paket Layanan');
            $response->assertSee('Metode Pembayaran');
            $response->assertSee('Transfer Bank & QRIS', false);
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
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
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
        $response->assertSee('Kamar Aktif');
        $response->assertSee('Seven Kost Dago Asri');
        $response->assertSee('Kamar Deluxe 101');
        $response->assertSee('Seven Laundry Dago Pasteur');
    }

    /**
     * Test bahwa penghuni dengan 2 kamar aktif di region berbeda dapat berpindah kamar tujuan layanan.
     */
    public function test_tenant_with_multiple_active_bookings_can_switch_rooms_and_regions(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
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

    /**
     * Test bahwa jika jarak kos melebihi 5 km, layanan ditampilkan sebagai di luar jangkauan.
     */
    public function test_service_outside_five_km_radius_marked_as_out_of_range(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $owner = User::firstOrCreate(
            ['email' => 'owner.outofrange@sevenkos.com'],
            ['name' => 'Owner Out Of Range', 'password' => bcrypt('password'), 'phone' => '081234567877']
        );

        $provJabar = Province::firstOrCreate(['id' => '32'], ['name' => 'Jawa Barat']);
        $regBandung = Regency::firstOrCreate(['id' => '3273'], ['province_id' => '32', 'name' => 'Kota Bandung']);

        // Kost dengan koordinat jauh (> 5 km dari hub Bandung: lat -6.9039, lon 107.6186)
        // Koordinat ini ~15 km di Soreang / Bandung Selatan
        $kostJauh = Kost::create([
            'name' => 'Seven Kost Jauh Soreang',
            'slug' => 'kost-jauh-soreang-'.uniqid(),
            'user_id' => $owner->id,
            'province_id' => $provJabar->id,
            'regency_id' => $regBandung->id,
            'type' => 'campur',
            'price_start' => 900000,
            'rent_type' => 'bulanan',
            'address' => 'Jl. Soreang Raya No. 123, Soreang',
            'latitude' => -7.0250,
            'longitude' => 107.5180,
            'verification_status' => 'verified',
            'is_available' => true,
        ]);

        $room = Room::create([
            'kost_id' => $kostJauh->id,
            'name' => 'Kamar Standard 01',
            'price' => 900000,
            'total_rooms' => 2,
            'available_rooms' => 1,
        ]);

        Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kostJauh->id,
            'room_id' => $room->id,
            'start_date' => now()->subDays(2)->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'duration_months' => 2,
            'total_amount' => 1800000,
            'status' => 'approved',
            'tenant_name' => $tenant->name,
            'tenant_phone' => '08123456789',
        ]);

        $response = $this->actingAs($tenant)->get(route('services.index'));

        $response->assertStatus(200);
        $response->assertSee('Di Luar Jangkauan (> 5 km)', false);
        $response->assertSee('Radius Maksimal 5.0 KM', false);
    }

    /**
     * Test penghuni dapat memesan paket layanan dengan Payment Gateway Xendit.
     */
    public function test_tenant_can_order_service_via_xendit_and_redirects_to_payment_checkout(): void
    {
        AppSetting::set('payment_xendit_enabled', true, 'payment', 'boolean');

        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $response = $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'laundry',
            'package_name' => 'Laundry Express Kilat (24 Jam)',
            'package_price' => 12000,
            'package_unit' => '/kg',
            'quantity' => 3,
            'kost_address' => 'Seven Kost Melati, Jl. Duren Tiga No. 12',
            'room_number' => 'Kamar 102 (Lt. 1)',
            'occupant_name' => $tenant->name,
            'occupant_phone' => '081234567890',
            'order_date' => now()->addDay()->toDateString(),
            'order_time' => '14:00',
            'order_notes' => 'Pakaian mohon dipisah warna putih',
            'partner_name' => 'Seven Laundry Melati Jakarta',
            'payment_method' => 'xendit',
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $tenant->id,
            'type' => 'service',
            'amount' => 41000,
            'status' => 'pending',
        ]);

        $transaction = Transaction::where('user_id', $tenant->id)->latest()->first();
        $response->assertRedirect(route('tenant.services.payment', $transaction->reference_id));

        // Checkout page dapat diakses
        $checkoutResponse = $this->actingAs($tenant)->get(route('tenant.services.payment', $transaction->reference_id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Laundry Express Kilat (24 Jam)');
        $checkoutResponse->assertSee('Rp 41.000', false);
        $checkoutResponse->assertSee('QRIS', false);
    }

    /**
     * Test penghuni dapat memesan paket layanan langsung menggunakan Saldo Dompet Digital SevenKost.
     */
    public function test_tenant_can_order_service_via_wallet_balance_and_redirects_to_success(): void
    {
        $tenant = User::factory()->create([
            'wallet_balance' => 100000,
        ]);
        $tenant->assignRole('penghuni');

        $response = $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'cleaning',
            'package_name' => 'Deep Clean Kamar Mandi Kos',
            'package_price' => 65000,
            'package_unit' => '/sesi',
            'quantity' => 1,
            'kost_address' => 'Seven Kost Melati, Jl. Duren Tiga No. 12',
            'room_number' => 'Kamar 204',
            'occupant_name' => $tenant->name,
            'occupant_phone' => '081234567890',
            'order_date' => now()->addDay()->toDateString(),
            'order_time' => '10:00',
            'order_notes' => 'Fokus kerak kloset & lantai',
            'partner_name' => 'Seven Clean Jakarta Selatan',
            'payment_method' => 'wallet',
        ]);

        $transaction = Transaction::where('user_id', $tenant->id)->latest()->first();
        $response->assertRedirect(route('tenant.services.payment-success', $transaction->reference_id));

        // Transaksi berstatus paid & saldo user terpotong: 100.000 - (65.000 + 5.000) = 30.000
        $this->assertEquals('paid', $transaction->status);
        $this->assertEquals(30000, (float) $tenant->fresh()->wallet_balance);

        // Halaman sukses merender rincian pesanan
        $successResponse = $this->actingAs($tenant)->get(route('tenant.services.payment-success', $transaction->reference_id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Pesanan Layanan Dikonfirmasi!');
        $successResponse->assertSee('Deep Clean Kamar Mandi Kos');
        $successResponse->assertSee('Rp 70.000', false);
    }

    /**
     * Test pesanan via saldo dompet ditolak jika saldo tidak mencukupi.
     */
    public function test_tenant_order_with_insufficient_wallet_balance_is_rejected(): void
    {
        $tenant = User::factory()->create([
            'wallet_balance' => 10000, // hanya 10k
        ]);
        $tenant->assignRole('penghuni');

        $response = $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'cleaning',
            'package_name' => 'Deep Clean Kamar Mandi Kos',
            'package_price' => 65000,
            'package_unit' => '/sesi',
            'quantity' => 1,
            'kost_address' => 'Seven Kost Melati, Jl. Duren Tiga No. 12',
            'room_number' => 'Kamar 204',
            'occupant_name' => $tenant->name,
            'occupant_phone' => '081234567890',
            'order_date' => now()->addDay()->toDateString(),
            'order_time' => '10:00',
            'payment_method' => 'wallet',
        ]);

        $response->assertSessionHas('error');
        $this->assertEquals(10000, (float) $tenant->fresh()->wallet_balance);
        $this->assertDatabaseMissing('transactions', [
            'user_id' => $tenant->id,
            'amount' => 65000,
        ]);
    }

    /**
     * Test simulasi pelunasan instan Sandbox pada invoice layanan.
     */
    public function test_tenant_can_simulate_payment_on_service_invoice(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-SIM-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 21000,
            'payment_channel' => 'all',
            'status' => 'pending',
            'payload' => [
                'service_title' => 'Seven Laundry Kos',
                'package_name' => 'Laundry Kiloan Reguler',
                'quantity' => 3,
                'package_unit' => '/kg',
            ],
        ]);

        $response = $this->actingAs($tenant)->post(route('tenant.services.simulate-payment', $transaction->reference_id));
        $response->assertRedirect(route('tenant.services.payment-success', $transaction->reference_id));

        $this->assertEquals('paid', $transaction->fresh()->status);
        $this->assertNotNull($transaction->fresh()->paid_at);
    }

    /**
     * Test penghuni dapat melihat tab riwayat pesanan layanan dan memantau statusnya.
     */
    public function test_tenant_can_view_service_orders_history_and_status(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-HIST-'.uniqid(),
            'type' => 'service',
            'item_id' => 'cleaning',
            'title' => 'Seven Clean Kamar: Deep Clean Kamar Mandi',
            'amount' => 65000,
            'payment_channel' => 'all',
            'status' => 'paid',
            'paid_at' => now(),
            'payload' => [
                'service_title' => 'Seven Clean Kamar & Toilet',
                'package_name' => 'Deep Clean Kamar Mandi',
                'quantity' => 1,
                'package_unit' => '/sesi',
                'room_number' => 'Kamar 301',
                'order_date' => now()->addDay()->toDateString(),
                'order_time' => '11:30',
                'partner_name' => 'Seven Clean Jakarta Selatan',
            ],
        ]);

        $response = $this->actingAs($tenant)->get(route('services.index', ['tab' => 'orders']));
        $response->assertStatus(200);
        $response->assertSee('Riwayat & Status Pesanan', false);
        $response->assertSee('Pesanan Saya');
        $response->assertSee('Deep Clean Kamar Mandi');
        $response->assertSee('Diproses Mitra');
        $response->assertSee('Rp 65.000', false);
        $response->assertSee('Lacak Pesanan');
    }

    /**
     * Test penghuni dapat melacak pesanan layanan secara live ala ShopeeFood.
     */
    public function test_tenant_can_track_service_order_live_shopeefood_style(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $futureTime = now()->addHours(2)->format('H:i');
        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-TRK-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'payment_channel' => 'xendit',
            'status' => 'paid',
            'paid_at' => now(),
            'payload' => [
                'service_id' => 'laundry',
                'service_title' => 'Seven Laundry Kos',
                'package_name' => 'Laundry Kiloan Reguler',
                'quantity' => 2,
                'package_unit' => '/kg',
                'package_price' => 7000,
                'subtotal' => 14000,
                'delivery_fee' => 5000,
                'distance_km' => 1.4,
                'room_number' => 'Kamar 102 - Melati Residence',
                'kost_address' => 'Jl. Fatmawati No. 45, Jakarta Selatan',
                'order_date' => now()->toDateString(),
                'order_time' => $futureTime,
                'partner_name' => 'Seven Laundry Melati Jakarta',
                'occupant_name' => 'Penghuni Test',
                'occupant_phone' => '081234567890',
                'order_notes' => 'Tolong jemput di pos satpam',
            ],
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.services.tracking', $transaction->reference_id));
        $response->assertStatus(200);

        // Header status live tracking
        $response->assertSee('Diproses Mitra', false);
        $response->assertSee('Mitra Sedang Menuju Kos Anda');
        $response->assertSee('#'.$transaction->reference_id);
        $response->assertSee($futureTime.' WIB');

        // ShopeeFood Stepper
        $response->assertSee('Status Tahapan Pesanan');
        $response->assertSee('Pembayaran Lunas & Terkonfirmasi', false);
        $response->assertSee('Mitra Menuju Kamar Kos');

        // Mitra / Driver Card
        $response->assertSee('Budi Santoso');
        $response->assertSee('Terverifikasi SevenKost');
        $response->assertSee('Chat WhatsApp');
        $response->assertSee('Panggil Mitra');
        $response->assertSee('Honda Vario');

        // Route & Kamar Tujuan
        $response->assertSee('Kamar 102 - Melati Residence');
        $response->assertSee('Tolong jemput di pos satpam');

        // Rincian Pembayaran
        $response->assertSee('Rp 14.000', false);
        $response->assertSee('Biaya Antar-Jemput:');
        $response->assertSee('Rp 5.000 (1.4 km)');
    }

    /**
     * Test perhitungan ongkos kirim berbasis jarak km oleh platform & konfigurasi Superadmin.
     */
    public function test_delivery_fee_calculated_based_on_distance_tiers_and_superadmin_config(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        // 1. Order jarak 1.2 km (<= 2.0 km) -> Dikenakan tarif dasar Rp 5.000 (bukan gratis)
        $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'laundry',
            'package_name' => 'Laundry Kiloan Reguler',
            'package_price' => 7000,
            'package_unit' => '/kg',
            'quantity' => 2, // subtotal = 14.000
            'distance_km' => 1.2,
            'kost_address' => 'Jl. Fatmawati No. 45',
            'room_number' => 'Kamar 102',
            'occupant_name' => 'Penghuni Test',
            'occupant_phone' => '081234567890',
            'order_date' => now()->toDateString(),
            'order_time' => '10:00',
            'payment_method' => 'xendit',
        ]);

        $trxBase = Transaction::where('user_id', $tenant->id)->latest('id')->first();
        $this->assertEquals(19000, (float) $trxBase->amount); // 14.000 + 5.000
        $this->assertEquals(5000, $trxBase->payload['delivery_fee']);

        // 2. Order jarak 2.5 km (> 2.0 km) -> 5.000 + (1 x 2.000) = 7.000
        $response = $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'laundry',
            'package_name' => 'Laundry Kiloan Reguler',
            'package_price' => 7000,
            'package_unit' => '/kg',
            'quantity' => 2, // subtotal = 14.000
            'distance_km' => 2.5, // ongkir = 7.000
            'kost_address' => 'Jl. Fatmawati No. 45',
            'room_number' => 'Kamar 102',
            'occupant_name' => 'Penghuni Test',
            'occupant_phone' => '081234567890',
            'order_date' => now()->toDateString(),
            'order_time' => '10:00',
            'payment_method' => 'xendit',
        ]);

        $transaction = Transaction::where('user_id', $tenant->id)->latest('id')->first();
        $response->assertRedirect(route('tenant.services.payment', $transaction->reference_id));

        // Total amount = 14.000 (subtotal) + 7.000 (ongkir) = 21.000
        $this->assertEquals(21000, (float) $transaction->amount);
        $this->assertEquals(7000, $transaction->payload['delivery_fee']);
        $this->assertEquals(14000, $transaction->payload['subtotal']);
        $this->assertEquals(2.5, $transaction->payload['distance_km']);

        // 3. Konfigurasi Superadmin Diubah: Tarif Dasar Rp 8.000 (Radius 1.5 km), Tambahan Rp 3.000/km
        AppSetting::set('service_delivery_base_fee', 8000, 'services', 'number');
        AppSetting::set('service_delivery_base_km', 1.5, 'services', 'number');
        AppSetting::set('service_delivery_per_km', 3000, 'services', 'number');

        // Order jarak 2.5 km dengan tarif baru:
        // Selisih km = ceil(2.5 - 1.5) = 1 km -> Ongkir = 8.000 + (1 x 3.000) = 11.000
        $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'laundry',
            'package_name' => 'Laundry Kiloan Reguler',
            'package_price' => 7000,
            'package_unit' => '/kg',
            'quantity' => 2, // subtotal = 14.000
            'distance_km' => 2.5,
            'kost_address' => 'Jl. Fatmawati No. 45',
            'room_number' => 'Kamar 102',
            'occupant_name' => 'Penghuni Test',
            'occupant_phone' => '081234567890',
            'order_date' => now()->toDateString(),
            'order_time' => '10:00',
            'payment_method' => 'xendit',
        ]);

        $trxCustom = Transaction::where('user_id', $tenant->id)->latest('id')->first();
        $this->assertEquals(25000, (float) $trxCustom->amount); // 14.000 + 11.000
        $this->assertEquals(11000, $trxCustom->payload['delivery_fee']);
    }

    /**
     * Test penghuni dapat mengubah catatan untuk mitra pada halaman pelacakan pesanan.
     */
    public function test_tenant_can_update_order_notes_on_tracking_page(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-NOTE-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'payment_channel' => 'xendit',
            'status' => 'paid',
            'paid_at' => now(),
            'payload' => [
                'service_id' => 'laundry',
                'order_notes' => null,
            ],
        ]);

        $response = $this->actingAs($tenant)->patch(route('tenant.services.update-notes', $transaction->reference_id), [
            'order_notes' => 'Catatan baru: tolong ketuk pintu kamar lantai 2.',
        ]);

        $response->assertRedirect(route('tenant.services.tracking', $transaction->reference_id));
        $this->assertEquals('Catatan baru: tolong ketuk pintu kamar lantai 2.', $transaction->fresh()->payload['order_notes']);
    }

    /**
     * Test jika jadwal estimasi kedatangan mitra terlewati, sistem menampilkan status peringatan dan opsi solusi.
     */
    public function test_overdue_service_order_shows_warning_and_resolution_options(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-OVERDUE-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'payment_channel' => 'xendit',
            'status' => 'paid',
            'paid_at' => now()->subHours(6),
            'payload' => [
                'service_id' => 'laundry',
                'order_date' => now()->toDateString(),
                'order_time' => now()->subHours(2)->format('H:i'), // Jadwal 2 jam lalu
                'room_number' => 'Kamar 102',
                'occupant_name' => 'Penghuni Test',
            ],
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.services.tracking', $transaction->reference_id));
        $response->assertStatus(200);
        $response->assertSee('Melewati Jadwal');
        $response->assertSee('Jadwal Kedatangan Terlewat');
        $response->assertSee('Jadwalkan Ulang');
        $response->assertSee('Batalkan &amp; Refund', false);
    }

    /**
     * Test penghuni dapat menjadwalkan ulang (reschedule) waktu kedatangan mitra layanan.
     */
    public function test_tenant_can_reschedule_overdue_service_order(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-RESCHED-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'payment_channel' => 'xendit',
            'status' => 'paid',
            'paid_at' => now(),
            'payload' => [
                'service_id' => 'laundry',
                'order_date' => now()->toDateString(),
                'order_time' => '09:00',
            ],
        ]);

        $newDate = now()->addDay()->toDateString();
        $response = $this->actingAs($tenant)->patch(route('tenant.services.reschedule', $transaction->reference_id), [
            'order_date' => $newDate,
            'order_time' => '14:00',
        ]);

        $response->assertRedirect(route('tenant.services.tracking', $transaction->reference_id));
        $freshPayload = $transaction->fresh()->payload;
        $this->assertEquals($newDate, $freshPayload['order_date']);
        $this->assertEquals('14:00', $freshPayload['order_time']);
        $this->assertNotNull($freshPayload['rescheduled_at']);
    }

    /**
     * Test penghuni dapat membatalkan pesanan yang terlambat dan dana dikembalikan 100% ke saldo dompet.
     */
    public function test_tenant_can_cancel_overdue_order_and_receive_full_wallet_refund(): void
    {
        $tenant = User::factory()->create([
            'wallet_balance' => 50000,
        ]);
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-CANCEL-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'payment_channel' => 'xendit',
            'status' => 'paid',
            'paid_at' => now()->subHours(3),
            'payload' => [
                'service_id' => 'laundry',
                'order_date' => now()->toDateString(),
                'order_time' => '09:00',
            ],
        ]);

        $response = $this->actingAs($tenant)->post(route('tenant.services.cancel-refund', $transaction->reference_id));
        $response->assertRedirect(route('tenant.services.tracking', $transaction->reference_id));

        // Transaksi berstatus failed dengan payload service_order_status cancelled
        $this->assertEquals('failed', $transaction->fresh()->status);
        $this->assertEquals('cancelled', $transaction->fresh()->payload['service_order_status']);
        $this->assertEquals(14000, $transaction->fresh()->payload['refund_amount']);

        // Saldo dompet bertambah 14.000: dari 50.000 menjadi 64.000
        $this->assertEquals(64000, (float) $tenant->fresh()->wallet_balance);
    }

    /**
     * Test alur pembayaran manual saat Xendit dinonaktifkan oleh Superadmin (Custom In-App UI).
     */
    public function test_tenant_can_order_with_xendit_off_and_uses_custom_manual_payment_ui(): void
    {
        AppSetting::set('payment_xendit_enabled', false, 'payment', 'boolean');
        AppSetting::set('payment_manual_enabled', true, 'payment', 'boolean');

        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $response = $this->actingAs($tenant)->post(route('services.order'), [
            'service_id' => 'laundry',
            'package_name' => 'Laundry Kiloan Reguler',
            'package_price' => 7000,
            'package_unit' => '/kg',
            'quantity' => 2,
            'distance_km' => 1.5,
            'kost_address' => 'Jl. RS Fatmawati No. 45',
            'room_number' => 'Kamar 102',
            'occupant_name' => 'Penghuni Test',
            'occupant_phone' => '081234567890',
            'order_date' => now()->toDateString(),
            'order_time' => '10:00',
            'payment_method' => 'xendit',
        ]);

        $transaction = Transaction::where('user_id', $tenant->id)->latest()->first();
        $response->assertRedirect(route('tenant.services.payment', $transaction->reference_id));
        $this->assertEquals('manual', $transaction->fresh()->payment_channel);

        // Halaman checkout merender Custom SevenKost Payment UI (QRIS & Transfer Bank)
        $checkoutResponse = $this->actingAs($tenant)->get(route('tenant.services.payment', $transaction->reference_id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Scan QRIS');
        $checkoutResponse->assertSee('Transfer Bank');
        $checkoutResponse->assertSee('Konfirmasi Bukti Transfer');
        $checkoutResponse->assertSee('Kirim Bukti Pembayaran');
    }

    /**
     * Test penghuni dapat mengunggah foto bukti pembayaran dan status berubah menjadi menunggu verifikasi.
     */
    public function test_tenant_can_upload_payment_proof_image_and_status_becomes_awaiting_confirmation(): void
    {
        Storage::fake('public');

        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-PROOF-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 19000,
            'payment_channel' => 'manual',
            'status' => 'pending',
            'payload' => [
                'service_id' => 'laundry',
                'order_date' => now()->toDateString(),
                'order_time' => '10:00',
            ],
        ]);

        $file = UploadedFile::fake()->image('struk_transfer.png');

        $response = $this->actingAs($tenant)->post(route('tenant.services.upload-proof', $transaction->reference_id), [
            'proof_image' => $file,
            'sender_name' => 'Budi Pengirim',
            'sender_bank' => 'BCA',
        ]);

        $response->assertSessionHas('success');
        $freshPayload = $transaction->fresh()->payload;
        $this->assertEquals('awaiting_confirmation', $freshPayload['payment_confirmation_status']);
        $this->assertEquals('Budi Pengirim', $freshPayload['payment_sender_name']);
        $this->assertEquals('BCA', $freshPayload['payment_sender_bank']);
        $this->assertNotNull($freshPayload['payment_proof_image']);

        Storage::disk('public')->assertExists($freshPayload['payment_proof_image']);
    }

    /**
     * Test penghuni dapat memesan layanan dengan opsi Mandiri / Self-Service (antar & ambil sendiri ke toko)
     * dan biaya pengantaran / ongkir menjadi Rp 0 (Gratis Ongkir).
     */
    public function test_tenant_can_order_service_with_self_service_fulfillment_and_free_delivery_fee(): void
    {
        $tenant = User::factory()->create([
            'wallet_balance' => 100000,
        ]);
        $tenant->assignRole('penghuni');

        $orderPayload = [
            'service_id' => 'laundry',
            'package_name' => 'Cuci Kering Lipat Express 1 Hari',
            'package_price' => 25000,
            'package_unit' => 'kg',
            'quantity' => 2, // Subtotal = 50.000
            'fulfillment_type' => 'self_service', // Bebas ongkir (delivery_fee = 0)
            'distance_km' => 3.5, // Walau jarak 3.5 km, ongkir tetap 0
            'kost_address' => 'SevenKost Residence Dago No. 12',
            'room_number' => 'B-04',
            'occupant_name' => 'Ahmad Rasyid',
            'occupant_phone' => '081299887766',
            'order_date' => now()->toDateString(),
            'order_time' => '14:00',
            'order_notes' => 'Akan saya antarkan jam 2 siang ini.',
            'payment_method' => 'wallet',
        ];

        $response = $this->actingAs($tenant)->post(route('services.order'), $orderPayload);

        $response->assertRedirect();

        $transaction = Transaction::where('user_id', $tenant->id)
            ->where('type', 'service')
            ->latest('id')
            ->first();

        $this->assertNotNull($transaction);
        // Total amount harus persis sama dengan subtotal (50.000) tanpa ongkir
        $this->assertEquals(50000, (float) $transaction->amount);
        $this->assertEquals(0, (float) $transaction->payload['delivery_fee']);
        $this->assertEquals('self_service', $transaction->payload['fulfillment_type']);

        // Saldo dompet berkurang persis 50.000: 100.000 - 50.000 = 50.000
        $this->assertEquals(50000, (float) $tenant->fresh()->wallet_balance);
    }

    /**
     * Test penghuni dapat mengakses portal layanan khusus penghuni di /penghuni/layanan.
     */
    public function test_tenant_can_access_dedicated_tenant_service_portal(): void
    {
        $tenant = User::factory()->create(['email' => 'tenant.hist.'.uniqid().'@test.com']);
        $tenant->assignRole('penghuni');

        $response = $this->actingAs($tenant)->get(route('tenant.services.index'));

        $response->assertStatus(200);
        $response->assertSee('Katalog Layanan');
        $response->assertSee('Seven Laundry Kos');
    }

    /**
     * Test guest tidak dapat mengakses portal layanan penghuni dan diarahkan ke login.
     */
    public function test_guest_is_redirected_when_accessing_tenant_service_portal(): void
    {
        $response = $this->get(route('tenant.services.index'));

        $response->assertRedirect(route('login'));
    }
}
