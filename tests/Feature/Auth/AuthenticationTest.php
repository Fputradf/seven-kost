<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }

    public function test_superadmin_redirected_to_superadmin_panel_after_login(): void
    {
        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('superadmin');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/superadmin');
    }

    public function test_pemilik_redirected_to_owner_dashboard_after_login(): void
    {
        Role::firstOrCreate(['name' => 'pemilik', 'guard_name' => 'web']);
        $user = User::factory()->create();
        $user->assignRole('pemilik');

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('owner.dashboard', absolute: false));
    }

    public function test_superadmin_and_admin_login_urls_redirect_to_unified_login_page(): void
    {
        $this->get('/superadmin/login')->assertRedirect('/login');
        $this->get('/admin/login')->assertRedirect('/login');
    }
}
