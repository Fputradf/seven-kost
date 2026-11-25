<?php

namespace App\Filament\Superadmin\Resources\KycVerifications\Pages;

use App\Filament\Superadmin\Resources\KycVerifications\KycVerificationResource;
use Filament\Resources\Pages\ListRecords;

class ListKycVerifications extends ListRecords
{
    protected static string $resource = KycVerificationResource::class;

    protected string $view = 'filament.superadmin.resources.kyc-verifications.pages.list-kyc-verifications';

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
