<?php

namespace App\Filament\Resources\Kosts\Pages;

use App\Filament\Resources\Kosts\KostResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListKosts extends ListRecords
{
    protected static string $resource = KostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
