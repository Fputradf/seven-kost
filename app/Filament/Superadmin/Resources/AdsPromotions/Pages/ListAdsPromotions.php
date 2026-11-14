<?php

namespace App\Filament\Superadmin\Resources\AdsPromotions\Pages;

use App\Filament\Superadmin\Resources\AdsPromotions\AdsPromotionResource;
use Filament\Resources\Pages\ListRecords;

class ListAdsPromotions extends ListRecords
{
    protected static string $resource = AdsPromotionResource::class;

    protected string $view = 'filament.superadmin.resources.ads-promotions.pages.list-ads-promotions';

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
