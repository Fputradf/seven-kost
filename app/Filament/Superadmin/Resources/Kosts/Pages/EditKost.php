<?php

namespace App\Filament\Superadmin\Resources\Kosts\Pages;

use App\Filament\Superadmin\Resources\Kosts\KostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;

class EditKost extends EditRecord
{
    protected static string $resource = KostResource::class;

    protected string $view = 'filament.superadmin.resources.kosts.pages.edit-kost';

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

            DeleteAction::make()
                ->label('Hapus Kos')
                ->color('danger')
                ->icon('heroicon-m-trash')
                ->requiresConfirmation(),

            $this->getSaveFormAction()
                ->label('Simpan Perubahan')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
