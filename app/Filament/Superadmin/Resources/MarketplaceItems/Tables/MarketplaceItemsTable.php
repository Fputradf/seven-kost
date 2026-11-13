<?php

namespace App\Filament\Superadmin\Resources\MarketplaceItems\Tables;

use App\Models\MarketplaceItem;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MarketplaceItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Nama Barang')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('user.name')
                    ->label('Penjual')
                    ->searchable(),

                TextColumn::make('category')
                    ->label('Kategori')
                    ->badge()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->colors([
                        'success' => 'like_new',
                        'info' => 'good',
                        'warning' => 'fair',
                    ]),

                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'sold' => 'gray',
                        default => 'gray',
                    }),

                IconColumn::make('is_featured')
                    ->label('Unggulan')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Status Moderasi')
                    ->options([
                        'pending' => 'Menunggu Review',
                        'active' => 'Aktif (Disetujui)',
                        'rejected' => 'Ditolak',
                        'sold' => 'Terjual',
                    ]),

                SelectFilter::make('category')
                    ->label('Kategori')
                    ->options([
                        'furniture' => 'Furnitur',
                        'electronic' => 'Elektronik',
                        'kitchen' => 'Peralatan Dapur',
                        'vehicle' => 'Kendaraan',
                        'other' => 'Lain-lain',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Iklan Barang')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui dan menayangkan barang ini di marketplace?')
                    ->action(fn (MarketplaceItem $record) => $record->update(['status' => 'active']))
                    ->visible(fn (MarketplaceItem $record): bool => $record->status !== 'active'),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Iklan Barang')
                    ->modalDescription('Apakah Anda yakin ingin menolak penayangan barang ini di marketplace?')
                    ->action(fn (MarketplaceItem $record) => $record->update(['status' => 'rejected']))
                    ->visible(fn (MarketplaceItem $record): bool => $record->status !== 'rejected'),

                Action::make('toggle_featured')
                    ->label(fn (MarketplaceItem $record): string => $record->is_featured ? 'Batal Unggulan' : 'Set Unggulan')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(fn (MarketplaceItem $record) => $record->update(['is_featured' => ! $record->is_featured])),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
