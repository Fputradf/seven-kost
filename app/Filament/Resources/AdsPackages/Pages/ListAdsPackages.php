<?php

namespace App\Filament\Resources\AdsPackages\Pages;

use App\Filament\Resources\AdsPackages\AdsPackageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdsPackages extends ListRecords
{
    protected static string $resource = AdsPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
