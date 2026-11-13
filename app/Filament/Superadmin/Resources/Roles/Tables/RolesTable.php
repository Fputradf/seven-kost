<?php

namespace App\Filament\Superadmin\Resources\Roles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RolesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Peran')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->badge()
                    ->colors([
                        'danger' => 'superadmin',
                        'warning' => 'admin',
                        'info' => 'pemilik',
                        'success' => 'penghuni',
                        'primary' => 'mitra',
                    ]),

                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('users_count')
                    ->label('Jumlah Pengguna')
                    ->counts('users')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('permissions_count')
                    ->label('Jumlah Hak Akses')
                    ->counts('permissions')
                    ->badge()
                    ->color('secondary')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (Role $record): bool => ! in_array($record->name, ['superadmin', 'admin', 'pemilik', 'penghuni'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
