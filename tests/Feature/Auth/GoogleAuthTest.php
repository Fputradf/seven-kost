<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    public function test_google_redirect_returns_redirect(): void
    {
        $response = $this->get(route('auth.google'));

        $response->assertRedirect();
        $this->assertStringContainsString('accounts.google.com', $response->getTargetUrl());
    }

    public function test_google_callback_registers_new_user_and_redirects_to_onboarding_role(): void
    {
        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google-unique-id-12345');
        $socialiteUser->shouldReceive('getName')->andReturn('Google Tester');
        $socialiteUser->shouldReceive('getNickname')->andReturn('googletester');
        $socialiteUser->shouldReceive('getEmail')->andReturn('newgoogletester@example.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/test-avatar');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        // Pengguna baru diarahkan ke halaman pemilihan peran
        $response->assertRedirect(route('onboarding.role'));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'newgoogletester@example.com',
            'google_id' => 'google-unique-id-12345',
            'name' => 'Google Tester',
        ]);
    }

    public function test_onboarding_role_screen_can_be_rendered_for_user_without_role(): void
    {
        $user = User::factory()->create([
            'email' => 'unassigned_role@example.com',
        ]);

        $response = $this->actingAs($user)->get(route('onboarding.role'));

        $response->assertStatus(200);
        $response->assertSee('Selamat Datang di SevenKost');
        $response->assertSee('Pencari / Penghuni Kos');
        $response->assertSee('Pemilik / Pengelola Kos');
        $response->assertSee('Mitra Layanan Kos');
    }

    public function test_onboarding_role_can_be_selected_and_stored(): void
    {
        $user = User::factory()->create([
            'email' => 'choosing_role@example.com',
        ]);

        $response = $this->actingAs($user)->post(route('onboarding.role.store'), [
            'role' => 'pemilik',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue($user->fresh()->hasRole('pemilik'));
    }

    public function test_onboarding_role_redirects_if_user_already_has_role(): void
    {
        $user = User::factory()->create([
            'email' => 'has_role@example.com',
        ]);
        $user->assignRole('penghuni');

        $response = $this->actingAs($user)->get(route('onboarding.role'));

        $response->assertRedirect(route('dashboard'));
    }

    public function test_google_callback_logs_in_existing_user_by_google_id_straight_to_dashboard(): void
    {
        $existingUser = User::factory()->create([
            'google_id' => 'google-existing-id-999',
            'email' => 'existing_google@example.com',
        ]);
        $existingUser->assignRole('penghuni');

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google-existing-id-999');
        $socialiteUser->shouldReceive('getName')->andReturn($existingUser->name);
        $socialiteUser->shouldReceive('getNickname')->andReturn(null);
        $socialiteUser->shouldReceive('getEmail')->andReturn('existing_google@example.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn(null);

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        // Pengguna lama yang sudah punya peran langsung ke dashboard
        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser);
    }
}
