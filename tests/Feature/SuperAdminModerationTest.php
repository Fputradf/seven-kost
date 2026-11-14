<?php

namespace Tests\Feature;

use App\Models\MarketplaceItem;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperAdminModerationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'penghuni', 'guard_name' => 'web']);
    }

    public function test_superadmin_can_access_filament_panel(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)
            ->get('/superadmin');

        $response->assertSuccessful();
        $response->assertSee('Manajemen Kos');
        $response->assertSee('Laporan Pelanggaran');
        $response->assertSee('Manajemen Marketplace');
        $response->assertSee('Manajemen Iklan Kos');
        $response->assertSee('Banner Beranda');
        $response->assertDontSee('Tarif Paket Iklan');
        $response->assertSee('Data Pengajuan &amp; Sewa', false);
        $response->assertSee('Manajemen Pengguna');
        $response->assertSee('Pengaturan Aplikasi');

        $this->actingAs($superadmin)
            ->get('/superadmin/users')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/kosts')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/reports')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/marketplace-items')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/ads-promotions')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/banners')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/ads-packages')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/bookings')
            ->assertSuccessful();

        $this->actingAs($superadmin)
            ->get('/superadmin/pengaturan-aplikasi')
            ->assertSuccessful();
    }

    public function test_regular_user_cannot_access_filament_panel(): void
    {
        $user = User::factory()->create([
            'status' => 'active',
        ]);
        $user->assignRole('penghuni');

        $this->actingAs($user)
            ->get('/superadmin')
            ->assertForbidden();
    }

    public function test_superadmin_bypasses_all_gate_checks(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $this->actingAs($superadmin);

        $this->assertTrue(Gate::allows('any_random_nonexistent_permission'));
    }

    public function test_banned_user_is_logged_out_by_middleware(): void
    {
        $user = User::factory()->create([
            'status' => 'banned',
            'moderation_reason' => 'Melakukan penipuan sewa kamar.',
        ]);
        $user->assignRole('penghuni');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_suspended_user_under_active_period_is_logged_out(): void
    {
        $user = User::factory()->create([
            'status' => 'suspended',
            'suspended_until' => now()->addDays(5),
            'moderation_reason' => 'Perilaku spam di marketplace.',
        ]);
        $user->assignRole('penghuni');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect('/login');
        $response->assertSessionHas('error');
        $this->assertGuest();
    }

    public function test_suspended_user_with_expired_suspension_is_automatically_reactivated(): void
    {
        $user = User::factory()->create([
            'status' => 'suspended',
            'suspended_until' => now()->subDay(),
            'moderation_reason' => 'Suspend sementara kemarin.',
        ]);
        $user->assignRole('penghuni');

        $this->actingAs($user)->get('/dashboard');

        $user->refresh();
        $this->assertEquals('active', $user->status);
        $this->assertNull($user->suspended_until);
        $this->assertNull($user->moderation_reason);
    }

    public function test_superadmin_login_redirects_to_admin_panel(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
            'password' => bcrypt('password'),
        ]);
        $superadmin->assignRole('superadmin');

        $response = $this->post('/login', [
            'email' => $superadmin->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/superadmin');
    }

    public function test_superadmin_accessing_dashboard_redirects_to_admin_panel(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)->get('/dashboard');

        $response->assertRedirect('/superadmin');
    }

    public function test_superadmin_can_access_edit_marketplace_item_page(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $seller = User::factory()->create();
        $item = MarketplaceItem::create([
            'user_id' => $seller->id,
            'title' => 'Kasur Busa 120x200 Premium',
            'category' => 'kasur',
            'price' => 450000,
            'condition' => 'bekas_baik',
            'city' => 'Depok',
            'meetup_location' => 'Dekat Kampus UI',
            'status' => 'active',
            'description' => 'Kasur busa empuk dan mulus terawat.',
        ]);

        $response = $this->actingAs($superadmin)
            ->get("/superadmin/marketplace-items/{$item->id}/edit");

        $response->assertSuccessful();
        $response->assertSee('Edit Barang Marketplace');
        $response->assertSee('Informasi Barang Marketplace');
        $response->assertSee('Kasur Busa 120x200 Premium');
        $response->assertDontSee('Kelola data produk barang kos yang dijual');
    }
}
