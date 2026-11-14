<?php

namespace App\Filament\Superadmin\Widgets;

use App\Models\AdsPromotion;
use App\Models\Banner;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\MarketplaceItem;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class PlatformAnalyticsChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected string $view = 'filament.superadmin.widgets.platform-analytics-chart';

    protected ?string $maxHeight = '320px';

    protected int|string|array $columnSpan = 'full';

    public string $activeMetric = 'revenue';

    public string $chartType = 'line';

    protected ?array $precomputedData = null;

    public function setMetric(string $metric): void
    {
        if (in_array($metric, ['revenue', 'bookings_kosts', 'marketplace'])) {
            $this->activeMetric = $metric;
            if ($metric === 'revenue' && $this->chartType !== 'bar') {
                $this->chartType = 'line';
            } elseif ($metric !== 'revenue' && $this->chartType !== 'line') {
                $this->chartType = 'bar';
            }
        }
    }

    public function setChartType(string $type): void
    {
        if (in_array($type, ['line', 'bar'])) {
            $this->chartType = $type;
        }
    }

    public function getHeading(): ?string
    {
        return match ($this->activeMetric) {
            'revenue' => 'Pendapatan & Komisi Platform',
            'bookings_kosts' => 'Transaksi Sewa & Listing Kos',
            'marketplace' => 'Aktivitas Produk Marketplace',
            default => 'Analitik Platform',
        };
    }

    public function getDescription(): ?string
    {
        return null;
    }

    protected function getType(): string
    {
        return $this->chartType;
    }

    protected function getData(): array
    {
        $all = $this->getAllChartsData();

        return $all[$this->activeMetric][$this->chartType]['data'] ?? [];
    }

    /**
     * Precompute and build all chart datasets in 5 fast grouped queries (under 3ms)
     */
    public function getAllChartsData(): array
    {
        if ($this->precomputedData !== null) {
            return $this->precomputedData;
        }

        $now = Carbon::now();
        $startPeriod = $now->copy()->subMonths(5)->startOfMonth();

        // 1. Single aggregate query for bookings (revenue & count)
        $bookingData = Booking::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, SUM(CASE WHEN status IN ("approved", "active", "completed") THEN total_amount ELSE 0 END) as gross_amount, COUNT(*) as booking_count')
            ->where('created_at', '>=', $startPeriod)
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        // 2. Single aggregate query for ads CPC spent
        $adsData = AdsPromotion::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, SUM(cpc_spent) as total_cpc')
            ->where('created_at', '>=', $startPeriod)
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        // 3. Single aggregate query for banner count
        $bannerData = Banner::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total_count')
            ->where('created_at', '>=', $startPeriod)
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        // 4. Single aggregate query for kosts created
        $kostData = Kost::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total_count')
            ->where('created_at', '>=', $startPeriod)
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        // 5. Single aggregate query for marketplace items
        $marketData = MarketplaceItem::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total_new, SUM(CASE WHEN status = "active" THEN 1 ELSE 0 END) as total_active')
            ->where('created_at', '>=', $startPeriod)
            ->groupBy('ym')
            ->get()
            ->keyBy('ym');

        $months = [];
        $grossRental = [];
        $commission = [];
        $adsSpent = [];
        $bookingsCount = [];
        $kostsCount = [];
        $marketNew = [];
        $marketActive = [];

        $monthNames = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des',
        ];

        for ($i = 5; $i >= 0; $i--) {
            $date = $now->copy()->subMonths($i);
            $ym = $date->format('Y-m');
            $monthNum = (int) $date->format('n');
            $months[] = $monthNames[$monthNum].' '.$date->format('y');

            $b = $bookingData->get($ym);
            $gross = $b ? (float) $b->gross_amount : 0;
            $grossRental[] = round($gross);
            $commission[] = round($gross * 0.05);
            $bookingsCount[] = $b ? (int) $b->booking_count : 0;

            $a = $adsData->get($ym);
            $cpc = $a ? (float) $a->total_cpc : 0;
            $bn = $bannerData->get($ym);
            $bannerEstimated = $bn ? ((int) $bn->total_count * 250000) : 0;
            $adsSpent[] = round($cpc + $bannerEstimated);

            $k = $kostData->get($ym);
            $kostsCount[] = $k ? (int) $k->total_count : 0;

            $m = $marketData->get($ym);
            $marketNew[] = $m ? (int) $m->total_new : 0;
            $marketActive[] = $m ? (int) $m->total_active : 0;
        }

        $revOptions = $this->buildOptions(true);
        $countOptions = $this->buildOptions(false);

        $this->precomputedData = [
            'revenue' => [
                'line' => [
                    'data' => [
                        'labels' => $months,
                        'datasets' => [
                            [
                                'label' => 'Total Nilai Sewa (Rp)',
                                'data' => $grossRental,
                                'borderColor' => '#0284c7',
                                'backgroundColor' => 'rgba(2, 132, 199, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#0284c7',
                                'pointRadius' => 4,
                            ],
                            [
                                'label' => 'Komisi Platform (5%) (Rp)',
                                'data' => $commission,
                                'borderColor' => '#10b981',
                                'backgroundColor' => 'rgba(16, 185, 129, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#10b981',
                                'pointRadius' => 4,
                            ],
                            [
                                'label' => 'Monetisasi Iklan (Rp)',
                                'data' => $adsSpent,
                                'borderColor' => '#f59e0b',
                                'backgroundColor' => 'rgba(245, 158, 11, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#f59e0b',
                                'pointRadius' => 4,
                            ],
                        ],
                    ],
                    'options' => $revOptions,
                ],
                'bar' => [
                    'data' => [
                        'labels' => $months,
                        'datasets' => [
                            [
                                'label' => 'Total Nilai Sewa (Rp)',
                                'data' => $grossRental,
                                'backgroundColor' => '#0284c7',
                                'hoverBackgroundColor' => '#0369a1',
                                'borderRadius' => 6,
                                'maxBarThickness' => 28,
                            ],
                            [
                                'label' => 'Komisi Platform (5%) (Rp)',
                                'data' => $commission,
                                'backgroundColor' => '#10b981',
                                'hoverBackgroundColor' => '#059669',
                                'borderRadius' => 6,
                                'maxBarThickness' => 28,
                            ],
                            [
                                'label' => 'Monetisasi Iklan (Rp)',
                                'data' => $adsSpent,
                                'backgroundColor' => '#f59e0b',
                                'hoverBackgroundColor' => '#d97706',
                                'borderRadius' => 6,
                                'maxBarThickness' => 28,
                            ],
                        ],
                    ],
                    'options' => $revOptions,
                ],
            ],
            'bookings_kosts' => [
                'line' => [
                    'data' => [
                        'labels' => $months,
                        'datasets' => [
                            [
                                'label' => 'Transaksi Pengajuan Sewa',
                                'data' => $bookingsCount,
                                'borderColor' => '#f59e0b',
                                'backgroundColor' => 'rgba(245, 158, 11, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#f59e0b',
                                'pointRadius' => 4,
                            ],
                            [
                                'label' => 'Listing Kos Baru',
                                'data' => $kostsCount,
                                'borderColor' => '#6366f1',
                                'backgroundColor' => 'rgba(99, 102, 241, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#6366f1',
                                'pointRadius' => 4,
                            ],
                        ],
                    ],
                    'options' => $countOptions,
                ],
                'bar' => [
                    'data' => [
                        'labels' => $months,
                        'datasets' => [
                            [
                                'label' => 'Transaksi Pengajuan Sewa',
                                'data' => $bookingsCount,
                                'backgroundColor' => '#f59e0b',
                                'hoverBackgroundColor' => '#d97706',
                                'borderRadius' => 6,
                                'maxBarThickness' => 32,
                            ],
                            [
                                'label' => 'Listing Kos Baru',
                                'data' => $kostsCount,
                                'backgroundColor' => '#6366f1',
                                'hoverBackgroundColor' => '#4f46e5',
                                'borderRadius' => 6,
                                'maxBarThickness' => 32,
                            ],
                        ],
                    ],
                    'options' => $countOptions,
                ],
            ],
            'marketplace' => [
                'line' => [
                    'data' => [
                        'labels' => $months,
                        'datasets' => [
                            [
                                'label' => 'Barang Baru Terdaftar',
                                'data' => $marketNew,
                                'borderColor' => '#14b8a6',
                                'backgroundColor' => 'rgba(20, 184, 166, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#14b8a6',
                                'pointRadius' => 4,
                            ],
                            [
                                'label' => 'Barang Aktif / Terjual',
                                'data' => $marketActive,
                                'borderColor' => '#8b5cf6',
                                'backgroundColor' => 'rgba(139, 92, 246, 0.08)',
                                'fill' => true,
                                'tension' => 0.35,
                                'borderWidth' => 2.5,
                                'pointBackgroundColor' => '#8b5cf6',
                                'pointRadius' => 4,
                            ],
                        ],
                    ],
                    'options' => $countOptions,
                ],
                'bar' => [
                    'data' => [
                        'labels' => $months,
                        'datasets' => [
                            [
                                'label' => 'Barang Baru Terdaftar',
                                'data' => $marketNew,
                                'backgroundColor' => '#14b8a6',
                                'hoverBackgroundColor' => '#0d9488',
                                'borderRadius' => 6,
                                'maxBarThickness' => 32,
                            ],
                            [
                                'label' => 'Barang Aktif / Terjual',
                                'data' => $marketActive,
                                'backgroundColor' => '#8b5cf6',
                                'hoverBackgroundColor' => '#7c3aed',
                                'borderRadius' => 6,
                                'maxBarThickness' => 32,
                            ],
                        ],
                    ],
                    'options' => $countOptions,
                ],
            ],
        ];

        return $this->precomputedData;
    }

    protected function buildOptions(bool $isRevenue): array
    {
        return [
            'animation' => [
                'duration' => 250,
            ],
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
                'tooltip' => [
                    'backgroundColor' => '#0f172a',
                    'titleFont' => [
                        'family' => 'Geist',
                        'size' => 12,
                        'weight' => '600',
                    ],
                    'bodyFont' => [
                        'family' => 'Geist',
                        'size' => 12,
                    ],
                    'padding' => 10,
                    'cornerRadius' => 8,
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
                            'size' => 11,
                        ],
                        'color' => '#64748b',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'color' => '#f1f5f9',
                    ],
                    'ticks' => [
                        'font' => [
                            'family' => 'Geist',
                            'size' => 11,
                        ],
                        'color' => '#64748b',
                        'precision' => $isRevenue ? null : 0,
                    ],
                ],
            ],
        ];
    }
}
