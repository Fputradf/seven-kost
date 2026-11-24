<?php

namespace App\Filament\Superadmin\Resources\KycVerifications\Pages;

use App\Filament\Superadmin\Resources\KycVerifications\KycVerificationResource;
use Filament\Resources\Pages\ListRecords;

class ListKycVerifications extends ListRecords
{
    protected static string $resource = KycVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
