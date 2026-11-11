<?php

namespace Tests\Feature;

use App\Models\MarketplaceItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MarketplaceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            \Database\Seeders\RolePermissionSeeder::class,
            \Database\Seeders\WilayahSeeder::class,
            \Database\Seeders\FacilitySeeder::class,
            \Database\Seeders\KostSeeder::class,
            \Database\Seeders\MarketplaceSeeder::class,
        ]);
    }

    public function test_marketplace_catalog_renders_successfully_for_guests(): void
    {
        $response = $this->get(route('marketplace.index'));

        $response->assertStatus(200);
        $response->assertSee('Marketplace');
        $response->assertSee('Barang Kos');
        $response->assertSee('COD');
        $response->assertSee('Kasur Busa 120x200 Premium');
        $response->assertSee('Masuk untuk Jual Barang');
        $response->assertSee('Iklan Sponsor');
        $response->assertDontSee('COD Aman');
        $response->assertDontSee('⭐ Unggulan');
    }

    public function test_guest_is_redirected_to_login_when_accessing_create_form(): void
    {
        $response = $this->get(route('marketplace.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_access_create_form(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('marketplace.create'));

        $response->assertStatus(200);
        $response->assertSee('Jual Barang di SevenKost');
        $response->assertSee('Pratinjau Iklan Anda');
        $response->assertSee('Unggah Foto Barang');
        $response->assertSee('Lokasi Titik Temu (COD)');
    }

    public function test_authenticated_user_can_store_marketplace_item(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        $payload = [
            'title' => 'Meja Belajar Kayu Minimalis',
            'category' => 'perabot',
            'price' => 150000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby Kos Putri Melati Kampus UI',
            'city' => 'Depok',
            'description' => 'Meja belajar lipat kayu masih mulus tanpa cacat, dipakai 3 bulan selama skripsi.',
            'is_featured' => 1,
            'images' => [
                UploadedFile::fake()->image('meja_1.jpg', 800, 600),
            ],
        ];

        $response = $this->actingAs($user)->post(route('marketplace.store'), $payload);

        $response->assertRedirect(route('marketplace.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('marketplace_items', [
            'title' => 'Meja Belajar Kayu Minimalis',
            'category' => 'perabot',
            'price' => 150000,
            'user_id' => $user->id,
            'is_featured' => true,
        ]);
    }

    public function test_marketplace_detail_page_renders_and_prompts_guest_to_login(): void
    {
        $item = MarketplaceItem::first();

        $response = $this->get(route('marketplace.show', $item->slug));

        $response->assertStatus(200);
        $response->assertSee($item->title);
        $response->assertSee('Tips Keamanan Transaksi COD');
        $response->assertSee('Masuk untuk Hubungi Penjual');
        $response->assertSee('COD');
        $response->assertDontSee('COD Aman');
        $response->assertDontSee('Barang Unggulan');
    }

    public function test_authenticated_user_sees_contact_seller_button_on_detail_page(): void
    {
        $user = User::factory()->create();
        $item = MarketplaceItem::first();

        $response = $this->actingAs($user)->get(route('marketplace.show', $item->slug));

        $response->assertStatus(200);
        $response->assertSee('Hubungi Penjual (COD)');
    }
}
