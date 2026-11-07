<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendHomeTest extends TestCase
{
    /**
     * Test that the homepage renders properly with all redesigned UI sections.
     */
    public function test_homepage_renders_seven_kost_redesigned_ui(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        // Header / Logo
        $response->assertSee('logo-seven-kost.png');
        $response->assertSee('Cari kos di sini...');
        $response->assertSee('Beranda');
        $response->assertSee('Layanan');
        $response->assertSee('Pasang Iklan');
        $response->assertSee('Marketplace');

        // Hero Section
        $response->assertSee('Terpercaya');
        $response->assertSee('Aman');
        $response->assertSee('Cari Kos Jadi');
        $response->assertSee('Lebih Mudah');
        $response->assertSee('Temukan kos terbaik di lokasi strategis');

        // Search floating bar
        $response->assertSee('Lokasi / Kampus');
        $response->assertSee('Tipe Kos');
        $response->assertSee('Rentang Harga');

        // Promo city cards
        $response->assertSee('Kos Terbaik');
        $response->assertSee('di Jakarta');
        $response->assertSee('Kos Strategis');
        $response->assertSee('di Bandung');
        $response->assertSee('Kos Nyaman');
        $response->assertSee('di Yogyakarta');

        // Featured Kos
        $response->assertSee('Kos Unggulan');
        $response->assertSee('Pilihan terbaik untuk hunian nyamanmu');
        $response->assertSee('Terverifikasi');

        // Jelajahi per kota & kampus
        $response->assertSee('Jelajahi per Kota');
        $response->assertSee('Jakarta');
        $response->assertSee('Bandung');
        $response->assertSee('Yogyakarta');
        $response->assertSee('Surabaya');
        $response->assertSee('Depok');

        // Banners
        $response->assertSee('Marketplace Barang Kos');
        $response->assertSee('Layanan Mitra');
        $response->assertSee('Coming Soon');

        // CTA Banner
        $response->assertSee('Pasang Iklan Kosmu');
        $response->assertSee('Sekarang!');

        // Footer
        $response->assertSee('Cari Kos Jadi Lebih Mudah');
        $response->assertSee('Navigasi');
        $response->assertSee('Informasi');
        $response->assertSee('Ikuti Kami');
        $response->assertSee('Berlangganan Newsletter');
    }
}
