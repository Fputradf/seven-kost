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

    public function test_google_callback_registers_new_user(): void
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

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'newgoogletester@example.com',
            'google_id' => 'google-unique-id-12345',
            'name' => 'Google Tester',
        ]);
    }

    public function test_google_callback_logs_in_existing_user_by_google_id(): void
    {
        $existingUser = User::factory()->create([
            'google_id' => 'google-existing-id-999',
            'email' => 'existing_google@example.com',
        ]);

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

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser);
    }

    public function test_google_callback_links_existing_user_by_email(): void
    {
        $existingUser = User::factory()->create([
            'google_id' => null,
            'email' => 'manual_user@example.com',
        ]);

        $socialiteUser = Mockery::mock(SocialiteUser::class);
        $socialiteUser->shouldReceive('getId')->andReturn('google-link-id-777');
        $socialiteUser->shouldReceive('getName')->andReturn($existingUser->name);
        $socialiteUser->shouldReceive('getNickname')->andReturn(null);
        $socialiteUser->shouldReceive('getEmail')->andReturn('manual_user@example.com');
        $socialiteUser->shouldReceive('getAvatar')->andReturn('https://lh3.googleusercontent.com/new-avatar');

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($socialiteUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);

        $response = $this->get(route('auth.google.callback'));

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticatedAs($existingUser);

        $this->assertDatabaseHas('users', [
            'id' => $existingUser->id,
            'google_id' => 'google-link-id-777',
        ]);
    }
}
