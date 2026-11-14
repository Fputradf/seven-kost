<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\AdsPromotion;
use App\Models\Banner;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\MarketplaceItem;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PlatformStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // 1. Hitung total komisi platform dari booking + pendapatan monetisasi iklan
        $bookingTotal = Booking::whereIn('status', ['approved', 'active', 'completed'])->sum('total_amount');
        $bookingCommission = $bookingTotal * 0.05; // Komisi 5% platform
        $adsSpent = AdsPromotion::sum('cpc_spent');
        $bannerRevenue = Banner::where('is_active', true)->count() * (float) setting('banner_price_30', 250000);
        $totalCommission = $bookingCommission + $adsSpent + $bannerRevenue;

        // Sparkline 7 bulan terakhir untuk komisi
        $commissionTrend = [];
        $now = Carbon::now();
        for ($i = 6; $i >= 0; $i--) {
            $monthStart = $now->copy()->subMonths($i)->startOfMonth();
            $monthEnd = $now->copy()->subMonths($i)->endOfMonth();

            $monthBooking = Booking::whereIn('status', ['approved', 'active', 'completed'])
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('total_amount') * 0.05;

            $monthAds = AdsPromotion::whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('cpc_spent');

            $commissionTrend[] = round(($monthBooking + $monthAds) / 1000);
        }

        // 2. Metrik Kos
        $totalKosts = Kost::count();
        $verifiedKosts = Kost::where('verification_status', 'verified')->count();
        $pendingKosts = Kost::where('verification_status', 'pending')->count();

        // 3. Metrik Pengguna
        $totalUsers = User::count();
        $ownerCount = User::role('admin')->count();
        $tenantCount = User::role('penghuni')->count();

        // 4. Metrik Marketplace & Laporan
        $activeProducts = MarketplaceItem::where('status', 'active')->count();
        $pendingReports = Report::where('status', 'pending')->count();

        return [
            Stat::make('Komisi & Pendapatan Platform', 'Rp '.number_format($totalCommission, 0, ',', '.'))
                ->description('Komisi sewa 5%, iklan CPC & banner aktif')
                ->chart($commissionTrend)
                ->color('success'),

            Stat::make('Listing Properti Kos', $totalKosts.' Properti')
                ->description("{$verifiedKosts} Terverifikasi • {$pendingKosts} Pending")
                ->chart([3, 4, 5, 6, 7, 7, $totalKosts])
                ->color('primary'),

            Stat::make('Pengguna & Mitra Pemilik', $totalUsers.' Akun')
                ->description("{$ownerCount} Pemilik Kos • {$tenantCount} Pencari Kos")
                ->chart([5, 7, 8, 10, 11, 12, $totalUsers])
                ->color('info'),

            Stat::make('Marketplace & Laporan', $activeProducts.' Produk Aktif')
                ->description("{$pendingReports} Laporan Butuh Penanganan")
                ->chart([1, 2, 4, 3, 5, 6, $activeProducts])
                ->color($pendingReports > 0 ? 'danger' : 'warning'),
        ];
    }
}
