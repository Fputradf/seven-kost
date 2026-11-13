<?php

namespace App\Filament\Superadmin\Resources\AdsPackages\Pages;

use App\Filament\Superadmin\Resources\AdsPackages\AdsPackageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdsPackage extends EditRecord
{
    protected static string $resource = AdsPackageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
