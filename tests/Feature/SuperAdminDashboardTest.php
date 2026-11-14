<?php

namespace Tests\Feature;

use App\Filament\Superadmin\Widgets\LatestBookingsTableWidget;
use App\Filament\Superadmin\Widgets\PlatformAnalyticsChart;
use App\Filament\Superadmin\Widgets\PlatformStatsOverview;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuperAdminDashboardTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'penghuni', 'guard_name' => 'web']);
    }

    public function test_superadmin_can_access_dashboard_with_visual_charts_and_commission_metrics(): void
    {
        $superadmin = User::factory()->create([
            'status' => 'active',
        ]);
        $superadmin->assignRole('superadmin');

        $response = $this->actingAs($superadmin)
            ->get('/superadmin');

        $response->assertSuccessful();

        // Verifikasi Header Card Gradient pada Halaman Dashboard
        $response->assertSee('Analitik &amp; Performa Platform', false);
        $response->assertSee('Ringkasan Eksekutif');

        // Verifikasi Widget Statistik & Komisi (Livewire)
        Livewire::actingAs($superadmin)
            ->test(PlatformStatsOverview::class)
            ->assertSee('Komisi &amp; Pendapatan Platform', false)
            ->assertSee('Listing Properti Kos')
            ->assertSee('Pengguna &amp; Mitra Pemilik', false)
            ->assertSee('Marketplace &amp; Laporan', false);

        // Verifikasi Widget Grafik Interaktif dengan Opsi Tombol (Tanpa Dropdown)
        Livewire::actingAs($superadmin)
            ->test(PlatformAnalyticsChart::class)
            ->assertSee('Pendapatan &amp; Komisi', false)
            ->assertSee('Sewa &amp; Listing Kos', false)
            ->assertSee('Marketplace')
            ->assertSee('Garis')
            ->assertSee('Batang')
            // Beralih ke Sewa & Listing Kos
            ->call('setMetric', 'bookings_kosts')
            ->assertSee('Transaksi Sewa &amp; Listing Kos', false)
            // Beralih tipe chart ke Batang
            ->call('setChartType', 'bar')
            ->assertSet('chartType', 'bar')
            // Beralih ke Marketplace
            ->call('setMetric', 'marketplace')
            ->assertSee('Aktivitas Produk Marketplace', false)
            // Beralih tipe chart ke Garis
            ->call('setChartType', 'line')
            ->assertSet('chartType', 'line');

        // Verifikasi Widget Tabel Booking Terbaru
        Livewire::actingAs($superadmin)
            ->test(LatestBookingsTableWidget::class)
            ->assertSee('Pengajuan Sewa &amp; Transaksi Booking Terbaru', false);
    }
}
