<?php

namespace App\Filament\Superadmin\Resources\Reports\Pages;

use App\Filament\Superadmin\Resources\Reports\ReportResource;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected string $view = 'filament.superadmin.resources.reports.pages.list-reports';

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
