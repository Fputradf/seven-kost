<?php

namespace App\Filament\Superadmin\Resources\Reports\Pages;

use App\Filament\Superadmin\Resources\Reports\ReportResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected string $view = 'filament.superadmin.resources.reports.pages.create-report';

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

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getFormActionsAlignment(): Alignment
    {
        return Alignment::Start;
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCancelFormAction()
                ->label('Batal')
                ->color('gray')
                ->url(static::getResource()::getUrl('index')),

            $this->getCreateFormAction()
                ->label('Simpan Laporan')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
