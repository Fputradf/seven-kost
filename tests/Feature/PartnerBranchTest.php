<?php

namespace Tests\Feature;

use App\Models\KycVerification;
use App\Models\Province;
use App\Models\Regency;
use App\Models\ServicePartner;
use App\Models\ServicePartnerBranch;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PartnerBranchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    private function createVerifiedPartnerUser(string $category = 'laundry', string $businessName = 'Seven Laundry Melati Jakarta'): array
    {
        $user = User::factory()->create(['email' => 'mitra.'.uniqid().'@test.com']);
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

        $partner = ServicePartner::create([
            'user_id' => $user->id,
            'business_name' => $businessName,
            'category' => $category,
            'phone' => '081234567890',
            'is_open' => true,
            'verification_status' => 'verified',
        ]);

        return [$user, $partner];
    }

    public function test_partner_can_create_update_toggle_and_delete_branch(): void
    {
        [$user, $partner] = $this->createVerifiedPartnerUser();

        $province = Province::firstOrCreate(['id' => '32'], ['name' => 'Jawa Barat']);
        $regency = Regency::firstOrCreate(['id' => '3273'], ['province_id' => '32', 'name' => 'Kota Bandung']);

        // 1. Create branch
        $response = $this->actingAs($user)->post(route('partner.branches.store'), [
            'branch_name' => 'Cabang Dago Bandung',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Ir. H. Juanda No. 88, Coblong, Bandung',
            'phone' => '081299998888',
            'operational_hours' => '08:00 - 21:00 WIB',
            'service_radius_km' => 6.0,
            'latitude' => -6.8920,
            'longitude' => 107.6105,
            'is_open' => '1',
        ]);

        $response->assertRedirect(route('partner.profile.index', ['tab' => 'location']));
        $this->assertDatabaseHas('service_partner_branches', [
            'service_partner_id' => $partner->id,
            'branch_name' => 'Cabang Dago Bandung',
            'regency_id' => '3273',
            'is_open' => true,
        ]);

        $branch = ServicePartnerBranch::where('service_partner_id', $partner->id)->firstOrFail();

        // 2. View in profile (Wilayah & Lokasi Hub tab)
        $profileResponse = $this->actingAs($user)->get(route('partner.profile.index', ['tab' => 'location']));
        $profileResponse->assertStatus(200);
        $profileResponse->assertSee('Cabang Dago Bandung');

        // 3. Toggle status
        $this->actingAs($user)->patch(route('partner.branches.toggle-status', $branch))
            ->assertRedirect(route('partner.profile.index', ['tab' => 'location']));
        $this->assertFalse($branch->fresh()->is_open);

        // 4. Update branch
        $this->actingAs($user)->put(route('partner.branches.update', $branch), [
            'branch_name' => 'Cabang Dago Pasteur',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Pasteur No. 12, Bandung',
            'service_radius_km' => 5.0,
            'is_open' => '1',
        ])->assertRedirect(route('partner.profile.index', ['tab' => 'location']));

        $this->assertDatabaseHas('service_partner_branches', [
            'id' => $branch->id,
            'branch_name' => 'Cabang Dago Pasteur',
            'is_open' => true,
        ]);

        // 5. Delete branch
        $this->actingAs($user)->delete(route('partner.branches.destroy', $branch))
            ->assertRedirect(route('partner.profile.index', ['tab' => 'location']));

        $this->assertDatabaseMissing('service_partner_branches', ['id' => $branch->id]);
    }

    public function test_partner_cannot_modify_another_partners_branch(): void
    {
        [, $partnerA] = $this->createVerifiedPartnerUser('laundry', 'Laundry A');
        [$userB] = $this->createVerifiedPartnerUser('cleaning', 'Cleaning B');

        $province = Province::firstOrCreate(['id' => '32'], ['name' => 'Jawa Barat']);
        $regency = Regency::firstOrCreate(['id' => '3273'], ['province_id' => '32', 'name' => 'Kota Bandung']);

        $branchA = ServicePartnerBranch::create([
            'service_partner_id' => $partnerA->id,
            'branch_name' => 'Cabang Milik A',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Dago No. 1',
            'service_radius_km' => 5.0,
            'is_open' => true,
        ]);

        $this->actingAs($userB)->patch(route('partner.branches.toggle-status', $branchA))->assertForbidden();
        $this->actingAs($userB)->delete(route('partner.branches.destroy', $branchA))->assertForbidden();
    }

    public function test_service_catalog_displays_partner_branch_for_selected_city(): void
    {
        [, $partner] = $this->createVerifiedPartnerUser('laundry', 'Seven Laundry Melati');

        $province = Province::firstOrCreate(['id' => '32'], ['name' => 'Jawa Barat']);
        $regency = Regency::firstOrCreate(['id' => '3273'], ['province_id' => '32', 'name' => 'Kota Bandung']);

        ServicePartnerBranch::create([
            'service_partner_id' => $partner->id,
            'branch_name' => 'Cabang Dago Bandung',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Ir. H. Juanda No. 88, Bandung',
            'operational_hours' => '07:00 - 22:00 WIB',
            'service_radius_km' => 5.0,
            'is_open' => true,
        ]);

        $response = $this->get('/layanan?kota=Bandung');

        $response->assertStatus(200);
        $response->assertSee('Seven Laundry Melati - Cabang Dago Bandung');
        $response->assertSee('Jl. Ir. H. Juanda No. 88, Bandung');
    }

    public function test_service_catalog_marks_city_unavailable_when_no_partner_or_branch_exists(): void
    {
        $provDki = Province::firstOrCreate(['id' => '31'], ['name' => 'DKI Jakarta']);
        $regJaksel = Regency::firstOrCreate(['id' => '3171'], ['province_id' => $provDki->id, 'name' => 'Kota Jakarta Selatan']);
        $provDiy = Province::firstOrCreate(['id' => '34'], ['name' => 'D.I. Yogyakarta']);
        $regSleman = Regency::firstOrCreate(['id' => '3404'], ['province_id' => $provDiy->id, 'name' => 'Kabupaten Sleman']);

        [, $partner] = $this->createVerifiedPartnerUser('laundry', 'Seven Laundry Melati Jakarta');
        $partner->update([
            'province_id' => $provDki->id,
            'regency_id' => $regJaksel->id,
            'address' => 'Jl. Fatmawati No. 45, Jakarta Selatan',
        ]);

        // Catering di Kecamatan Depok, Sleman (Jogja) tidak boleh salah cocok dengan Kota Depok
        [, $cateringPartner] = $this->createVerifiedPartnerUser('catering', 'Seven Kitchen UGM Jogja');
        $cateringPartner->update([
            'province_id' => $provDiy->id,
            'regency_id' => $regSleman->id,
            'address' => 'Jl. Kaliurang KM 5.5, Depok, Sleman, D.I. Yogyakarta',
        ]);

        // Depok has no partner and no branch at all
        $response = $this->get('/layanan?kota=Depok');

        $response->assertStatus(200);
        $response->assertSee('Belum ada mitra di Depok');
        $response->assertSee('Tidak Tersedia di Depok');
        $response->assertDontSee('Seven Kitchen UGM Jogja');
    }

    public function test_service_catalog_dynamically_syncs_new_branch_city_and_marks_other_categories_unavailable(): void
    {
        $provJateng = Province::firstOrCreate(['id' => '33'], ['name' => 'Jawa Tengah']);
        $regSemarang = Regency::firstOrCreate(['id' => '3374'], ['province_id' => $provJateng->id, 'name' => 'Kota Semarang']);

        [, $partner] = $this->createVerifiedPartnerUser('laundry', 'Seven Laundry Melati');

        ServicePartnerBranch::create([
            'service_partner_id' => $partner->id,
            'branch_name' => 'Cabang Tembalang Semarang',
            'province_id' => $provJateng->id,
            'regency_id' => $regSemarang->id,
            'address' => 'Jl. Prof. Soedarto No. 10, Tembalang, Semarang',
            'service_radius_km' => 5.0,
            'is_open' => true,
        ]);

        $response = $this->get('/layanan?kota=Semarang');

        $response->assertStatus(200);
        $response->assertSee('Kota Semarang');
        $response->assertSee('Seven Laundry Melati - Cabang Tembalang Semarang');
        $response->assertSee('Belum ada mitra di Semarang');
        $response->assertSee('Tidak Tersedia di Semarang');
    }
}
