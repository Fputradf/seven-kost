<?php

namespace App\Filament\Superadmin\Resources\Reports\Pages;

use App\Filament\Superadmin\Resources\Reports\ReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReports extends ListRecords
{
    protected static string $resource = ReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
