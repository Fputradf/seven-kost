<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Report;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class OwnerDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected User $owner;

    protected User $tenant;

    protected Kost $kost;

    protected Room $room;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'pemilik', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'penghuni', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        $province = Province::firstOrCreate(
            ['id' => '31'],
            ['name' => 'DKI Jakarta', 'latitude' => -6.2088, 'longitude' => 106.8456]
        );

        $regency = Regency::firstOrCreate(
            ['id' => '3171'],
            ['province_id' => '31', 'name' => 'Jakarta Selatan', 'type' => 'Kota', 'latitude' => -6.2615, 'longitude' => 106.8106]
        );

        $this->owner = User::factory()->create([
            'name' => 'Pak Haji Pemilik',
            'email' => 'pemilik_'.uniqid().'@sevenkost.com',
            'phone' => '081234567890',
            'status' => 'active',
        ]);
        $this->owner->assignRole('pemilik');

        $this->tenant = User::factory()->create([
            'name' => 'Anak Kos Rajin',
            'email' => 'penghuni_'.uniqid().'@sevenkost.com',
            'phone' => '089876543210',
            'status' => 'active',
        ]);
        $this->tenant->assignRole('penghuni');

        $this->kost = Kost::create([
            'user_id' => $this->owner->id,
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'name' => 'Kos Barokah Sejahtera',
            'slug' => 'kos-barokah-sejahtera-'.uniqid(),
            'type' => 'campur',
            'price_start' => 1500000,
            'rent_type' => 'bulanan',
            'address' => 'Jl. Tebet Barat No. 7, Jakarta Selatan',
            'verification_status' => 'verified',
            'is_available' => true,
        ]);

        $this->room = Room::create([
            'kost_id' => $this->kost->id,
            'name' => 'Deluxe AC Single',
            'price' => 1500000,
            'total_rooms' => 10,
            'available_rooms' => 6,
            'room_size' => '3x4 m',
        ]);
    }

    public function test_owner_dashboard_can_be_accessed_by_pemilik(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('owner.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Kos Barokah Sejahtera');
        $response->assertSee('Tingkat Okupansi');
        $response->assertSee('Estimasi Omzet Bulanan');
        $response->assertSee('40%'); // 10 total, 6 available => 4 occupied (40%)
    }

    public function test_owner_dashboard_forbidden_for_regular_tenant_without_kosts(): void
    {
        $response = $this->actingAs($this->tenant)
            ->get(route('owner.dashboard'));

        $response->assertStatus(403);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get(route('owner.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_dashboard_redirects_pemilik_to_owner_dashboard(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('dashboard'));

        $response->assertRedirect(route('owner.dashboard'));
    }

    public function test_occupancy_metrics_and_monthly_revenue_calculation(): void
    {
        // Buat 1 booking aktif
        Booking::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $this->kost->id,
            'room_id' => $this->room->id,
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->startOfMonth()->addMonths(3),
            'duration_months' => 3,
            'total_amount' => 4500000,
            'status' => 'approved',
            'tenant_name' => $this->tenant->name,
            'tenant_phone' => $this->tenant->phone,
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Rp 1.500.000'); // Monthly revenue from active booking
        $response->assertSee('1 orang'); // 1 active tenant
    }

    public function test_pending_booking_appears_in_dashboard_and_can_be_approved(): void
    {
        $booking = Booking::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $this->kost->id,
            'room_id' => $this->room->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(2)->addMonths(1),
            'duration_months' => 1,
            'total_amount' => 1500000,
            'status' => 'pending',
            'tenant_name' => 'Budi Santoso',
            'tenant_phone' => '081234567899',
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('Pengajuan Butuh Tindakan');

        // Approve the booking
        $approveResponse = $this->actingAs($this->owner)
            ->patch(route('owner.bookings.updateStatus', $booking), [
                'status' => 'approved',
            ]);

        $approveResponse->assertRedirect();
        $this->assertEquals('approved', $booking->fresh()->status);
        // Room available rooms should decrement from 6 to 5
        $this->assertEquals(5, $this->room->fresh()->available_rooms);
    }

    public function test_booking_rejection_from_pending_does_not_increment_available_rooms(): void
    {
        $booking = Booking::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $this->kost->id,
            'room_id' => $this->room->id,
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(2)->addMonths(1),
            'duration_months' => 1,
            'total_amount' => 1500000,
            'status' => 'pending',
            'tenant_name' => 'Calon Ditolak',
            'tenant_phone' => '081234567891',
        ]);

        $initialAvailable = $this->room->available_rooms; // 6

        $this->actingAs($this->owner)
            ->patch(route('owner.bookings.updateStatus', $booking), [
                'status' => 'rejected',
                'notes' => 'Kamar sedang dalam proses renovasi.',
            ]);

        $this->assertEquals('rejected', $booking->fresh()->status);
        $this->assertEquals('Kamar sedang dalam proses renovasi.', $booking->fresh()->notes);
        // Should remain 6 (NOT incremented!)
        $this->assertEquals($initialAvailable, $this->room->fresh()->available_rooms);
    }

    public function test_booking_completion_increments_available_rooms(): void
    {
        $booking = Booking::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $this->kost->id,
            'room_id' => $this->room->id,
            'start_date' => now()->subMonths(2),
            'end_date' => now()->today(),
            'duration_months' => 2,
            'total_amount' => 3000000,
            'status' => 'approved',
            'tenant_name' => 'Penghuni Selesai',
            'tenant_phone' => '081234567892',
        ]);

        $initialAvailable = $this->room->available_rooms; // 6

        $this->actingAs($this->owner)
            ->patch(route('owner.bookings.updateStatus', $booking), [
                'status' => 'completed',
            ]);

        $this->assertEquals('completed', $booking->fresh()->status);
        // Increments from 6 to 7
        $this->assertEquals($initialAvailable + 1, $this->room->fresh()->available_rooms);
    }

    public function test_owner_can_add_and_update_room(): void
    {
        // Add new room
        $createResponse = $this->actingAs($this->owner)
            ->post(route('owner.rooms.store', $this->kost), [
                'name' => 'Superior Double Bed',
                'price' => 2000000,
                'total_rooms' => 5,
                'available_rooms' => 3,
                'room_size' => '4x5 m',
            ]);

        $createResponse->assertRedirect();
        $this->assertDatabaseHas('rooms', [
            'kost_id' => $this->kost->id,
            'name' => 'Superior Double Bed',
            'price' => 2000000,
            'total_rooms' => 5,
            'available_rooms' => 3,
        ]);

        $newRoom = Room::where('kost_id', $this->kost->id)->where('name', 'Superior Double Bed')->first();

        // Update room
        $updateResponse = $this->actingAs($this->owner)
            ->put(route('owner.rooms.update', [$this->kost, $newRoom]), [
                'name' => 'Superior Double Bed Deluxe',
                'price' => 2200000,
                'total_rooms' => 5,
                'available_rooms' => 2,
                'room_size' => '4x5 m',
            ]);

        $updateResponse->assertRedirect();
        $this->assertDatabaseHas('rooms', [
            'id' => $newRoom->id,
            'name' => 'Superior Double Bed Deluxe',
            'price' => 2200000,
            'available_rooms' => 2,
        ]);
    }

    public function test_owner_promotions_page_can_be_accessed(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('owner.promosi.index'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Promosi');
        $response->assertSee('Akun VIP Pemilik');
        $response->assertSee('Banner Komersial Beranda');
        $response->assertSee('Formulir Penayangan Banner Beranda');
        $response->assertSee('Saluran Pembayaran Xendit Gateway');
        $response->assertSee('QRIS Instan');
        $response->assertSee('Bayar via Xendit & Ajukan Review Admin', false);

        // Memastikan tidak ada pengalihan keluar ke home page paket iklan
        $response->assertDontSee(route('ads.packages.index', ['tab' => 'banner']));
        $response->assertDontSee(route('ads.packages.index', ['tab' => 'kost']));
    }

    public function test_owner_can_submit_banner_order_directly_from_form_to_xendit(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->owner)->post(route('ads.banner.order'), [
            'brand_name' => 'Laundry Mitra Pemilik',
            'contact_email' => 'laundry@pemilik.com',
            'contact_phone' => '081234567899',
            'duration_days' => 90,
            'target_url' => 'https://laundrymitra.id',
            'payment_channel' => 'bca_va',
            'banner_image' => UploadedFile::fake()->image('banner_brand.jpg', 1200, 400),
        ]);

        $response->assertStatus(302);
        $this->assertStringContainsString('/pembayaran/checkout/', $response->headers->get('Location'));

        $this->assertDatabaseHas('banners', [
            'user_id' => $this->owner->id,
            'advertiser_name' => 'Laundry Mitra Pemilik',
            'payment_status' => 'pending',
            'payment_channel' => 'bca_va',
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $this->owner->id,
            'type' => 'banner',
            'payment_channel' => 'bca_va',
            'status' => 'pending',
        ]);
    }

    public function test_owner_tenants_page_renders_with_enhanced_typography_and_metrics(): void
    {
        $response = $this->actingAs($this->owner)
            ->get(route('owner.tenants.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Penyewa & Pengajuan Booking', false);
        $response->assertSee('Total Pengajuan');
        $response->assertSee('Menunggu Konfirmasi');
        $response->assertSee('Sedang Menyewa');
        $response->assertSee('Riwayat Selesai');
    }

    public function test_owner_can_view_complaints_list_and_metrics(): void
    {
        Report::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $this->kost->id,
            'reason' => 'Air kamar mandi mati',
            'details' => 'Sejak pagi air di lantai 2 mati total mohon diperbaiki.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)
            ->get(route('owner.complaints.index'));

        $response->assertStatus(200);
        $response->assertSee('Daftar Keluhan & Laporan Kos', false);
        $response->assertSee('Air kamar mandi mati');
        $response->assertSee('Sejak pagi air di lantai 2 mati total');
        $response->assertSee('Kos Barokah Sejahtera');
        $response->assertSee('Menunggu Tindakan');
    }

    public function test_owner_can_update_complaint_status_and_notes(): void
    {
        $report = Report::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $this->kost->id,
            'reason' => 'Lampu koridor padam',
            'details' => 'Lampu koridor depan kamar 4 padam.',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)
            ->patch(route('owner.complaints.updateStatus', $report), [
                'status' => 'reviewed',
                'admin_notes' => 'Sedang dibelikan lampu pengganti oleh penjaga kos.',
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('reports', [
            'id' => $report->id,
            'status' => 'reviewed',
            'admin_notes' => 'Sedang dibelikan lampu pengganti oleh penjaga kos.',
        ]);
    }

    public function test_owner_cannot_update_complaint_from_other_owners_kost(): void
    {
        $otherOwner = User::factory()->create(['status' => 'active']);
        $otherOwner->assignRole('pemilik');

        $otherKost = Kost::create([
            'user_id' => $otherOwner->id,
            'province_id' => $this->kost->province_id,
            'regency_id' => $this->kost->regency_id,
            'name' => 'Kos Pemilik Lain',
            'slug' => 'kos-pemilik-lain-'.uniqid(),
            'type' => 'putra',
            'price_start' => 1000000,
            'rent_type' => 'bulanan',
            'address' => 'Jl. Lain',
            'verification_status' => 'verified',
            'is_available' => true,
        ]);

        $report = Report::create([
            'user_id' => $this->tenant->id,
            'kost_id' => $otherKost->id,
            'reason' => 'Keluhan Kos Lain',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->owner)
            ->patch(route('owner.complaints.updateStatus', $report), [
                'status' => 'resolved',
            ]);

        $response->assertStatus(403);
    }
}
