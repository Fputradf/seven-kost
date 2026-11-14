<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\Booking;
use App\Models\Kost;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class KostBookingTrendChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Transaksi Sewa & Listing Kos';

    protected ?string $maxHeight = '280px';

    protected function getData(): array
    {
        $months = [];
        $kostsData = [];
        $bookingsData = [];

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

            $bookingsData[] = Booking::whereBetween('created_at', [$start, $end])->count();
            $kostsData[] = Kost::whereBetween('created_at', [$start, $end])->count();
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transaksi Pengajuan Sewa',
                    'data' => $bookingsData,
                    'backgroundColor' => '#f59e0b',
                    'hoverBackgroundColor' => '#d97706',
                    'borderRadius' => 6,
                    'maxBarThickness' => 32,
                ],
                [
                    'label' => 'Listing Kos Baru',
                    'data' => $kostsData,
                    'backgroundColor' => '#6366f1',
                    'hoverBackgroundColor' => '#4f46e5',
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
