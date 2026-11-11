<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            \Database\Seeders\RolePermissionSeeder::class,
        ]);
    }

    /**
     * Test bahwa halaman Layanan Mitra Kos dapat diakses dan merender seluruh katalog paket.
     */
    public function test_services_catalog_page_renders_successfully(): void
    {
        $response = $this->get('/layanan');

        $response->assertStatus(200);

        // Header & Judul
        $response->assertSee('Layanan Kebutuhan Kos', false);
        $response->assertSee('Mitra Siap Melayani Hari Ini');

        // Kategori Filter Tab
        $response->assertSee('Semua Layanan');
        $response->assertSee('Laundry Kiloan');
        $response->assertSee('Kebersihan Kamar');
        $response->assertSee('Katering Harian');
        $response->assertSee('Teknisi');
        $response->assertSee('Coming Soon');

        // Katalog Layanan
        $response->assertSee('Seven Laundry Kos');
        $response->assertSee('Seven Clean Kamar');
        $response->assertSee('Seven Katering Harian');
        $response->assertSee('Seven Care Teknisi');

        // Paket & Tombol Pemesanan (Arahkan ke Login untuk Guest)
        $response->assertSee('Laundry Kiloan Reguler');
        $response->assertSee('Deep Clean Kamar Mandi Kos');
        $response->assertSee('Pesan Sekarang');
        $response->assertDontSee('Teruskan ke WhatsApp');

        // Alur & Mitra
        $response->assertSee('Iklan Sponsor');
        $response->assertSee('Bagaimana Cara Pesan Layanan?');
        $response->assertSee('Punya Usaha Laundry');
    }

    /**
     * Test bahwa akun dengan role penghuni dapat mengakses form pemesanan layanan.
     */
    public function test_authenticated_penghuni_can_view_order_modal(): void
    {
        $penghuni = \App\Models\User::role('penghuni')->first();
        if ($penghuni) {
            $response = $this->actingAs($penghuni)->get('/layanan');
            $response->assertStatus(200);
            $response->assertSee('Form Pemesanan Layanan');
            $response->assertSee('Konfirmasi');
        }
    }
}
