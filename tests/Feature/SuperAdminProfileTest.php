<?php

namespace Tests\Feature;

use App\Filament\Superadmin\Pages\EditProfile;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperAdminProfileTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
    }

    public function test_superadmin_can_access_profile_page(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)
            ->get('/superadmin/profile');

        $response->assertSuccessful();
        $response->assertSee('seven-gradient-card', false);
        $response->assertSee('Pengaturan Akun');
    }

    public function test_non_superadmin_cannot_access_profile_page(): void
    {
        $owner = User::factory()->create([
            'status' => 'active',
        ]);
        $owner->assignRole('owner');

        $response = $this->actingAs($owner)
            ->get('/superadmin/profile');

        $response->assertForbidden();
    }

    public function test_superadmin_can_update_profile_information(): void
    {
        $superadmin = User::factory()->create([
            'name' => 'Admin Lama',
            'email' => 'admin.lama@example.com',
            'phone' => '0811111111',
            'password' => Hash::make('password123'),
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        Livewire::actingAs($superadmin)
            ->test(EditProfile::class)
            ->fillForm([
                'name' => 'Admin Baru Seven Kost',
                'phone' => '0822222222',
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $superadmin->refresh();
        $this->assertSame('Admin Baru Seven Kost', $superadmin->name);
        $this->assertSame('0822222222', $superadmin->phone);
    }

    public function test_user_filament_avatar_url(): void
    {
        $userWithAvatar = User::factory()->create([
            'avatar' => 'avatars/sample.jpg',
            'status' => 'active',
        ]);

        $this->assertStringContainsString('avatars/sample.jpg', $userWithAvatar->getFilamentAvatarUrl() ?? '');

        $userWithoutAvatar = User::factory()->create([
            'avatar' => null,
            'status' => 'active',
        ]);

        $this->assertNull($userWithoutAvatar->getFilamentAvatarUrl());
    }
}
