<?php

namespace Tests\Feature;

use Tests\TestCase;

class FrontendHomeTest extends TestCase
{
    /**
     * Test that the homepage renders properly with redesigned UI, header, HD banners, interactive CTA, and bright modern footer.
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

        // Hero Section & HD Building
        $response->assertSee('Terpercaya');
        $response->assertSee('Aman');
        $response->assertSee('Cari Kos Jadi');
        $response->assertSee('Lebih Mudah');
        $response->assertSee('Temukan kos terbaik di lokasi strategis');
        $response->assertSee('hero-building-hd.jpg');
        $response->assertSee('Listing Pilihan Nasional');

        // Search floating bar
        $response->assertSee('Lokasi / Kampus');
        $response->assertSee('Tipe Kos');
        $response->assertSee('Rentang Harga');

        // 3 Ad Banner slots in Ultra HD (pure images, no text overlay)
        $response->assertSee('banner-jakarta-hd.jpg');
        $response->assertSee('banner-bandung-hd.jpg');
        $response->assertSee('banner-yogyakarta-hd.jpg');

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

        // Banners (Marketplace & Layanan Mitra)
        $response->assertSee('Marketplace Barang Kos');
        $response->assertSee('Layanan Mitra');
        $response->assertSee('Coming Soon');

        // Interactive Animated CTA Banner
        $response->assertSee('Pasang Iklan Kosmu');
        $response->assertSee('Sekarang!');
        $response->assertSee('Listing Langsung Tayang');

        // Bright Modern Footer (Clean light style, no dark navy)
        $response->assertSee('Cari Kos Jadi Lebih Mudah');
        $response->assertSee('Navigasi');
        $response->assertSee('Informasi');
        $response->assertSee('Ikuti Kami');
        $response->assertSee('Berlangganan Newsletter');
        $response->assertSee('Aman & Terpercaya', false);
    }
}
