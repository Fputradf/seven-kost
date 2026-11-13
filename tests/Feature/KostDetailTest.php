<?php

namespace Tests\Feature;

use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\User;
use Tests\TestCase;

class KostDetailTest extends TestCase
{
    private function getOrCreateTestKost(): Kost
    {
        $user = User::first() ?? User::factory()->create();
        $province = Province::first() ?? Province::create(['id' => '31', 'name' => 'DKI Jakarta']);
        $regency = Regency::first() ?? Regency::create(['id' => '3171', 'province_id' => $province->id, 'name' => 'Jakarta Selatan']);

        $kost = Kost::firstOrCreate(
            ['name' => 'Kost Test Detail UI'],
            [
                'user_id' => $user->id,
                'province_id' => $province->id,
                'regency_id' => $regency->id,
                'type' => 'campur',
                'price_start' => 1500000,
                'rent_type' => 'bulanan',
                'address' => 'Jl. Pengujian No. 123, Cilandak',
                'description' => 'Deskripsi kos untuk testing detail UI baru Seven Kost.',
                'rules' => 'Dilarang merokok di dalam kamar.',
                'latitude' => -6.2088,
                'longitude' => 106.8456,
                'verification_status' => 'verified',
                'is_available' => true,
                'is_featured' => true,
            ]
        );

        Room::firstOrCreate(
            ['kost_id' => $kost->id, 'name' => 'Kamar Deluxe Test'],
            [
                'price' => 1500000,
                'total_rooms' => 5,
                'available_rooms' => 2,
                'room_size' => '3x4 m',
            ]
        );

        return $kost;
    }

    public function test_kost_detail_page_renders_with_redesigned_ui_for_guest(): void
    {
        $kost = $this->getOrCreateTestKost();

        $response = $this->get('/kos/'.$kost->slug);

        $response->assertStatus(200);

        // Header & Navigasi
        $response->assertSee('Beranda');
        $response->assertSee($kost->name);
        $response->assertSee('Bagikan');
        $response->assertSee('Simpan');

        // Galeri & Visual
        $response->assertSee('Foto Utama');
        $response->assertSee('Lihat Semua Foto');

        // Pilihan Tipe Kamar
        $response->assertSee('Pilihan Tipe Kamar');
        $response->assertSee('Kamar Deluxe Test');
        $response->assertSee('3x4 m');

        // Fasilitas & Info
        $response->assertSee('Fasilitas Lengkap Kos', false);
        $response->assertSee('Deskripsi & Catatan Kos', false);
        $response->assertSee('Peraturan & Tata Tertib Kos', false);

        // Peta & Tombol Kontak Guest
        $response->assertSee('kostDetailMap');
        $response->assertSee('Tanya Pemilik via WhatsApp');
        $response->assertSee('Masuk untuk Booking');
        $response->assertSee('Laporkan Pelanggaran Iklan Ini');
    }

    public function test_kost_detail_page_renders_booking_action_for_authenticated_user(): void
    {
        $user = User::first() ?? User::factory()->create();
        $kost = $this->getOrCreateTestKost();

        $response = $this->actingAs($user)->get('/kos/'.$kost->slug);

        $response->assertStatus(200);
        $response->assertSee('Ajukan Booking Sewa');
        $response->assertSee('Pengajuan Sewa');
    }
}
