<?php

namespace Tests\Feature;

use App\Models\KycVerification;
use App\Models\Province;
use App\Models\Regency;
use App\Models\ServicePartner;
use App\Models\ServicePartnerPackage;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerPackageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function createVerifiedPartnerUser(string $category = 'laundry', string $businessName = 'Seven Laundry Melati'): array
    {
        $user = User::factory()->create(['email' => 'mitra.pkg.'.uniqid().'@test.com']);
        $user->assignRole('mitra');

        KycVerification::create([
            'user_id' => $user->id,
            'role' => 'mitra',
            'nik' => fake()->unique()->numerify('################'),
            'full_name' => $user->name,
            'ktp_path' => 'kyc/ktp/dummy.jpg',
            'selfie_path' => 'kyc/selfie/dummy.jpg',
            'status' => 'verified',
            'submitted_at' => now(),
            'reviewed_at' => now(),
        ]);

        $prov = Province::firstOrCreate(['id' => '31'], ['name' => 'DKI Jakarta']);
        $reg = Regency::firstOrCreate(['id' => '3171'], ['province_id' => $prov->id, 'name' => 'Kota Jakarta Selatan']);

        $partner = ServicePartner::create([
            'user_id' => $user->id,
            'business_name' => $businessName,
            'category' => $category,
            'province_id' => $prov->id,
            'regency_id' => $reg->id,
            'address' => 'Jl. Fatmawati No. 45, Jakarta Selatan',
            'phone' => '081234567890',
            'is_open' => true,
            'verification_status' => 'verified',
        ]);

        return [$user, $partner];
    }

    public function test_partner_can_create_update_toggle_and_delete_package(): void
    {
        [$user, $partner] = $this->createVerifiedPartnerUser();

        // 1. Create package
        $this->actingAs($user)->post(route('partner.packages.store'), [
            'name' => 'Cuci Lipat Wangi 1 Hari',
            'price' => 9500,
            'unit' => '/kg',
            'description' => 'Termasuk pewangi premium',
        ])->assertRedirect(route('partner.profile.index', ['tab' => 'profile']));

        $this->assertDatabaseHas('service_partner_packages', [
            'service_partner_id' => $partner->id,
            'name' => 'Cuci Lipat Wangi 1 Hari',
            'price' => 9500,
            'is_active' => true,
        ]);

        $package = ServicePartnerPackage::where('service_partner_id', $partner->id)->firstOrFail();

        // 2. View in profile
        $this->actingAs($user)->get(route('partner.profile.index', ['tab' => 'profile']))
            ->assertStatus(200)
            ->assertSee('Cuci Lipat Wangi 1 Hari');

        // 3. Update package
        $this->actingAs($user)->put(route('partner.packages.update', $package), [
            'name' => 'Cuci Setrika Kilat',
            'price' => 14000,
            'unit' => '/kg',
            'description' => 'Selesai dalam 12 jam',
        ])->assertRedirect(route('partner.profile.index', ['tab' => 'profile']));

        $this->assertDatabaseHas('service_partner_packages', [
            'id' => $package->id,
            'name' => 'Cuci Setrika Kilat',
            'price' => 14000,
            'is_active' => true,
        ]);

        // 4. Catalog displays custom package
        $this->get('/layanan?kota=Jakarta')
            ->assertStatus(200)
            ->assertSee('Cuci Setrika Kilat')
            ->assertSee('14.000');

        // 5. Delete package
        $this->actingAs($user)->delete(route('partner.packages.destroy', $package))
            ->assertRedirect(route('partner.profile.index', ['tab' => 'profile']));

        $this->assertDatabaseMissing('service_partner_packages', ['id' => $package->id]);
    }

    public function test_partner_cannot_modify_another_partners_package(): void
    {
        [, $partnerA] = $this->createVerifiedPartnerUser('laundry', 'Laundry A');
        [$userB] = $this->createVerifiedPartnerUser('cleaning', 'Cleaning B');

        $packageA = ServicePartnerPackage::create([
            'service_partner_id' => $partnerA->id,
            'name' => 'Paket Milik A',
            'price' => 10000,
            'unit' => '/kg',
            'is_active' => true,
        ]);

        $this->actingAs($userB)->put(route('partner.packages.update', $packageA), [
            'name' => 'Hacked',
            'price' => 5000,
            'unit' => '/kg',
        ])->assertForbidden();
        $this->actingAs($userB)->delete(route('partner.packages.destroy', $packageA))->assertForbidden();
    }

    public function test_partner_can_customize_service_features_and_display_on_catalog(): void
    {
        [$user, $partner] = $this->createVerifiedPartnerUser('laundry', 'Seven Laundry Melati');

        $this->actingAs($user)->put(route('partner.profile.update'), [
            'business_name' => 'Seven Laundry Melati',
            'category' => 'laundry',
            'phone' => '081234567890',
            'service_features_submitted' => '1',
            'service_features' => [
                'Gratis jemput minimal 3 kg',
                'Parfum khusus anti apek 7 hari',
                'Setrika uap super rapi',
            ],
        ])->assertRedirect(route('partner.profile.index'));

        $this->assertEquals([
            'Gratis jemput minimal 3 kg',
            'Parfum khusus anti apek 7 hari',
            'Setrika uap super rapi',
        ], $partner->fresh()->service_features);

        $this->get('/layanan?kota=Jakarta')
            ->assertStatus(200)
            ->assertSee('Gratis jemput minimal 3 kg')
            ->assertSee('Parfum khusus anti apek 7 hari')
            ->assertSee('Setrika uap super rapi');
    }
}
