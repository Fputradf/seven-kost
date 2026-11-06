<?php

namespace App\Filament\Resources\AdsPackages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdsPackagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('duration_days')
                    ->label('Durasi')
                    ->suffix(' Hari')
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Tarif Paket')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
