<?php

namespace App\Filament\Superadmin\Resources\MarketplaceItems\Tables;

use App\Models\MarketplaceItem;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MarketplaceItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->columns([
                ImageColumn::make('primary_image_url')
                    ->label('Foto')
                    ->circular(),

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
                    ->color('info')
                    ->formatStateUsing(fn (MarketplaceItem $record): string => $record->category_label),

                TextColumn::make('price')
                    ->label('Harga')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru', 'bekas_seperti_baru' => 'success',
                        'bekas_baik' => 'info',
                        default => 'warning',
                    })
                    ->formatStateUsing(fn (MarketplaceItem $record): string => $record->condition_label),

                TextColumn::make('city')
                    ->label('Kota')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('Status Moderasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'sold' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif (Disetujui)',
                        'pending' => 'Pending',
                        'rejected' => 'Ditolak',
                        'sold' => 'Terjual',
                        default => ucfirst($state),
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending (Menunggu Review)',
                        'active' => 'Aktif (Disetujui)',
                        'rejected' => 'Ditolak',
                        'sold' => 'Terjual',
                    ]),

                SelectFilter::make('category')
                    ->label('Filter Kategori')
                    ->options([
                        'kasur' => 'Kasur',
                        'kipas' => 'Kipas Angin',
                        'rak' => 'Rak & Lemari',
                        'perabot' => 'Perabot Kamar',
                        'elektronik' => 'Elektronik Kecil',
                        'buku' => 'Buku & Kuliah',
                        'other' => 'Lain-lain',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->button()
                    ->color('success')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Barang Marketplace')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui barang ini untuk tampil aktif di katalog Marketplace?')
                    ->modalSubmitActionLabel('Ya, Setujui & Terbitkan')
                    ->action(function (MarketplaceItem $record): void {
                        $record->update(['status' => 'active']);

                        Notification::make()
                            ->title('Barang Berhasil Disetujui')
                            ->body("Barang '{$record->title}' kini aktif tayang di marketplace.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (MarketplaceItem $record): bool => $record->status !== 'active'),

                Action::make('reject')
                    ->label('Tolak')
                    ->button()
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Barang Marketplace')
                    ->modalDescription('Apakah Anda yakin ingin menolak penayangan barang ini di marketplace?')
                    ->modalSubmitActionLabel('Ya, Tolak')
                    ->action(function (MarketplaceItem $record): void {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Barang Ditolak')
                            ->body("Barang '{$record->title}' telah ditolak.")
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (MarketplaceItem $record): bool => $record->status !== 'rejected'),

                EditAction::make()
                    ->label('Edit')
                    ->button()
                    ->color('primary')
                    ->size('sm'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
