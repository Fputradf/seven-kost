<?php

namespace Tests\Feature;

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
     * Test mitra dapat melihat bagian pengelolaan banner iklan di halaman profil usaha.
     */
    public function test_mitra_profile_page_renders_banner_management_section(): void
    {
        [$user, $partner] = $this->createPartnerUser('laundry', [
            'business_name' => 'Laundry Bersih Dago',
        ]);

        $response = $this->actingAs($user)->get(route('partner.profile.index'));

        $response->assertStatus(200);
        $response->assertSee('Banner Iklan Halaman /layanan');
        $response->assertSee('Unggah / Ganti Gambar Banner');
        $response->assertSee('Judul Promo / Headline Banner Iklan');
    }
}
