<?php

namespace Tests\Feature;

use App\Models\Facility;
use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RoomFacilitiesTest extends TestCase
{
    use RefreshDatabase;

    private User $owner;

    private Kost $kost;

    private Facility $ac;

    private Facility $bath;

    private Facility $wifi;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'pemilik', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'pencari', 'guard_name' => 'web']);

        $this->owner = User::factory()->create();
        $this->owner->assignRole('pemilik');

        $province = Province::create(['id' => '31', 'name' => 'DKI Jakarta']);
        $regency = Regency::create(['id' => '3171', 'province_id' => '31', 'name' => 'Jakarta Selatan', 'type' => 'Kota']);

        $this->kost = Kost::create([
            'user_id' => $this->owner->id,
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'name' => 'Kos Fasilitas Kamar Test',
            'slug' => 'kos-fasilitas-kamar-test',
            'description' => 'Deskripsi kos',
            'type' => 'campur',
            'price_start' => 1500000,
            'rent_type' => 'bulanan',
            'address' => 'Jl. Test No. 1',
            'verification_status' => 'verified',
            'is_available' => true,
        ]);

        $this->ac = Facility::create(['name' => 'AC', 'category' => 'kamar']);
        $this->bath = Facility::create(['name' => 'Kamar Mandi Dalam', 'category' => 'kamar']);
        $this->wifi = Facility::create(['name' => 'WiFi Cepat', 'category' => 'umum']);

        $this->kost->facilities()->sync([$this->wifi->id]);
    }

    public function test_owner_can_store_room_with_specific_facilities(): void
    {
        $response = $this->actingAs($this->owner)->post(route('owner.rooms.store', $this->kost), [
            'name' => 'Tipe VIP AC',
            'price' => 2000000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'room_size' => '4x4 m',
            'facilities' => [$this->ac->id, $this->bath->id],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('rooms', [
            'kost_id' => $this->kost->id,
            'name' => 'Tipe VIP AC',
        ]);

        $room = Room::where('kost_id', $this->kost->id)->where('name', 'Tipe VIP AC')->firstOrFail();
        $this->assertCount(2, $room->facilities);
        $this->assertTrue($room->facilities->contains($this->ac));
        $this->assertTrue($room->facilities->contains($this->bath));
    }

    public function test_owner_can_update_room_facilities(): void
    {
        $room = Room::create([
            'kost_id' => $this->kost->id,
            'name' => 'Tipe Standar',
            'price' => 1200000,
            'total_rooms' => 4,
            'available_rooms' => 2,
            'room_size' => '3x3 m',
        ]);
        $room->facilities()->sync([$this->ac->id]);

        $response = $this->actingAs($this->owner)->put(route('owner.rooms.update', [$this->kost, $room]), [
            'name' => 'Tipe Standar Non-AC',
            'price' => 1100000,
            'total_rooms' => 4,
            'available_rooms' => 2,
            'room_size' => '3x3 m',
            'facilities' => [$this->bath->id],
        ]);

        $response->assertRedirect();
        $room->refresh();
        $this->assertCount(1, $room->facilities);
        $this->assertTrue($room->facilities->contains($this->bath));
        $this->assertFalse($room->facilities->contains($this->ac));
    }

    public function test_detail_views_display_room_specific_facilities(): void
    {
        $room = Room::create([
            'kost_id' => $this->kost->id,
            'name' => 'Tipe Deluxe Istimewa',
            'price' => 1800000,
            'total_rooms' => 2,
            'available_rooms' => 1,
            'room_size' => '3x4 m',
        ]);
        $room->facilities()->sync([$this->ac->id, $this->bath->id]);

        // Public desktop web detail
        $publicResponse = $this->get(route('kost.detail', $this->kost->slug));
        $publicResponse->assertOk();
        $publicResponse->assertSee('Tipe Deluxe Istimewa');
        $publicResponse->assertSee('AC');
        $publicResponse->assertSee('Kamar Mandi Dalam');

        // Tenant mobile detail
        $tenantUser = User::factory()->create();
        $tenantUser->assignRole('pencari');
        $tenantResponse = $this->actingAs($tenantUser)->get(route('tenant.kost.detail', $this->kost->slug));
        $tenantResponse->assertOk();
        $tenantResponse->assertSee('Tipe Deluxe Istimewa');
        $tenantResponse->assertSee('AC');
        $tenantResponse->assertSee('Kamar Mandi Dalam');
    }
}
