<?php

namespace App\Filament\Superadmin\Resources\Kosts\Pages;

use App\Filament\Superadmin\Resources\Kosts\KostResource;
use Filament\Resources\Pages\ListRecords;

class ListKosts extends ListRecords
{
    protected static string $resource = KostResource::class;

    protected string $view = 'filament.superadmin.resources.kosts.pages.list-kosts';

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
