<?php

namespace Tests\Feature;

use App\Models\MarketplaceItem;
use App\Models\User;
use Database\Seeders\FacilitySeeder;
use Database\Seeders\KostSeeder;
use Database\Seeders\MarketplaceSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\WilayahSeeder;
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
            RolePermissionSeeder::class,
            WilayahSeeder::class,
            FacilitySeeder::class,
            KostSeeder::class,
            MarketplaceSeeder::class,
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
            'status' => 'pending',
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

    public function test_tenant_can_access_create_form_and_store_marketplace_item(): void
    {
        Storage::fake('public');

        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        // 1. Tenant membuka formulir jual barang di /penghuni/marketplace/jual
        $createResponse = $this->actingAs($tenant)->get(route('tenant.marketplace.create'));
        $createResponse->assertStatus(200);
        $createResponse->assertSee('Jual Perlengkapan Kos');
        $createResponse->assertSee('Posting Barang Sekarang (Gratis)');

        // 2. Tenant mengirimkan data posting barang
        $payload = [
            'title' => 'Kipas Angin Meja Miyako',
            'category' => 'kipas',
            'price' => 95000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby Seven Kost Melati',
            'city' => 'Jakarta Selatan',
            'description' => 'Kipas angin meja kondisi dingin kencang, baru dipakai 2 bulan.',
            'images' => [
                UploadedFile::fake()->image('kipas1.jpg', 600, 600),
                UploadedFile::fake()->image('kipas2.jpg', 600, 600),
            ],
        ];

        $storeResponse = $this->actingAs($tenant)->post(route('tenant.marketplace.store'), $payload);

        // 3. Harus redirect ke halaman barang saya dengan flash success info pending approval
        $storeResponse->assertRedirect(route('tenant.marketplace.my-items'));
        $storeResponse->assertSessionHas('success');

        // 4. Record tersimpan di DB berstatus pending
        $this->assertDatabaseHas('marketplace_items', [
            'user_id' => $tenant->id,
            'title' => 'Kipas Angin Meja Miyako',
            'category' => 'kipas',
            'price' => 95000,
            'status' => 'pending',
        ]);

        $item = MarketplaceItem::where('title', 'Kipas Angin Meja Miyako')->first();
        $this->assertCount(2, $item->images);

        // 5. Halaman Barang Saya menampilkan barang berstatus Pending
        $myItemsResponse = $this->actingAs($tenant)->get(route('tenant.marketplace.my-items'));
        $myItemsResponse->assertStatus(200);
        $myItemsResponse->assertSee('Kipas Angin Meja Miyako');
        $myItemsResponse->assertSee('Pending');
    }

    public function test_tenant_can_delete_their_own_marketplace_item(): void
    {
        Storage::fake('public');

        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $item = MarketplaceItem::create([
            'user_id' => $tenant->id,
            'title' => 'Rak Sepatu Susun 4',
            'slug' => 'rak-sepatu-susun-4',
            'category' => 'rak',
            'price' => 50000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby SevenKost',
            'city' => 'Jakarta Selatan',
            'description' => 'Rak sepatu susun plastik masih bagus.',
            'status' => 'pending',
            'views_count' => 0,
        ]);

        $deleteResponse = $this->actingAs($tenant)->delete(route('tenant.marketplace.destroy', $item->id));

        $deleteResponse->assertRedirect(route('tenant.marketplace.my-items'));
        $deleteResponse->assertSessionHas('success');

        $this->assertDatabaseMissing('marketplace_items', [
            'id' => $item->id,
        ]);
    }

    public function test_user_cannot_delete_other_users_marketplace_item(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $item = MarketplaceItem::create([
            'user_id' => $owner->id,
            'title' => 'Barang Milik Orang Lain',
            'slug' => 'barang-milik-orang-lain',
            'category' => 'perabot',
            'price' => 75000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby',
            'city' => 'Jakarta',
            'description' => 'Deskripsi barang',
            'status' => 'pending',
            'views_count' => 0,
        ]);

        $deleteResponse = $this->actingAs($stranger)->delete(route('tenant.marketplace.destroy', $item->id));

        $deleteResponse->assertStatus(403);
        $this->assertDatabaseHas('marketplace_items', [
            'id' => $item->id,
        ]);
    }

    public function test_superadmin_can_approve_pending_marketplace_item(): void
    {
        $tenant = User::factory()->create();
        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');

        $item = MarketplaceItem::create([
            'user_id' => $tenant->id,
            'title' => 'Dispenser Air Panas Dingin',
            'slug' => 'dispenser-air-panas-dingin',
            'category' => 'elektronik',
            'price' => 120000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby Kost Melati',
            'city' => 'Jakarta',
            'description' => 'Dispenser berfungsi normal tanpa bocor.',
            'status' => 'pending',
            'views_count' => 0,
        ]);

        // Sebelum approve, barang pending tidak tampil di katalog publik
        $catalogResponse = $this->get(route('marketplace.index'));
        $catalogResponse->assertDontSee('Dispenser Air Panas Dingin');

        // Admin melakukan update approve status menjadi active
        $item->update(['status' => 'active']);

        // Setelah approve, barang tampil di katalog publik
        $approvedCatalogResponse = $this->get(route('marketplace.index'));
        $approvedCatalogResponse->assertStatus(200);
        $approvedCatalogResponse->assertSee('Dispenser Air Panas Dingin');
    }

    public function test_tenant_can_view_edit_marketplace_item_page(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $item = MarketplaceItem::create([
            'user_id' => $tenant->id,
            'title' => 'Kipas Meja Miyako',
            'slug' => 'kipas-meja-miyako',
            'category' => 'kipas',
            'price' => 80000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby',
            'city' => 'Jakarta',
            'description' => 'Kipas angin meja kondisi bagus.',
            'status' => 'active',
            'views_count' => 0,
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.marketplace.edit', $item->id));

        $response->assertStatus(200);
        $response->assertSee('Edit Barang Dagangan');
        $response->assertSee('Kipas Meja Miyako');
        $response->assertSee('Simpan Perubahan Barang');
    }

    public function test_tenant_can_update_marketplace_item_and_status_becomes_pending(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $item = MarketplaceItem::create([
            'user_id' => $tenant->id,
            'title' => 'Kipas Meja Lama',
            'slug' => 'kipas-meja-lama',
            'category' => 'kipas',
            'price' => 80000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby',
            'city' => 'Jakarta',
            'description' => 'Kipas angin meja kondisi bagus.',
            'status' => 'active',
            'views_count' => 0,
        ]);

        $payload = [
            'title' => 'Kipas Meja Miyako 9 Inch Updated',
            'category' => 'kipas',
            'price' => 85000,
            'condition' => 'bekas_seperti_baru',
            'meetup_location' => 'Lobby Kos Melati Lantai 1',
            'city' => 'Jakarta Selatan',
            'description' => 'Kipas sudah dibersihkan dan mulus.',
        ];

        $response = $this->actingAs($tenant)->put(route('tenant.marketplace.update', $item->id), $payload);

        $response->assertRedirect(route('tenant.marketplace.my-items'));
        $response->assertSessionHas('success');

        $item->refresh();
        $this->assertEquals('Kipas Meja Miyako 9 Inch Updated', $item->title);
        $this->assertEquals(85000, $item->price);
        $this->assertEquals('pending', $item->status);
    }

    public function test_stranger_cannot_edit_or_update_other_users_marketplace_item(): void
    {
        $owner = User::factory()->create();
        $stranger = User::factory()->create();

        $item = MarketplaceItem::create([
            'user_id' => $owner->id,
            'title' => 'Barang Privat Pemilik',
            'slug' => 'barang-privat-pemilik',
            'category' => 'perabot',
            'price' => 50000,
            'condition' => 'bekas_baik',
            'meetup_location' => 'Lobby',
            'city' => 'Jakarta',
            'description' => 'Deskripsi',
            'status' => 'active',
            'views_count' => 0,
        ]);

        $editResponse = $this->actingAs($stranger)->get(route('tenant.marketplace.edit', $item->id));
        $editResponse->assertStatus(403);

        $updateResponse = $this->actingAs($stranger)->put(route('tenant.marketplace.update', $item->id), [
            'title' => 'Coba Retas Ubah Judul',
            'category' => 'perabot',
            'price' => 1000,
            'condition' => 'baru',
            'meetup_location' => 'Luar',
            'description' => 'Hacker edit',
        ]);
        $updateResponse->assertStatus(403);
    }
}
