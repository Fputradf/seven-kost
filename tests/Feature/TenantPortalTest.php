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
use App\Services\XenditService;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantPortalTest extends TestCase
{
    private function getOrCreateTestKost(): Kost
    {
        $owner = User::firstOrCreate(
            ['email' => 'owner.test@sevenkos.com'],
            ['name' => 'Owner Test', 'password' => bcrypt('password'), 'phone' => '081234567890']
        );

        $province = Province::first() ?? Province::create(['id' => '31', 'name' => 'DKI Jakarta']);
        $regency = Regency::first() ?? Regency::create(['id' => '3171', 'province_id' => $province->id, 'name' => 'Jakarta Selatan']);

        $kost = Kost::firstOrCreate(
            ['slug' => 'kost-test-tenant-portal'],
            [
                'name' => 'Kost Test Tenant Portal',
                'user_id' => $owner->id,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'type' => 'campur',
                'price_start' => 1500000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Uji Tenant No. 7, Jakarta',
                'description' => 'Kos untuk pengujian modul penghuni.',
                'verification_status' => 'verified',
                'is_available' => true,
            ]
        );

        $room = Room::firstOrCreate(
            ['kost_id' => $kost->id, 'name' => 'Tipe Standard A'],
            [
                'price' => 1500000,
                'total_rooms' => 5,
                'available_rooms' => 5,
                'room_size' => '3x4 m',
            ]
        );
        $room->update(['available_rooms' => 5]);

        return $kost;
    }

    private function getOrCreateTenantUser(): User
    {
        Role::firstOrCreate(['name' => 'penghuni', 'guard_name' => 'web']);

        $tenant = User::firstOrCreate(
            ['email' => 'tenant.test@sevenkos.com'],
            ['name' => 'Siti Penyewa Test', 'password' => bcrypt('password'), 'phone' => '089876543210']
        );

        if (! $tenant->hasRole('penghuni')) {
            $tenant->assignRole('penghuni');
        }

        return $tenant;
    }

    public function test_guest_cannot_access_tenant_dashboard(): void
    {
        $response = $this->get('/penghuni/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_tenant_can_access_dashboard(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get('/penghuni/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Portal Penghuni');
        $response->assertSee($tenant->name);
    }

    public function test_dashboard_redirects_tenant_to_tenant_dashboard(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get('/dashboard');

        $response->assertRedirect(route('tenant.dashboard'));
    }

    public function test_tenant_can_toggle_wishlist_json(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();

        // 1. Add to wishlist
        $response = $this->actingAs($tenant)->postJson(route('tenant.wishlists.toggle', $kost->id));

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_favorited' => true,
            ]);

        $this->assertDatabaseHas('wishlists', [
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
        ]);

        // 2. Remove from wishlist
        $response2 = $this->actingAs($tenant)->postJson(route('tenant.wishlists.toggle', $kost->id));

        $response2->assertStatus(200)
            ->assertJson([
                'success' => true,
                'is_favorited' => false,
            ]);

        $this->assertDatabaseMissing('wishlists', [
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
        ]);
    }

    public function test_tenant_can_view_wishlist_page(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get('/penghuni/favorit');

        $response->assertStatus(200);
        $response->assertSee('Kos Favorit Saya');
    }

    public function test_tenant_can_create_and_view_complaint(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();

        $response = $this->actingAs($tenant)->post(route('tenant.complaints.store'), [
            'kost_id' => $kost->id,
            'category' => 'listrik_elektronik',
            'reason' => 'AC Kamar Tidak Dingin dan Berisik',
            'details' => 'AC meneteskan air ke lantai dan tidak mengeluarkan udara dingin sejak kemarin malam.',
        ]);

        $response->assertRedirect(route('tenant.complaints.index'));

        $this->assertDatabaseHas('reports', [
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'category' => 'listrik_elektronik',
            'reason' => 'AC Kamar Tidak Dingin dan Berisik',
            'status' => 'pending',
        ]);

        $indexResponse = $this->actingAs($tenant)->get(route('tenant.complaints.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('AC Kamar Tidak Dingin dan Berisik');
    }

    public function test_tenant_can_view_create_complaint_form(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get(route('tenant.complaints.create'));
        $response->assertStatus(200);
        $response->assertSee('Lapor Kendala / Kerusakan Fasilitas');
    }

    public function test_public_cari_page_renders_web_layout_for_guests(): void
    {
        $response = $this->get(url('/cari'));
        $response->assertStatus(200);
        $response->assertSee('Jelajah Kos Seluruh Indonesia');
    }

    public function test_tenant_search_page_requires_auth(): void
    {
        $response = $this->get(route('tenant.search'));
        $response->assertRedirect(route('login'));
    }

    public function test_tenant_can_access_dedicated_mobile_search_page(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get(route('tenant.search'));
        $response->assertStatus(200);
        $response->assertSee('Cari Kamar Kos Impian');
        $response->assertSee('Cari nama atau area kos...');
    }

    public function test_tenant_can_view_mobile_kost_detail(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();

        $response = $this->actingAs($tenant)->get(route('tenant.kost.detail', $kost->slug));
        $response->assertStatus(200);
        $response->assertSee($kost->name);
        $response->assertSee('Pilihan Tipe Kamar');
        $response->assertSee('Checkout Sewa');
    }

    public function test_guest_accessing_tenant_kost_detail_redirects_to_login(): void
    {
        $kost = $this->getOrCreateTestKost();

        $response = $this->get(route('tenant.kost.detail', $kost->slug));
        $response->assertRedirect(route('login'));
    }

    public function test_public_kost_detail_renders_normally_for_guests(): void
    {
        $kost = $this->getOrCreateTestKost();

        $response = $this->get(route('kost.detail', $kost->slug));
        $response->assertStatus(200);
        $response->assertSee($kost->name);
    }

    public function test_tenant_map_requires_auth(): void
    {
        $response = $this->get(route('tenant.map'));
        $response->assertRedirect(route('login'));
    }

    public function test_tenant_can_access_dedicated_mobile_map(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get(route('tenant.map'));
        $response->assertStatus(200);
        $response->assertSee('mobileTenantMap');
        $response->assertSee('Filter Area Peta');
    }

    public function test_public_map_renders_for_guests(): void
    {
        $response = $this->get(url('/peta'));
        $response->assertStatus(200);
        $response->assertSee('fullSearchMap');
    }

    public function test_tenant_can_checkout_room_booking_successfully(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms()->first();

        $response = $this->actingAs($tenant)->post(route('tenant.bookings.store', $kost), [
            'room_id' => $room->id,
            'start_date' => now()->addDays(2)->toDateString(),
            'duration_months' => '3', // String passed from form input
            'notes' => 'Catatan booking test',
            'is_for_other' => '0',
        ]);

        $response->assertRedirect(route('tenant.bookings.index'));
        $this->assertDatabaseHas('bookings', [
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'duration_months' => 3,
            'status' => 'pending',
            'is_for_other' => false,
        ]);
    }

    public function test_approved_booking_requires_payment_and_becomes_active_when_paid(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms()->first();

        // 1. Create booking
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(2)->addMonths(1),
            'duration_months' => 1,
            'total_amount' => 1500000,
            'status' => 'pending',
            'is_for_other' => false,
            'tenant_name' => $tenant->name,
            'tenant_phone' => $tenant->phone,
        ]);

        // 2. Owner approves booking -> status is approved
        $owner = $kost->owner;
        $approveResponse = $this->actingAs($owner)->patch(route('owner.bookings.updateStatus', $booking), [
            'status' => 'approved',
        ]);
        $approveResponse->assertRedirect();
        $this->assertEquals('approved', $booking->fresh()->status);

        // 3. Tenant views booking -> sees 'Disetujui - Menunggu Pembayaran' & payment CTA
        $detailResponse = $this->actingAs($tenant)->get(route('tenant.bookings.show', $booking));
        $detailResponse->assertStatus(200);
        $detailResponse->assertSee('Disetujui - Menunggu Pembayaran');
        $detailResponse->assertSee('Bayar Sekarang');

        // 4. Tenant triggers payment route -> redirects to Xendit Sandbox or mobile payment route
        $payResponse = $this->actingAs($tenant)->post(route('tenant.bookings.pay', $booking));
        $payResponse->assertRedirect();
        $redirectUrl = $payResponse->headers->get('Location');
        $this->assertTrue(
            str_contains($redirectUrl, 'checkout') || str_contains($redirectUrl, 'penghuni/pembayaran'),
            "Expected redirect URL to contain 'checkout' or 'penghuni/pembayaran', got: {$redirectUrl}"
        );

        // 5. Transaction is created and marked as paid -> booking status becomes active
        $transaction = Transaction::where('user_id', $tenant->id)
            ->where('type', 'booking')
            ->where('item_id', (string) $booking->id)
            ->first();
        $this->assertNotNull($transaction);

        // 6. Tenant can access dedicated mobile payment and success pages
        $mobilePayResponse = $this->actingAs($tenant)->get(route('tenant.bookings.payment', $transaction->reference_id));
        $mobilePayResponse->assertStatus(200);
        $mobilePayResponse->assertSee($transaction->reference_id);

        app(XenditService::class)->markTransactionAsPaid($transaction, 'Xendit Simulator', 'PAY-SIM-123');
        $this->assertEquals('active', $booking->fresh()->status);

        $successResponse = $this->actingAs($tenant)->get(route('tenant.bookings.payment-success', $transaction->reference_id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Kamar Kos Resmi Aktif');
    }

    public function test_rental_bill_notice_and_late_fee_calculation(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms->first();

        // Konfigurasi aturan sewa & denda
        AppSetting::set('rent_bill_notice_days', 7, 'rental', 'number');
        AppSetting::set('late_fee_enabled', true, 'rental', 'boolean');
        AppSetting::set('late_fee_grace_days', 1, 'rental', 'number');
        AppSetting::set('late_fee_daily_amount', 20000, 'rental', 'number');
        AppSetting::set('fee_booking_tenant', 5000, 'rental', 'number');

        // 1. Sewa aktif masih jauh dari jatuh tempo (H-10) -> belum aktif tagihan
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => now()->subMonth(),
            'end_date' => now()->addDays(10),
            'duration_months' => 1,
            'total_amount' => $room->price + 5000,
            'status' => 'active',
            'tenant_name' => $tenant->name,
            'tenant_phone' => $tenant->phone,
        ]);

        $this->assertFalse($booking->isDueForRenewal());
        $this->assertFalse($booking->isOverdue());
        $this->assertEquals(0, $booking->getLateFee());

        // 2. Sewa memasuki H-5 (kurang dari H-7) -> tagihan perpanjangan otomatis aktif, belum denda
        $booking->update(['end_date' => now()->addDays(5)]);
        $this->assertTrue($booking->fresh()->isDueForRenewal());
        $this->assertFalse($booking->fresh()->isOverdue());
        $this->assertEquals(0, $booking->fresh()->getLateFee());

        // 3. Sewa jatuh tempo kemarin (H+1) -> masih dalam batas toleransi (grace period 1 hari)
        $booking->update(['end_date' => now()->subDay()->startOfDay()]);
        $freshBooking = $booking->fresh();
        $this->assertTrue($freshBooking->isDueForRenewal());
        $this->assertTrue($freshBooking->isOverdue());
        $this->assertEquals(1, $freshBooking->getOverdueDays());
        $this->assertEquals(0, $freshBooking->getLateFeeDays());
        $this->assertEquals(0, $freshBooking->getLateFee());

        // 4. Sewa jatuh tempo 3 hari lalu (H+3) -> telah melewati H+1 (2 hari denda x 20.000 = 40.000)
        $booking->update(['end_date' => now()->subDays(3)->startOfDay()]);
        $lateBooking = $booking->fresh();
        $this->assertTrue($lateBooking->isOverdue());
        $this->assertEquals(3, $lateBooking->getOverdueDays());
        $this->assertEquals(2, $lateBooking->getLateFeeDays());
        $this->assertEquals(40000, $lateBooking->getLateFee());

        // Rincian total perpanjangan 1 bulan
        $calc = $lateBooking->calculateRenewalTotal(1);
        $this->assertEquals((float) $room->price, (float) $calc['room_price']);
        $this->assertEquals(40000, (float) $calc['late_fee']);
        $this->assertEquals(5000, (float) $calc['fee_booking_tenant']);
        $this->assertEquals((float) ($room->price + 5000 + 40000), (float) $calc['grand_total']);
    }

    public function test_tenant_can_pay_and_extend_lease_via_renewal(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms->first();

        // Booking aktif pada periode jatuh tempo
        $initialEndDate = now()->addDays(3)->startOfDay();
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => now()->subMonth(),
            'end_date' => $initialEndDate,
            'duration_months' => 1,
            'total_amount' => $room->price + 5000,
            'status' => 'active',
            'tenant_name' => $tenant->name,
            'tenant_phone' => $tenant->phone,
        ]);

        // Detail halaman menampilkan tagihan perpanjangan
        $viewResponse = $this->actingAs($tenant)->get(route('tenant.bookings.show', $booking));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Tagihan Periode Berikutnya');
        $viewResponse->assertSee('Bayar & Perpanjang Sewa', false);

        // Tenant melakukan submit perpanjangan 2 bulan
        $renewResponse = $this->actingAs($tenant)->post(route('tenant.bookings.renew', $booking), [
            'duration_months' => 2,
        ]);
        $renewResponse->assertRedirect();

        $transaction = Transaction::where('user_id', $tenant->id)
            ->where('type', 'renewal')
            ->where('item_id', (string) $booking->id)
            ->first();

        $this->assertNotNull($transaction);
        $this->assertEquals('pending', $transaction->status);
        $this->assertEquals(2, $transaction->payload['duration_months']);

        // Pelunasan transaksi renewal via XenditService
        app(XenditService::class)->markTransactionAsPaid($transaction, 'Xendit Simulator', 'PAY-RENEWAL-123');

        $booking->refresh();
        $this->assertEquals('active', $booking->status);
        $this->assertEquals(3, $booking->duration_months); // 1 bulan awal + 2 bulan perpanjangan
        $this->assertEquals($initialEndDate->copy()->addMonths(2)->format('Y-m-d'), $booking->end_date->format('Y-m-d'));
    }

    public function test_tenant_can_view_wallet_and_top_up_via_xendit(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $tenant->update(['wallet_balance' => 0]);

        // 1. Akses halaman dompet
        $walletResponse = $this->actingAs($tenant)->get(route('tenant.wallet.index'));
        $walletResponse->assertStatus(200);
        $walletResponse->assertSee('SevenKost Wallet');
        $walletResponse->assertSee('Total Saldo Dompet');

        // 2. Ajukan top up Rp 500.000
        $topupResponse = $this->actingAs($tenant)->post(route('tenant.wallet.topup'), [
            'amount' => 500000,
        ]);
        $topupResponse->assertRedirect();

        $transaction = Transaction::where('user_id', $tenant->id)
            ->where('type', 'topup')
            ->latest('id')
            ->first();
        $this->assertNotNull($transaction);
        $this->assertEquals(500000.00, (float) $transaction->amount);
        $this->assertEquals('pending', $transaction->status);

        // 3. Akses halaman checkout top up
        $checkoutResponse = $this->actingAs($tenant)->get(route('tenant.wallet.payment', $transaction->reference_id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Nominal Top Up Saldo');

        // 4. Simulasi pelunasan top up via XenditService
        app(XenditService::class)->markTransactionAsPaid($transaction, 'Xendit Simulator', 'TOPUP-SIM-123');

        $this->assertEquals(500000.00, (float) $tenant->fresh()->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $tenant->id,
            'type' => 'topup',
            'amount' => 500000.00,
            'status' => 'success',
        ]);
    }

    public function test_tenant_can_withdraw_wallet_balance(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $tenant->update(['wallet_balance' => 300000]);

        // Tarik saldo Rp 100.000 ke rekening BCA
        $withdrawResponse = $this->actingAs($tenant)->post(route('tenant.wallet.withdraw'), [
            'bank_name' => 'BCA',
            'account_number' => '8880123456',
            'account_holder' => $tenant->name,
            'amount' => 100000,
        ]);
        $withdrawResponse->assertRedirect(route('tenant.wallet.index'));

        // Saldo berkurang menjadi 200.000
        $this->assertEquals(200000.00, (float) $tenant->fresh()->wallet_balance);
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $tenant->id,
            'type' => 'withdrawal',
            'amount' => 100000.00,
            'bank_name' => 'BCA',
            'account_number' => '8880123456',
            'status' => 'success',
        ]);
    }

    public function test_tenant_can_pay_booking_and_renewal_with_wallet_balance(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms->first();

        // Berikan saldo dompet cukup
        $tenant->update(['wallet_balance' => 5000000]);

        // 1. Buat booking approved
        $booking = Booking::create([
            'user_id' => $tenant->id,
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'duration_months' => 1,
            'total_amount' => 1500000,
            'status' => 'approved',
            'tenant_name' => $tenant->name,
            'tenant_phone' => $tenant->phone,
        ]);

        // Bayar sewa awal menggunakan saldo dompet
        $payWalletResponse = $this->actingAs($tenant)->post(route('tenant.bookings.pay-wallet', $booking));
        $payWalletResponse->assertRedirect(route('tenant.bookings.show', $booking));

        $this->assertEquals('active', $booking->fresh()->status);
        $this->assertEquals(3500000.00, (float) $tenant->fresh()->wallet_balance); // 5jt - 1.5jt = 3.5jt
        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $tenant->id,
            'type' => 'payment',
            'amount' => 1500000.00,
            'status' => 'success',
        ]);

        // 2. Set booking jatuh tempo dalam 3 hari untuk perpanjangan
        $booking->update(['end_date' => now()->addDays(3)->startOfDay()]);
        $initialEndDate = $booking->fresh()->end_date;

        // Bayar perpanjangan 1 bulan menggunakan saldo dompet
        $renewWalletResponse = $this->actingAs($tenant)->post(route('tenant.bookings.renew-wallet', $booking), [
            'duration_months' => 1,
        ]);
        $renewWalletResponse->assertRedirect(route('tenant.bookings.show', $booking));

        $calc = $booking->calculateRenewalTotal(1);
        $expectedRemainder = 3500000.00 - (float) $calc['grand_total'];
        $this->assertEquals($expectedRemainder, (float) $tenant->fresh()->wallet_balance);
        $this->assertEquals(2, $booking->fresh()->duration_months);
        $this->assertEquals($initialEndDate->copy()->addMonth()->format('Y-m-d'), $booking->fresh()->end_date->format('Y-m-d'));
    }

    public function test_tenant_header_has_hamburger_menu_and_essential_bottom_tabs(): void
    {
        $tenant = $this->getOrCreateTenantUser();

        $response = $this->actingAs($tenant)->get(route('tenant.dashboard'));
        $response->assertStatus(200);

        // Header icon garis 3 (hamburger)
        $response->assertSee('Menu Semua Fitur');

        // Drawer menu semua fitur
        $response->assertSee('Semua Fitur Utama');
        $response->assertSee('Dompet & Saldo (Top Up / Tarik)', false);
        $response->assertSee('Peta Interaktif Kos');
        $response->assertSee('Marketplace Anak Kos');

        // Bottom navbar tab penting
        $response->assertSee('Beranda');
        $response->assertSee('Cari Kos');
        $response->assertSee('Kos Saya');
        $response->assertSee('Dompet');
        $response->assertSee('Akun');
    }

    public function test_tenant_profile_renders_mobile_tenant_layout_and_can_update_phone(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $tenant->update(['wallet_balance' => 150000]);

        $response = $this->actingAs($tenant)->get(route('profile.edit'));
        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
        $response->assertSee('Informasi Data Diri');
        $response->assertDontSee('Ganti Kata Sandi');
        $response->assertDontSee('Zona Berbahaya');

        // Test update phone & name
        $updateResponse = $this->actingAs($tenant)->patch(route('profile.update'), [
            'name' => 'Penghuni Updated',
            'email' => $tenant->email,
            'phone' => '081299998888',
        ]);

        $updateResponse->assertSessionHasNoErrors();
        $updateResponse->assertRedirect(route('profile.edit'));

        $this->assertEquals('Penghuni Updated', $tenant->fresh()->name);
        $this->assertEquals('081299998888', $tenant->fresh()->phone);
    }

    public function test_xendit_settled_invoice_is_auto_synced_and_activates_booking(): void
    {
        $refId = 'BK-TEST-SYNC-'.uniqid();

        Http::fake([
            'https://api.xendit.co/v2/invoices/*' => Http::response([
                'id' => 'xnd_real_invoice_123',
                'external_id' => $refId,
                'status' => 'SETTLED',
                'amount' => 1500000,
                'payment_channel' => 'BRI',
            ], 200),
        ]);

        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms()->first();

        $booking = Booking::create([
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'user_id' => $tenant->id,
            'start_date' => now()->toDateString(),
            'end_date' => now()->addMonth()->toDateString(),
            'duration_months' => 1,
            'total_price' => 1500000,
            'status' => 'approved',
        ]);

        $transaction = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => $refId,
            'type' => 'booking',
            'item_id' => (string) $booking->id,
            'title' => 'Sewa Kos Test',
            'amount' => 1500000,
            'payment_channel' => 'all',
            'status' => 'pending',
            'xendit_invoice_id' => 'xnd_real_invoice_123',
        ]);

        // When tenant opens booking show or polls payments.status
        $response = $this->actingAs($tenant)->get(route('tenant.bookings.show', $booking));
        $response->assertStatus(200);

        // Booking should now be active and transaction paid
        $this->assertEquals('active', $booking->fresh()->status);
        $this->assertEquals('paid', $transaction->fresh()->status);
        $this->assertEquals('BRI', $transaction->fresh()->payment_channel);
    }

    public function test_tenant_dashboard_displays_wallet_and_due_renewal_bill(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $tenant->update(['wallet_balance' => 250000]);

        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms()->first();

        // Booking active expiring in 3 days (due for renewal)
        $booking = Booking::create([
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'user_id' => $tenant->id,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->addDays(3)->toDateString(),
            'duration_months' => 1,
            'total_price' => 1500000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('250.000');
        $response->assertSee('Tagihan Perpanjangan Sewa (H-7)');
    }

    public function test_tenant_can_terminate_lease_checkout(): void
    {
        $tenant = $this->getOrCreateTenantUser();
        $kost = $this->getOrCreateTestKost();
        $room = $kost->rooms()->first();
        $initialAvailable = $room->available_rooms;

        $booking = Booking::create([
            'kost_id' => $kost->id,
            'room_id' => $room->id,
            'user_id' => $tenant->id,
            'start_date' => now()->subMonth()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'duration_months' => 1,
            'total_price' => 1500000,
            'status' => 'active',
        ]);

        $response = $this->actingAs($tenant)->post(route('tenant.bookings.terminate', $booking), [
            'reason' => 'Selesai masa studi',
        ]);

        $response->assertRedirect(route('tenant.dashboard'));
        $this->assertEquals('completed', $booking->fresh()->status);
        $this->assertEquals($initialAvailable + 1, $room->fresh()->available_rooms);
        $this->assertStringContainsString('Selesai masa studi', $booking->fresh()->notes);
    }
}
