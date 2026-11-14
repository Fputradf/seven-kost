<?php

namespace App\Filament\Superadmin\Resources\Banners\Pages;

use App\Filament\Superadmin\Resources\Banners\BannerResource;
use Filament\Resources\Pages\ListRecords;

class ListBanners extends ListRecords
{
    protected static string $resource = BannerResource::class;

    protected string $view = 'filament.superadmin.resources.banners.pages.list-banners';

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
