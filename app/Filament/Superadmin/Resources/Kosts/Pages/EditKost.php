<?php

namespace App\Filament\Superadmin\Resources\Kosts\Pages;

use App\Filament\Superadmin\Resources\Kosts\KostResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKost extends EditRecord
{
    protected static string $resource = KostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
