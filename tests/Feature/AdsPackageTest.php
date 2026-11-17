<?php

namespace Tests\Feature;

use App\Models\AdsPackage;
use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdsPackageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        AdsPackage::create([
            'name' => 'Paket Highlight 7 Hari',
            'duration_days' => 7,
            'price' => 49000,
            'description' => 'Iklan kos Anda diberi lencana Unggulan.',
            'is_active' => true,
        ]);
    }

    public function test_ads_packages_page_renders_successfully_with_dual_tabs(): void
    {
        $response = $this->get(route('ads.packages.index'));

        $response->assertStatus(200);
        $response->assertSee('Pusat Pasang Iklan');
        $response->assertSee('Iklan Properti Kos');
        $response->assertSee('Iklan Banner Komersial');
        $response->assertSee('Kelola Iklan Kos Anda Secara Gratis');
        $response->assertSee('Perbandingan Akun Biasa vs Akun VIP Pemilik');
        $response->assertSee('Akun VIP Pemilik');
        $response->assertSee('Unlimited (Bebas Pasang Banyak Kos)');
        $response->assertSee('Prioritas Fast-Track Admin');
        $response->assertSee('Pilihan Paket Durasi');
        $response->assertSee('Masuk untuk Memesan');

        // Ensure removed badges and sections are NOT rendered
        $response->assertDontSee('Pemesanan Khusus Akun Pemilik');
        $response->assertDontSee('📢 Solusi Promosi & Periklanan SevenKost');
        $response->assertDontSee('🎨 Sponsorship & Brand Display Ads');
        $response->assertDontSee('Skema Komisi Platform SevenKost');
        $response->assertDontSee('Pertanyaan Seputar Iklan di SevenKost');
        $response->assertDontSee('Paket Promosi Unggulan (Boost Kos)');
        $response->assertDontSee('Paket VIP Terpilih:');
    }

    public function test_authenticated_owner_sees_banner_order_form(): void
    {
        $owner = User::factory()->create();

        $response = $this->actingAs($owner)->get(route('ads.packages.index', ['tab' => 'banner']));

        $response->assertStatus(200);
        $response->assertSee('Formulir Pemasangan Banner Promosi');
        $response->assertDontSee('Pemesanan Khusus Akun Pemilik');
    }

    public function test_authenticated_owner_can_order_commercial_banner(): void
    {
        Storage::fake('public');

        $owner = User::factory()->create();

        $payload = [
            'brand_name' => 'Laundry Express Mahasiswa',
            'contact_email' => 'sponsor@laundry.com',
            'contact_phone' => '081298765432',
            'duration_days' => 30,
            'target_url' => 'https://laundryexpress.id',
            'payment_channel' => 'qris',
            'banner_image' => UploadedFile::fake()->image('banner_hero.jpg', 1200, 400),
        ];

        $response = $this->actingAs($owner)->post(route('ads.banner.order'), $payload);

        $this->assertDatabaseHas('banners', [
            'title' => '[Banner 1 Bulan] Laundry Express Mahasiswa',
            'target_url' => 'https://laundryexpress.id',
            'is_active' => false,
            'payment_status' => 'pending',
            'payment_channel' => 'qris',
        ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => $owner->id,
            'type' => 'banner',
            'status' => 'pending',
            'payment_channel' => 'qris',
        ]);

        $response->assertSessionHas('success');
        $response->assertStatus(302);
        $this->assertStringContainsString('/pembayaran/checkout/', $response->headers->get('Location'));
    }

    public function test_authenticated_owner_can_order_vip_via_xendit_and_redirects_to_checkout(): void
    {
        $owner = User::factory()->create();

        $response = $this->actingAs($owner)->post(route('ads.vip.order'), [
            'vip_package_id' => '1_month',
            'payment_channel' => 'bca_va',
        ]);

        $response->assertSessionHas('success');
        $response->assertStatus(302);
        $this->assertStringContainsString('/pembayaran/checkout/', $response->headers->get('Location'));

        $this->assertDatabaseHas('transactions', [
            'user_id' => $owner->id,
            'type' => 'vip',
            'item_id' => '1_month',
            'amount' => 49000,
            'payment_channel' => 'bca_va',
            'status' => 'pending',
        ]);
    }

    public function test_checkout_page_renders_transaction_details(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)->post(route('ads.vip.order'), [
            'vip_package_id' => '3_months',
            'payment_channel' => 'qris',
        ]);

        $transaction = Transaction::where('user_id', $owner->id)->first();
        $this->assertNotNull($transaction);

        $response = $this->actingAs($owner)->get(route('payments.checkout', $transaction->reference_id));
        $response->assertStatus(200);
        $response->assertSee($transaction->reference_id);
        $response->assertSee('Checkout Pembayaran Xendit');
        $response->assertSee('QRIS');
        $response->assertSee('Ringkasan Tagihan');
    }

    public function test_xendit_payment_simulation_activates_vip_and_redirects_to_success(): void
    {
        $owner = User::factory()->create();
        $this->assertFalse($owner->isVip());

        $this->actingAs($owner)->post(route('ads.vip.order'), [
            'vip_package_id' => '1_month',
            'payment_channel' => 'mandiri_va',
        ]);

        $transaction = Transaction::where('user_id', $owner->id)->first();

        // Simulasi pembayaran lunas via sandbox/testing
        $simulateResponse = $this->actingAs($owner)->post(route('payments.simulate', $transaction->reference_id));
        $simulateResponse->assertRedirect(route('payments.success', $transaction->reference_id));

        $transaction->refresh();
        $this->assertEquals('paid', $transaction->status);
        $this->assertNotNull($transaction->paid_at);

        $owner->refresh();
        $this->assertTrue($owner->isVip());
        $this->assertNotNull($owner->vip_expires_at);

        // Halaman success renders info sukses
        $successResponse = $this->actingAs($owner)->get(route('payments.success', $transaction->reference_id));
        $successResponse->assertStatus(200);
        $successResponse->assertSee('Pembayaran Berhasil!');
        $successResponse->assertSee('Akun Anda Kini Berstatus Pemilik VIP!');
    }

    public function test_payment_status_api_returns_json(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)->post(route('ads.vip.order'), [
            'vip_package_id' => '1_month',
            'payment_channel' => 'qris',
        ]);

        $transaction = Transaction::where('user_id', $owner->id)->first();

        $response = $this->getJson(route('payments.status', $transaction->reference_id));
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'pending',
            'is_paid' => false,
        ]);
    }

    public function test_guest_cannot_order_commercial_banner_directly(): void
    {
        $payload = [
            'brand_name' => 'Guest Brand',
            'contact_email' => 'guest@brand.com',
            'contact_phone' => '081298765432',
            'duration_days' => 30,
            'banner_image' => UploadedFile::fake()->image('banner.jpg', 1200, 400),
        ];

        $response = $this->post(route('ads.banner.order'), $payload);

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_owner_can_order_boost_kos_package(): void
    {
        Storage::fake('public');

        $province = Province::create(['id' => '31', 'name' => 'DKI JAKARTA']);
        $regency = Regency::create(['id' => '3171', 'province_id' => '31', 'name' => 'JAKARTA SELATAN']);

        $owner = User::factory()->create();

        $kost = Kost::create([
            'user_id' => $owner->id,
            'name' => 'Kost Unggulan Asri',
            'slug' => 'kost-unggulan-asri',
            'type' => 'campur',
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'address' => 'Jl. Tebet Raya No. 10',
            'latitude' => -6.225,
            'longitude' => 106.855,
            'price_monthly' => 1500000,
            'total_rooms' => 10,
            'available_rooms' => 5,
            'status' => 'available',
            'verification_status' => 'verified',
        ]);

        $package = AdsPackage::first();

        $response = $this->actingAs($owner)->post(route('ads.packages.order'), [
            'kost_id' => $kost->id,
            'ads_package_id' => $package->id,
            'proof_of_payment' => UploadedFile::fake()->image('transfer_receipt.jpg'),
        ]);

        $response->assertRedirect(route('ads.packages.index', ['tab' => 'kost']));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('ads_promotions', [
            'kost_id' => $kost->id,
            'ads_package_id' => $package->id,
            'status' => 'pending',
        ]);
    }
}
