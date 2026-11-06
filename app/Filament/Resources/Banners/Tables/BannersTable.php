<?php

namespace App\Filament\Resources\Banners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('Banner')
                    ->disk('public'),

                TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('regency.name')
                    ->label('Target Wilayah')
                    ->default('Nasional (Semua)')
                    ->badge()
                    ->color(fn ($state) => $state === 'Nasional (Semua)' ? 'info' : 'primary'),

                TextColumn::make('click_count')
                    ->label('Total Klik')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                TextColumn::make('starts_at')
                    ->label('Mulai Tayang')
                    ->dateTime('d M Y')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('Selesai')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Keaktifan'),
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
