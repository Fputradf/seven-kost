<?php

namespace App\Filament\Superadmin\Pages;

use App\Filament\Superadmin\Widgets\LatestBookingsTableWidget;
use App\Filament\Superadmin\Widgets\PlatformAnalyticsChart;
use App\Filament\Superadmin\Widgets\PlatformStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Enums\Width;

class Dashboard extends BaseDashboard
{
    protected string $view = 'filament.superadmin.pages.dashboard';

    public function getMaxContentWidth(): Width|string|null
    {
        return Width::Full;
    }

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getColumns(): int|array
    {
        return 1;
    }

    public function getWidgets(): array
    {
        return [
            PlatformStatsOverview::class,
            PlatformAnalyticsChart::class,
            LatestBookingsTableWidget::class,
        ];
    }
}
