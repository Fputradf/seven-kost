<?php

namespace Tests\Feature;

use App\Filament\Superadmin\Pages\ManageSettings;
use App\Models\AppSetting;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperAdminSettingsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
    }

    public function test_superadmin_can_view_manage_settings_page(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)
            ->get('/superadmin/pengaturan-aplikasi');

        $response->assertSuccessful();
        $response->assertSee('seven-gradient-card', false);
        $response->assertSee('linear-gradient(135deg, #031d44 0%, #05377a 45%, #0a66c2 100%)', false);
        $response->assertSee('Auto-Compress Image Aktif', false);

        AppSetting::updateOrCreate(['key' => 'app_logo'], ['value' => 'images/logo-seven-kost.png']);
        AppSetting::updateOrCreate(['key' => 'app_logo_dark'], ['value' => 'images/logo-seven-kost-dark.svg']);
        AppSetting::updateOrCreate(['key' => 'app_favicon'], ['value' => 'images/logo-seven-kost.png']);

        Livewire::actingAs($superadmin)
            ->test(ManageSettings::class)
            ->call('callSchemaComponentMethod', 'content.app_logo', 'getUploadedFiles')
            ->assertSuccessful()
            ->call('callSchemaComponentMethod', 'content.app_logo_dark', 'getUploadedFiles')
            ->assertSuccessful()
            ->call('callSchemaComponentMethod', 'content.app_favicon', 'getUploadedFiles')
            ->assertSuccessful();
    }

    public function test_superadmin_can_save_settings_and_upload_new_logo(): void
    {
        Storage::fake('public');

        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $fakeLogo = UploadedFile::fake()->image('custom-logo.png', 200, 200);

        Livewire::actingAs($superadmin)
            ->test(ManageSettings::class)
            ->set('data.app_name', 'Seven Kost Updated')
            ->set('data.app_slogan', 'Hunian Nyaman dan Hemat')
            ->set('data.app_logo', [$fakeLogo])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals('Seven Kost Updated', AppSetting::where('key', 'app_name')->value('value'));
        $this->assertEquals('Hunian Nyaman dan Hemat', AppSetting::where('key', 'app_slogan')->value('value'));

        $savedLogo = AppSetting::where('key', 'app_logo')->value('value');
        $this->assertNotNull($savedLogo);
        Storage::disk('public')->assertExists($savedLogo);
    }

    public function test_auto_compress_scales_down_large_uploaded_image(): void
    {
        Storage::fake('public');

        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $largeImage = UploadedFile::fake()->image('huge-logo.png', 1600, 1200);

        Livewire::actingAs($superadmin)
            ->test(ManageSettings::class)
            ->set('data.app_name', 'Seven Kost')
            ->set('data.app_logo', [$largeImage])
            ->call('save')
            ->assertHasNoErrors();

        $savedLogo = AppSetting::where('key', 'app_logo')->value('value');
        $this->assertNotNull($savedLogo);
        Storage::disk('public')->assertExists($savedLogo);

        $realPath = Storage::disk('public')->path($savedLogo);
        $dimensions = @getimagesize($realPath);
        $this->assertNotNull($dimensions);
        $this->assertLessThanOrEqual(800, $dimensions[0]);
        $this->assertLessThanOrEqual(800, $dimensions[1]);
    }

    public function test_welcome_and_auth_pages_display_dynamic_logo_and_app_name(): void
    {
        AppSetting::updateOrCreate(['key' => 'app_name'], ['value' => 'SevenKost Custom Brand']);
        AppSetting::updateOrCreate(['key' => 'app_logo'], ['value' => 'settings/new-brand-logo.png']);
        Cache::flush();

        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertSee('SevenKost Custom Brand');
        $response->assertSee(asset('storage/settings/new-brand-logo.png'));

        $loginResponse = $this->get('/login');
        $loginResponse->assertSuccessful();
        $loginResponse->assertSee('SevenKost Custom Brand');
        $loginResponse->assertSee(asset('storage/settings/new-brand-logo.png'));
    }

    public function test_superadmin_can_configure_up_to_five_active_social_links_and_display_in_footer(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $socialPayload = [
            ['platform' => 'instagram', 'url' => 'https://instagram.com/sevenkost_official', 'is_active' => true],
            ['platform' => 'tiktok', 'url' => 'https://tiktok.com/@sevenkost_id', 'is_active' => true],
            ['platform' => 'youtube', 'url' => 'https://youtube.com/@sevenkost_tv', 'is_active' => true],
            ['platform' => 'whatsapp', 'url' => 'https://wa.me/6281122334455', 'is_active' => true],
            ['platform' => 'telegram', 'url' => 'https://t.me/sevenkost_channel', 'is_active' => true],
        ];

        Livewire::actingAs($superadmin)
            ->test(ManageSettings::class)
            ->set('data.app_name', 'Seven Kost')
            ->set('data.social_links', $socialPayload)
            ->call('save')
            ->assertHasNoErrors();

        $savedJson = AppSetting::where('key', 'social_links')->value('value');
        $this->assertNotNull($savedJson);
        $savedArray = json_decode($savedJson, true);
        $this->assertCount(5, $savedArray);
        $this->assertEquals('instagram', $savedArray[0]['platform']);
        $this->assertEquals('telegram', $savedArray[4]['platform']);

        // Pastikan helper app_social_links() menghasilkan 5 data
        $activeLinks = app_social_links();
        $this->assertCount(5, $activeLinks);
        $this->assertEquals('Instagram', $activeLinks[0]['name']);
        $this->assertEquals('Telegram', $activeLinks[4]['name']);

        // Verifikasi pada halaman landing page footer
        $response = $this->get('/');
        $response->assertSuccessful();
        $response->assertSee('https://instagram.com/sevenkost_official');
        $response->assertSee('https://tiktok.com/@sevenkost_id');
        $response->assertSee('https://youtube.com/@sevenkost_tv');
        $response->assertSee('https://wa.me/6281122334455');
        $response->assertSee('https://t.me/sevenkost_channel');
    }

    public function test_superadmin_can_configure_tenant_fee_and_cpc_fee_and_packages(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        Livewire::actingAs($superadmin)
            ->test(ManageSettings::class)
            ->set('data.app_name', 'Seven Kost')
            ->set('data.fee_booking_tenant', 7500)
            ->set('data.ad_cpc_fee', 600)
            ->set('data.vip_price_1_month', 55000)
            ->set('data.vip_price_3_months', 145000)
            ->set('data.vip_price_1_year', 450000)
            ->set('data.banner_price_30', 300000)
            ->set('data.banner_price_90', 800000)
            ->set('data.banner_price_365', 2400000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals(7500, (int) AppSetting::where('key', 'fee_booking_tenant')->value('value'));
        $this->assertEquals(600, (int) AppSetting::where('key', 'ad_cpc_fee')->value('value'));
        $this->assertEquals(55000, (int) AppSetting::where('key', 'vip_price_1_month')->value('value'));
        $this->assertEquals(300000, (int) AppSetting::where('key', 'banner_price_30')->value('value'));

        $vipPackages = app_vip_packages();
        $this->assertEquals(55000, $vipPackages['1_month']['price']);
        $this->assertEquals('Rp 55.000', $vipPackages['1_month']['price_label']);

        $bannerPackages = app_banner_packages();
        $this->assertEquals(300000, $bannerPackages[30]['price']);
        $this->assertEquals('Rp 300.000', $bannerPackages[30]['price_label']);

        // Pastikan halaman paket iklan menampilkan harga baru yang dikonfigurasi
        $response = $this->get(route('ads.packages.index'));
        $response->assertSuccessful();
        $response->assertSee('Rp 55.000');
        $response->assertSee('Rp 300.000');
    }

    public function test_booking_includes_tenant_fee_and_promoted_kos_click_tracks_cpc(): void
    {
        AppSetting::updateOrCreate(['key' => 'fee_booking_tenant'], ['value' => '5000']);
        AppSetting::updateOrCreate(['key' => 'ad_cpc_fee'], ['value' => '500']);
        Cache::flush();

        $owner = User::factory()->create(['status' => 'active']);
        $tenant = User::factory()->create(['status' => 'active']);

        $province = Province::first() ?? Province::create(['id' => '34', 'name' => 'DI Yogyakarta']);
        $regency = Regency::first() ?? Regency::create(['id' => '3404', 'province_id' => $province->id, 'name' => 'Sleman']);

        $kost = Kost::create([
            'user_id' => $owner->id,
            'province_id' => $province->id,
            'regency_id' => $regency->id,
            'name' => 'Kost Promosi Unggulan',
            'slug' => 'kost-promosi-unggulan',
            'price_start' => 1000000,
            'verification_status' => 'verified',
            'is_available' => true,
            'is_featured' => true,
            'ad_clicks_count' => 0,
        ]);

        $room = Room::create([
            'kost_id' => $kost->id,
            'name' => 'Kamar Deluxe VIP',
            'price' => 1000000,
            'total_rooms' => 5,
            'available_rooms' => 5,
        ]);

        // 1. Uji klik iklan kos (CPC tracking)
        $this->actingAs($tenant)->get(route('kost.detail', ['slug' => $kost->slug]));
        $this->assertEquals(1, $kost->fresh()->ad_clicks_count);

        // 2. Uji booking mengenakan biaya tambahan penghuni
        $bookingResponse = $this->actingAs($tenant)->post(route('tenant.bookings.store', ['kost' => $kost->id]), [
            'room_id' => $room->id,
            'start_date' => now()->addDays(2)->toDateString(),
            'duration_months' => 2,
        ]);

        $bookingResponse->assertRedirect(route('tenant.bookings.index'));

        // Biaya kamar = 1.000.000 * 2 = 2.000.000 + Biaya Tambahan Penghuni 5.000 = 2.005.000
        $booking = Booking::where('user_id', $tenant->id)->latest()->first();
        $this->assertNotNull($booking);
        $this->assertEquals(2005000.00, (float) $booking->total_amount);
    }

    public function test_dual_logo_helper_returns_light_and_dark_variants(): void
    {
        AppSetting::updateOrCreate(['key' => 'app_logo'], ['value' => 'images/custom-light-logo.png']);
        AppSetting::updateOrCreate(['key' => 'app_logo_dark'], ['value' => 'images/custom-dark-logo.png']);
        Cache::flush();

        $this->assertStringContainsString('images/custom-light-logo.png', app_logo_url('light'));
        $this->assertStringContainsString('images/custom-dark-logo.png', app_logo_url('dark'));
    }

    public function test_superadmin_can_configure_rental_and_late_fee_settings(): void
    {
        $superadmin = User::factory()->create(['status' => 'active']);
        $superadmin->assignRole('superadmin');

        Livewire::actingAs($superadmin)
            ->test(ManageSettings::class)
            ->set('data.app_name', 'Seven Kost')
            ->set('data.rent_bill_notice_days', 10)
            ->set('data.late_fee_enabled', true)
            ->set('data.late_fee_grace_days', 2)
            ->set('data.late_fee_daily_amount', 25000)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertEquals(10, (int) AppSetting::where('key', 'rent_bill_notice_days')->value('value'));
        $this->assertEquals('1', AppSetting::where('key', 'late_fee_enabled')->value('value'));
        $this->assertEquals(2, (int) AppSetting::where('key', 'late_fee_grace_days')->value('value'));
        $this->assertEquals(25000, (int) AppSetting::where('key', 'late_fee_daily_amount')->value('value'));

        $this->assertEquals(10, setting('rent_bill_notice_days'));
        $this->assertTrue(setting('late_fee_enabled'));
        $this->assertEquals(2, setting('late_fee_grace_days'));
        $this->assertEquals(25000, setting('late_fee_daily_amount'));
    }
}
