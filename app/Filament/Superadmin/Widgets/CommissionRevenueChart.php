<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\AdsPromotion;
use App\Models\Booking;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class CommissionRevenueChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Pendapatan & Komisi Platform';

    protected ?string $maxHeight = '280px';

    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        $months = [];
        $commissionData = [];
        $adsData = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        $now = Carbon::now();

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $monthNum = (int) $date->format('n');
            $months[] = $monthNames[$monthNum].' '.$date->format('y');

            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            // Komisi 5% dari booking sewa
            $bookingSum = Booking::whereIn('status', ['approved', 'active', 'completed'])
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_amount');
            $commissionData[] = round($bookingSum * 0.05);

            // Monetisasi iklan CPC kos
            $adsSum = AdsPromotion::whereBetween('created_at', [$start, $end])
                ->sum('cpc_spent');
            $adsData[] = round($adsSum);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Komisi Sewa Kos (5%) (Rp)',
                    'data' => $commissionData,
                    'borderColor' => '#0284c7',
                    'backgroundColor' => 'rgba(2, 132, 199, 0.08)',
                    'fill' => true,
                    'tension' => 0.35,
                    'borderWidth' => 2.5,
                    'pointBackgroundColor' => '#0284c7',
                    'pointRadius' => 4,
                ],
                [
                    'label' => 'Monetisasi Iklan Kos (CPC) (Rp)',
                    'data' => $adsData,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.08)',
                    'fill' => true,
                    'tension' => 0.35,
                    'borderWidth' => 2.5,
                    'pointBackgroundColor' => '#10b981',
                    'pointRadius' => 4,
                ],
            ],
            'labels' => $months,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'top',
                    'labels' => [
                        'font' => [
                            'family' => 'Geist',
                            'weight' => '500',
                            'size' => 12,
                        ],
                        'usePointStyle' => true,
                        'padding' => 14,
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'family' => 'Geist',
                        ],
                    ],
                ],
                'y' => [
                    'display' => true,
                    'position' => 'left',
                    'grid' => [
                        'color' => '#f1f5f9',
                    ],
                    'ticks' => [
                        'font' => [
                            'family' => 'Geist',
                        ],
                    ],
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
