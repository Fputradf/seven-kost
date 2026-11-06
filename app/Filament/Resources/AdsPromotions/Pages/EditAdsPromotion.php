<?php

namespace App\Filament\Resources\AdsPromotions\Pages;

use App\Filament\Resources\AdsPromotions\AdsPromotionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdsPromotion extends EditRecord
{
    protected static string $resource = AdsPromotionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
