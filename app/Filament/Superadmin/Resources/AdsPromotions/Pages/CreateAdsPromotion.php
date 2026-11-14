<?php

namespace App\Filament\Superadmin\Resources\AdsPromotions\Pages;

use App\Filament\Superadmin\Resources\AdsPromotions\AdsPromotionResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;

class CreateAdsPromotion extends CreateRecord
{
    protected static string $resource = AdsPromotionResource::class;

    protected string $view = 'filament.superadmin.resources.ads-promotions.pages.create-ads-promotion';

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
                ->label('Simpan Pengajuan')
                ->color('primary')
                ->icon('heroicon-m-check'),
        ];
    }
}
