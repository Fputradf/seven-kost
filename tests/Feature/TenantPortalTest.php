<?php

namespace Tests\Feature;

use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\User;
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

        Room::firstOrCreate(
            ['kost_id' => $kost->id, 'name' => 'Tipe Standard A'],
            [
                'price' => 1500000,
                'total_rooms' => 5,
                'available_rooms' => 3,
                'room_size' => '3x4 m',
            ]
        );

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
}
