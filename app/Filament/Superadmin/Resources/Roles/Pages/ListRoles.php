<?php

namespace App\Filament\Superadmin\Resources\Roles\Pages;

use App\Filament\Superadmin\Resources\Roles\RoleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoles extends ListRecords
{
    protected static string $resource = RoleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Tambah Peran Baru'),
        ];
    }
}
