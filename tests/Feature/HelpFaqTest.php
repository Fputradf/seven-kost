<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Tests\TestCase;

class HelpFaqTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RolePermissionSeeder::class,
        ]);
    }

    /**
     * Test bahwa halaman bantuan & FAQ dapat diakses oleh guest dan memuat seluruh informasi utama.
     */
    public function test_faq_page_renders_successfully_for_guest(): void
    {
        $response = $this->get('/bantuan');

        $response->assertStatus(200);

        // Header & Search
        $response->assertSee('Pusat Bantuan & FAQ', false);
        $response->assertSee('Ada yang Bisa Kami');

        // Kategori Tabs
        $response->assertSee('Semua Pertanyaan');
        $response->assertSee('Sewa & Booking Kos', false);
        $response->assertSee('Jual Beli (100% COD)');
        $response->assertSee('Layanan Kebutuhan Kos');
        $response->assertSee('Pasang Iklan & Banner', false);
        $response->assertSee('Akun & Keamanan', false);

        // Pertanyaan Kunci
        $response->assertSee('Bagaimana alur mencari dan memesan kamar kos di SevenKost?');
        $response->assertSee('Bagaimana cara menjual barang kos bekas');
        $response->assertSee('Bagaimana sistem transaksi COD di Marketplace SevenKost?');
        $response->assertSee('Siapa saja yang berhak memesan Layanan Kos');
        $response->assertSee('Apa keuntungan paket Iklan VIP Properti');

        // Live Chat Section: Guest diarahkan ke Login
        $response->assertSee('Tidak Menemukan Jawaban?');
        $response->assertSee('Masuk untuk Live Chat');
        $response->assertDontSee('Mulai Live Chat Sekarang');
    }

    /**
     * Test bahwa pengguna yang login dapat melihat tombol dan modal Live Chat Admin.
     */
    public function test_authenticated_user_can_access_live_chat_section(): void
    {
        $user = User::role('penghuni')->first() ?? User::factory()->create();

        $response = $this->actingAs($user)->get('/bantuan');

        $response->assertStatus(200);
        $response->assertSee('Mulai Live Chat Sekarang');
        $response->assertSee('Live Chat Admin SevenKost');
        $response->assertSee('Super Admin');
        $response->assertSee('Maya Kartika (Admin Verifikator)');
        $response->assertDontSee('Masuk untuk Live Chat');
    }
}
