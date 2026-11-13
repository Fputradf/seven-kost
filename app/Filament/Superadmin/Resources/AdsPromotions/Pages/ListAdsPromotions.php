<?php

namespace App\Filament\Superadmin\Resources\AdsPromotions\Pages;

use App\Filament\Superadmin\Resources\AdsPromotions\AdsPromotionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdsPromotions extends ListRecords
{
    protected static string $resource = AdsPromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
