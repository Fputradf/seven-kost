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

        // Browser Tab Favicon
        $response->assertSee('favicon.png');
        $response->assertSee('favicon.ico');

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

        // Ad Banners displayed one-by-one wide format 1200x400 (aspect-[12/4])
        $response->assertSee('banner-jakarta-wide.jpg');
        $response->assertSee('banner-bandung-wide.jpg');
        $response->assertSee('banner-yogyakarta-wide.jpg');

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

        // Two Interactive Cards (Marketplace & Layanan Mitra)
        $response->assertSee('Marketplace Barang Kos');
        $response->assertSee('marketplace-hd.jpg');
        $response->assertSee('Layanan Mitra');
        $response->assertSee('service-laundry.jpg');
        $response->assertSee('service-cleaning.jpg');
        $response->assertSee('service-catering.jpg');
        $response->assertSee('Available');
        $response->assertSee('Coming Soon');

        // Interactive Animated CTA Banner with HD Photos
        $response->assertSee('Pasang Iklan Kosmu');
        $response->assertSee('Sekarang!');
        $response->assertSee('Listing Langsung Tayang');
        $response->assertSee('cta-host-male.jpg');
        $response->assertSee('cta-house-keys.jpg');

        // Bright Modern Footer (Clean light style, copyright inside footer, no Aman & Terpercaya)
        $response->assertSee('Cari Kos Jadi Lebih Mudah');
        $response->assertSee('Navigasi');
        $response->assertSee('Informasi');
        $response->assertSee('Ikuti Kami');
        $response->assertSee('Berlangganan Newsletter');
        $response->assertSee('SevenKost. All rights reserved.');
        $response->assertDontSee('Aman & Terpercaya');
    }
}
