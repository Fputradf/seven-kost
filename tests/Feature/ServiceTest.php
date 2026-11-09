<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServiceTest extends TestCase
{
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

        // Paket & Tombol Pemesanan
        $response->assertSee('Laundry Kiloan Reguler');
        $response->assertSee('Deep Clean Kamar Mandi Kos');
        $response->assertSee('Pesan Sekarang');
        $response->assertSee('Form Pemesanan Layanan');
        $response->assertSee('Teruskan ke WhatsApp');

        // Alur & Mitra
        $response->assertSee('Bagaimana Cara Pesan Layanan?');
        $response->assertSee('Punya Usaha Laundry');
    }
}
