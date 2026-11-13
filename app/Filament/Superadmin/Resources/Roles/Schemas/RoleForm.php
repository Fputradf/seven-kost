<?php

namespace App\Filament\Superadmin\Resources\Roles\Schemas;

use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Permission;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Peran (Role)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->placeholder('Contoh: superadmin, admin, verifikator, pemilik'),

                TextInput::make('guard_name')
                    ->label('Guard')
                    ->default('web')
                    ->required()
                    ->maxLength(255),

                CheckboxList::make('permissions')
                    ->label('Daftar Hak Akses (Permissions)')
                    ->relationship('permissions', 'name')
                    ->options(fn () => Permission::pluck('name', 'id'))
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
