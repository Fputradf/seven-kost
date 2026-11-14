<?php

namespace App\Filament\Superadmin\Resources\Kosts\Pages;

use App\Filament\Superadmin\Resources\Kosts\KostResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;

class CreateKost extends CreateRecord
{
    protected static string $resource = KostResource::class;

    protected string $view = 'filament.superadmin.resources.kosts.pages.create-kost';

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
                ->label('Simpan Kos')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
