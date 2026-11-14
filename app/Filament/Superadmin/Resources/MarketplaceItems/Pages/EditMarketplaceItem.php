<?php

namespace App\Filament\Superadmin\Resources\MarketplaceItems\Pages;

use App\Filament\Superadmin\Resources\MarketplaceItems\MarketplaceItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;

class EditMarketplaceItem extends EditRecord
{
    protected static string $resource = MarketplaceItemResource::class;

    protected string $view = 'filament.superadmin.resources.marketplace-items.pages.edit-marketplace-item';

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
                ->label('Hapus Barang')
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
