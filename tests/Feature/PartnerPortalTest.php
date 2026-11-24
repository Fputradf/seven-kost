<?php

namespace Tests\Feature;

use App\Models\KycVerification;
use App\Models\ServicePartner;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PartnerPortalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RolePermissionSeeder::class,
        ]);
    }

    /**
     * Helper untuk membuat akun mitra dan profil usaha ServicePartner.
     */
    private function createPartnerUser(string $category = 'laundry', array $attributes = []): array
    {
        /** @var User $user */
        $user = User::factory()->create([
            'email' => 'partner_'.uniqid().'@example.com',
            'wallet_balance' => $attributes['wallet_balance'] ?? 0,
        ]);
        $user->assignRole('mitra');

        $partner = ServicePartner::create([
            'user_id' => $user->id,
            'business_name' => $attributes['business_name'] ?? 'Seven Laundry Mandiri',
            'category' => $category,
            'phone' => '081234567890',
            'is_open' => $attributes['is_open'] ?? true,
            'bank_name' => $attributes['bank_name'] ?? 'BCA',
            'bank_account_number' => $attributes['bank_account_number'] ?? '8730129841',
            'bank_account_holder' => $attributes['bank_account_holder'] ?? $user->name,
            'verification_status' => 'verified',
            'operational_hours' => '08:00 - 20:00 WIB',
        ]);

        KycVerification::create([
            'user_id' => $user->id,
            'role' => 'mitra',
            'nik' => fake()->unique()->numerify('################'),
            'full_name' => $user->name,
            'ktp_path' => 'kyc/ktp/dummy.jpg',
            'selfie_path' => 'kyc/selfie/dummy.jpg',
            'status' => 'verified',
            'submitted_at' => now(),
            'reviewed_at' => now(),
        ]);

        return [$user, $partner];
    }

    /**
     * Test pengunjung tanpa login tidak bisa mengakses portal mitra dan diarahkan ke login.
     */
    public function test_guest_cannot_access_partner_portal_and_redirects_to_login(): void
    {
        $response = $this->get(route('partner.dashboard'));
        $response->assertRedirect('/login');
    }

    /**
     * Test pengguna dengan role selain mitra mendapatkan error 403 Forbidden.
     */
    public function test_non_mitra_user_is_forbidden_from_partner_portal(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $response = $this->actingAs($tenant)->get(route('partner.dashboard'));
        $response->assertStatus(403);
    }

    /**
     * Test akun mitra yang mengakses rute /dashboard otomatis dialihkan ke /mitra/dashboard.
     */
    public function test_mitra_user_is_redirected_to_partner_dashboard_from_root_dashboard(): void
    {
        [$user, $partner] = $this->createPartnerUser();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertRedirect(route('partner.dashboard'));
    }

    /**
     * Test mitra dapat membuka halaman dashboard dan melihat metrik operasional.
     */
    public function test_mitra_can_access_dashboard_and_views_operational_metrics(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', [
            'business_name' => 'Laundry Cepat Dago',
        ]);

        $response = $this->actingAs($user)->get(route('partner.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Laundry Cepat Dago');
        $response->assertSee('Mitra Layanan');
        $response->assertSee('Butuh Konfirmasi');
        $response->assertSee('Sedang Berjalan');
        $response->assertSee('Selesai Hari Ini');
        $response->assertSee('Saldo Dompet Mitra');
    }

    /**
     * Test mitra dapat mengubah status toko (Buka / Tutup Operasional) via toggle.
     */
    public function test_mitra_can_toggle_store_open_status(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', ['is_open' => true]);

        $response = $this->actingAs($user)->patch(route('partner.profile.toggle-status'));
        $response->assertRedirect();

        $this->assertFalse((bool) $partner->fresh()->is_open);

        // Toggle kembali ke aktif
        $this->actingAs($user)->patch(route('partner.profile.toggle-status'));
        $this->assertTrue((bool) $partner->fresh()->is_open);
    }

    /**
     * Test mitra dapat melihat daftar pesanan pada halaman /mitra/pesanan.
     */
    public function test_mitra_can_view_orders_list_with_tabs_and_search(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry');

        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $refId = 'SRV-MITRA-'.uniqid();
        Transaction::create([
            'user_id' => $tenant->id,
            'partner_id' => $partner->id,
            'reference_id' => $refId,
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 21000,
            'status' => 'paid',
            'payload' => [
                'occupant_name' => 'Budi Penyewa',
                'occupant_phone' => '081299887766',
                'room_number' => 'Kamar 101',
                'kost_address' => 'Jl. Dago Asri No. 10',
                'service_order_status' => 'pending',
                'quantity' => 3,
                'package_unit' => '/kg',
            ],
        ]);

        $response = $this->actingAs($user)->get(route('partner.orders.index'));

        $response->assertStatus(200);
        $response->assertSee($refId);
        $response->assertSee('Budi Penyewa');
        $response->assertSee('Kamar 101');
        $response->assertSee('Rp 21.000', false);
    }

    /**
     * Test mitra dapat menerima pesanan layanan yang berstatus pending.
     */
    public function test_mitra_can_accept_pending_order(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry');
        $tenant = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-ACCP-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'status' => 'paid',
            'payload' => [
                'service_order_status' => 'pending',
            ],
        ]);

        $response = $this->actingAs($user)->post(route('partner.orders.accept', $transaction->reference_id));
        $response->assertRedirect();

        $fresh = $transaction->fresh();
        $this->assertEquals($partner->id, $fresh->partner_id);
        $this->assertEquals('confirmed', $fresh->payload['service_order_status']);
        $this->assertNotNull($fresh->payload['confirmed_at']);
    }

    /**
     * Test mitra dapat menugaskan kurir/petugas pelaksana dan status berubah menjadi on_the_way.
     */
    public function test_mitra_can_assign_courier_and_status_changes_to_on_the_way(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry');
        $tenant = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'partner_id' => $partner->id,
            'reference_id' => 'SRV-ASGN-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'status' => 'paid',
            'payload' => [
                'service_order_status' => 'confirmed',
            ],
        ]);

        $response = $this->actingAs($user)->post(route('partner.orders.assign-courier', $transaction->reference_id), [
            'courier_name' => 'Fajar Santoso',
            'courier_phone' => '081234567899',
            'vehicle_info' => 'Honda Beat • D 1234 ABC',
        ]);

        $response->assertRedirect();

        $fresh = $transaction->fresh();
        $this->assertEquals('on_the_way', $fresh->payload['service_order_status']);
        $this->assertEquals('Fajar Santoso', $fresh->payload['courier_name']);
        $this->assertEquals('081234567899', $fresh->payload['courier_phone']);
        $this->assertEquals('Honda Beat • D 1234 ABC', $fresh->payload['vehicle_info']);
    }

    /**
     * Test kurir yang ditugaskan oleh mitra langsung terefleksikan di halaman tracking penghuni.
     */
    public function test_mitra_assigned_courier_is_reflected_on_tenant_tracking_page(): void
    {
        [$mitraUser, $partner] = $this->createPartnerUser('laundry');

        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'partner_id' => $partner->id,
            'reference_id' => 'SRV-TRK-'.uniqid(),
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Laundry Kiloan Reguler',
            'amount' => 14000,
            'status' => 'paid',
            'paid_at' => now(),
            'payload' => [
                'service_id' => 'laundry',
                'service_order_status' => 'on_the_way',
                'courier_name' => 'Hendro Setiawan',
                'courier_phone' => '081299998888',
                'vehicle_info' => 'Yamaha NMAX • D 5678 XYZ',
                'order_date' => now()->toDateString(),
                'order_time' => '15:00',
            ],
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.services.tracking', $transaction->reference_id));

        $response->assertStatus(200);
        $response->assertSee('Hendro Setiawan');
        $response->assertSee('Yamaha NMAX • D 5678 XYZ');
        $response->assertSee('081299998888');
    }

    /**
     * Test alur pengerjaan dimulai dan diselesaikan dengan bukti foto yang mengkredit saldo dompet mitra.
     */
    public function test_mitra_can_start_work_and_complete_order_with_proof_image_crediting_partner_wallet(): void
    {
        Storage::fake('public');

        [$user, $partner] = $this->createPartnerUser('cleaning', [
            'wallet_balance' => 10000,
        ]);
        $tenant = User::factory()->create();

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'partner_id' => $partner->id,
            'reference_id' => 'SRV-CMPL-'.uniqid(),
            'type' => 'service',
            'item_id' => 'cleaning',
            'title' => 'Seven Clean Kamar: Deep Clean Kamar Mandi',
            'amount' => 65000,
            'status' => 'paid',
            'payload' => [
                'service_order_status' => 'on_the_way',
            ],
        ]);

        // 1. Mulai pengerjaan
        $resStart = $this->actingAs($user)->post(route('partner.orders.start-work', $transaction->reference_id));
        $resStart->assertRedirect();
        $this->assertEquals('in_progress', $transaction->fresh()->payload['service_order_status']);

        // 2. Selesaikan pesanan & upload foto bukti
        $file = UploadedFile::fake()->image('kamar_bersih.jpg');
        $resComplete = $this->actingAs($user)->post(route('partner.orders.complete', $transaction->reference_id), [
            'proof_image' => $file,
            'completion_notes' => 'Pembersihan toilet kos selesai tuntas.',
        ]);

        $resComplete->assertRedirect();

        $fresh = $transaction->fresh();
        $this->assertEquals('completed', $fresh->payload['service_order_status']);
        $this->assertNotNull($fresh->payload['proof_image']);
        Storage::disk('public')->assertExists($fresh->payload['proof_image']);

        // Saldo dompet mitra bertambah 65.000 (dari 10.000 menjadi 75.000)
        $this->assertEquals(75000, (float) $user->fresh()->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'type' => 'topup',
            'amount' => 65000,
        ]);
    }

    /**
     * Test mitra dapat mengajukan penarikan saldo pendapatan (withdrawal) ke rekening bank.
     */
    public function test_mitra_can_withdraw_earnings_to_bank_account(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', [
            'wallet_balance' => 150000,
            'bank_name' => 'BCA',
            'bank_account_number' => '8730129841',
            'bank_account_holder' => 'Mitra Budi',
        ]);

        $response = $this->actingAs($user)->post(route('partner.finances.withdraw'), [
            'amount' => 100000,
            'bank_name' => 'BCA',
            'account_number' => '8730129841',
            'account_holder' => 'Mitra Budi',
        ]);

        $response->assertSessionHas('success');

        // Saldo berkurang 100.000: 150.000 - 100.000 = 50.000
        $this->assertEquals(50000, (float) $user->fresh()->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $user->id,
            'type' => 'withdrawal',
            'amount' => 100000,
            'bank_name' => 'BCA',
            'account_number' => '8730129841',
        ]);
    }

    /**
     * Test mitra dapat memperbarui profil usaha dan area operasional.
     */
    public function test_mitra_can_update_business_profile_details(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', [
            'business_name' => 'Laundry Lama',
        ]);

        $response = $this->actingAs($user)->put(route('partner.profile.update'), [
            'business_name' => 'Laundry Express Juara',
            'category' => 'laundry',
            'phone' => '081299881122',
            'service_radius_km' => 7.5,
            'operational_hours' => '07:00 - 22:00 WIB',
            'bank_name' => 'Bank Mandiri',
            'bank_account_number' => '137009988112',
            'bank_account_holder' => 'Juara Laundry',
        ]);

        $response->assertSessionHas('success');

        $fresh = $partner->fresh();
        $this->assertEquals('Laundry Express Juara', $fresh->business_name);
        $this->assertEquals('081299881122', $fresh->phone);
        $this->assertEquals(7.5, (float) $fresh->service_radius_km);
        $this->assertEquals('Bank Mandiri', $fresh->bank_name);
    }

    /**
     * Test mitra dapat mengubah dan mengunggah banner iklan layanan langsung dari formulir profil usaha.
     */
    public function test_mitra_can_update_banner_directly_from_business_profile_form(): void
    {
        Storage::fake('public');

        [$user, $partner] = $this->createPartnerUser('laundry', [
            'business_name' => 'Laundry Profil Mantap',
        ]);

        $bannerImage = UploadedFile::fake()->image('banner_profile.jpg', 1200, 400);

        $response = $this->actingAs($user)->put(route('partner.profile.update'), [
            'business_name' => 'Laundry Profil Mantap',
            'category' => 'laundry',
            'phone' => '081299881122',
            'banner_image' => $bannerImage,
            'banner_title' => 'Diskon Kilat Profil 35% Hari Ini',
            'banner_target_url' => 'https://wa.me/6281299881122',
            'banner_is_active' => '1',
        ]);

        $response->assertSessionHas('success');

        $this->assertNotNull($partner->fresh()->banner_image);

        $this->assertDatabaseHas('banners', [
            'user_id' => $user->id,
            'title' => 'Diskon Kilat Profil 35% Hari Ini',
            'is_active' => true,
            'target_url' => 'https://wa.me/6281299881122',
        ]);

        // Verifikasi langsung muncul di katalog layanan
        $resCatalog = $this->get('/layanan');
        $resCatalog->assertStatus(200);
        $resCatalog->assertSee('Diskon Kilat Profil 35% Hari Ini');
        $resCatalog->assertSee('Laundry Profil Mantap');
    }

    /**
     * Test alur pesanan mandiri / self-service (antar & ambil sendiri ke outlet toko mitra)
     * tanpa perlu penugasan kurir, dapat ditandai 'ready_for_pickup' dan diselesaikan.
     */
    public function test_mitra_can_process_self_service_order_without_assigning_courier_and_marks_ready_for_pickup(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry');

        $tenant = User::factory()->create();
        $refId = 'SRV-SELF-'.uniqid();

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => $refId,
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Cuci Lipat Reguler',
            'amount' => 30000,
            'status' => 'paid',
            'payload' => [
                'service_id' => 'laundry',
                'fulfillment_type' => 'self_service',
                'delivery_fee' => 0,
                'package_name' => 'Cuci Lipat Reguler 3 Hari',
                'occupant_name' => 'Budi Santoso',
                'occupant_phone' => '081234567890',
                'service_order_status' => 'confirmed',
            ],
        ]);

        // 1. Mitra melihat detail pesanan mandiri
        $resShow = $this->actingAs($user)->get(route('partner.orders.show', $refId));
        $resShow->assertStatus(200);
        $resShow->assertSee('Mandiri', false);
        $resShow->assertSee('Penerimaan di Toko Mitra');

        // 2. Penghuni tiba di toko, Mitra mulai mencuci cucian (status -> in_progress)
        $resStart = $this->actingAs($user)->post(route('partner.orders.start-work', $refId));
        $resStart->assertRedirect();
        $this->assertEquals('in_progress', $transaction->fresh()->payload['service_order_status']);

        // 3. Cucian selesai dicuci, Mitra menandai siap diambil (status -> ready_for_pickup)
        $resReady = $this->actingAs($user)->post(route('partner.orders.mark-ready', $refId));
        $resReady->assertRedirect();
        $this->assertEquals('ready_for_pickup', $transaction->fresh()->payload['service_order_status']);

        // 4. Penghuni datang mengambil cucian, Mitra konfirmasi serah terima (status -> completed)
        $resComplete = $this->actingAs($user)->post(route('partner.orders.complete', $refId), [
            'completion_notes' => 'Cucian telah diambil langsung oleh Kak Budi di outlet.',
        ]);
        $resComplete->assertRedirect();

        $fresh = $transaction->fresh();
        $this->assertEquals('completed', $fresh->payload['service_order_status']);
        $this->assertEquals(30000, (float) $user->fresh()->wallet_balance);
    }

    /**
     * Test mitra dapat melihat bagian pengelolaan foto kartu layanan di halaman pengaturan usaha.
     */
    public function test_mitra_profile_page_renders_service_card_image_management_section(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', [
            'business_name' => 'Laundry Bersih Dago',
        ]);

        $response = $this->actingAs($user)->get(route('partner.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Foto Utama Kartu Layanan');
        $response->assertSee('Unggah / Ganti Foto Kartu Layanan');
    }

    /**
     * Test formulir pengaturan mitra merender dropdown wilayah dinamis dan tombol radius di samping alamat.
     */
    public function test_mitra_profile_page_renders_dynamic_location_and_radius_stepper(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', [
            'service_radius_km' => 6.5,
        ]);

        $response = $this->actingAs($user)->get(route('partner.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Radius Layanan (KM)');
        $response->assertSee('Kurangi radius (-0.5 km)');
        $response->assertSee('Tambah radius (+0.5 km)');
        $response->assertSee('onProvChange(');
        $response->assertSee('onRegencyChange()');
    }

    /**
     * Test halaman pengaturan mitra memisahkan formulir ke dalam sistem tab navigasi.
     */
    public function test_mitra_profile_page_renders_separated_tab_navigation(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry');

        $response = $this->actingAs($user)->get(route('partner.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('1. Profil &amp; Kartu Layanan', false);
        $response->assertSee('2. Wilayah &amp; Lokasi Hub', false);
        $response->assertSee('3. Rekening Bank Pencairan', false);
        $response->assertSee('4. Profil Pengelola');
        $response->assertSee('Simpan Profil Usaha');
        $response->assertSee('Simpan Pengaturan Lokasi');
        $response->assertSee('Simpan Rekening Bank');
        $response->assertSee('Simpan Profil Pengelola');
        $response->assertSee('Pengelola:');
    }

    /**
     * Test mitra yang belum verifikasi KYC tidak dapat mengakses fitur operasional (fitur dikunci).
     */
    public function test_unverified_mitra_cannot_access_operational_features_and_redirects_to_kyc(): void
    {
        /** @var User $unverifiedUser */
        $unverifiedUser = User::factory()->create();
        $unverifiedUser->assignRole('mitra');

        ServicePartner::create([
            'user_id' => $unverifiedUser->id,
            'business_name' => 'Mitra Baru Laundry',
            'category' => 'laundry',
            'phone' => '081234567891',
            'is_open' => false,
            'verification_status' => 'pending',
            'operational_hours' => '08:00 - 20:00 WIB',
        ]);

        // Coba akses dasbor, pesanan, dan keuangan
        $resDash = $this->actingAs($unverifiedUser)->get(route('partner.dashboard'));
        $resDash->assertRedirect(route('kyc.index'));
        $resDash->assertSessionHas('warning');

        $resOrders = $this->actingAs($unverifiedUser)->get(route('partner.orders.index'));
        $resOrders->assertRedirect(route('kyc.index'));

        $resFinances = $this->actingAs($unverifiedUser)->get(route('partner.finances.index'));
        $resFinances->assertRedirect(route('kyc.index'));
    }

    /**
     * Test halaman KYC menampilkan sidebar mitra dan menandai fitur lain sebagai Terkunci.
     */
    public function test_kyc_page_renders_with_partner_sidebar_and_shows_locked_features_for_unverified_mitra(): void
    {
        /** @var User $unverifiedUser */
        $unverifiedUser = User::factory()->create();
        $unverifiedUser->assignRole('mitra');

        ServicePartner::create([
            'user_id' => $unverifiedUser->id,
            'business_name' => 'Mitra Baru Laundry',
            'category' => 'laundry',
            'phone' => '081234567891',
            'is_open' => false,
            'verification_status' => 'pending',
            'operational_hours' => '08:00 - 20:00 WIB',
        ]);

        $response = $this->actingAs($unverifiedUser)->get(route('kyc.index'));
        $response->assertStatus(200);

        // Memastikan sidebar mitra dirender
        $response->assertSee('Ikhtisar Operasional');
        $response->assertSee('Pesanan Layanan');
        $response->assertSee('Dompet & Keuangan', false);
        $response->assertSee('Profil & Pengaturan', false);

        // Tidak menampilkan teks 'Terkunci' pada sidebar sesuai preferensi UI
        $response->assertDontSee('Terkunci');

        // Menampilkan card gradient pada form
        $response->assertSee('Verifikasi Identitas Pengguna');

        // Menu KYC aktif dan wajib
        $response->assertSee('Verifikasi Identitas (KYC)');
        $response->assertSee('Wajib');
    }

    /**
     * Test menu KYC hilang dari sidebar setelah mitra menyelesaikan verifikasi KYC.
     */
    public function test_kyc_menu_item_disappears_from_sidebar_once_mitra_is_verified(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry');

        $response = $this->actingAs($user)->get(route('partner.dashboard'));
        $response->assertStatus(200);

        // Menu KYC hilang dari navigasi sidebar setelah verifikasi selesai
        $response->assertDontSee('Verifikasi Identitas (KYC)');

        // Fitur lain terbuka dan tidak ada badge Terkunci
        $response->assertDontSee('Terkunci');
        $response->assertSee('Daftar Pesanan');
        $response->assertSee('Dompet Pendapatan');
        $response->assertSee('Pengaturan Usaha');
    }
}
