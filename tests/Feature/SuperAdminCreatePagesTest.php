<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperAdminCreatePagesTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
    }

    public function test_superadmin_can_access_all_create_pages(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $routes = [
            '/superadmin',
            '/superadmin/pengaturan-aplikasi',
            '/superadmin/users/create',
            '/superadmin/kosts/create',
            '/superadmin/reports/create',
            '/superadmin/marketplace-items/create',
            '/superadmin/ads-promotions/create',
            '/superadmin/banners/create',
            '/superadmin/ads-packages/create',
            '/superadmin/bookings/create',
        ];

        foreach ($routes as $route) {
            $response = $this->actingAs($superadmin)->get($route);
            $this->assertTrue(
                $response->isSuccessful(),
                "Failed asserting that {$route} returned successful status. Got {$response->getStatusCode()}"
            );
        }
    }
}
