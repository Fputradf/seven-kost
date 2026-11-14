<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\MarketplaceItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MarketplaceChart extends ChartWidget
{
    protected static ?int $sort = 4;

    protected ?string $heading = 'Aktivitas Produk Marketplace';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $months = [];
        $marketNewData = [];
        $marketActiveData = [];

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

            $marketNewData[] = MarketplaceItem::whereBetween('created_at', [$start, $end])->count();
            $marketActiveData[] = MarketplaceItem::where('status', 'active')
                ->whereBetween('created_at', [$start, $end])
                ->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Barang Baru Terdaftar',
                    'data' => $marketNewData,
                    'backgroundColor' => '#14b8a6',
                    'hoverBackgroundColor' => '#0d9488',
                    'borderRadius' => 6,
                    'maxBarThickness' => 32,
                ],
                [
                    'label' => 'Barang Aktif / Terjual',
                    'data' => $marketActiveData,
                    'backgroundColor' => '#8b5cf6',
                    'hoverBackgroundColor' => '#7c3aed',
                    'borderRadius' => 6,
                    'maxBarThickness' => 32,
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
                        'precision' => 0,
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
        return 'bar';
    }
}
