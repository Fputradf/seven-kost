<?php

namespace App\Filament\Superadmin\Resources\Banners\Pages;

use App\Filament\Superadmin\Resources\Banners\BannerResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Enums\Alignment;

class EditBanner extends EditRecord
{
    protected static string $resource = BannerResource::class;

    protected string $view = 'filament.superadmin.resources.banners.pages.edit-banner';

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
                ->label('Hapus Banner')
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
