<?php

namespace App\Filament\Superadmin\Resources\MarketplaceItems\Pages;

use App\Filament\Superadmin\Resources\MarketplaceItems\MarketplaceItemResource;
use Filament\Resources\Pages\ListRecords;

class ListMarketplaceItems extends ListRecords
{
    protected static string $resource = MarketplaceItemResource::class;

    protected string $view = 'filament.superadmin.resources.marketplace-items.pages.list-marketplace-items';

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
