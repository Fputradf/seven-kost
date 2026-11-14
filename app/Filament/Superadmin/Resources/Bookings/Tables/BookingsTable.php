<?php

namespace App\Filament\Superadmin\Resources\Bookings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BookingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('tenant.name')
                    ->label('Penghuni')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn ($record): ?string => $record->tenant_phone ?: $record->tenant?->phone),

                TextColumn::make('kost.name')
                    ->label('Kos')
                    ->searchable()
                    ->limit(24),

                TextColumn::make('room.name')
                    ->label('Tipe Kamar')
                    ->default('-'),

                TextColumn::make('start_date')
                    ->label('Rencana Masuk')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('duration_months')
                    ->label('Durasi')
                    ->suffix(' Bln'),

                TextColumn::make('total_amount')
                    ->label('Total Biaya')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved', 'active' => 'success',
                        'rejected', 'cancelled' => 'danger',
                        'completed' => 'gray',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Menunggu Konfirmasi',
                        'approved' => 'Disetujui',
                        'active' => 'Sewa Aktif',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                    ]),
            ])
            ->recordActions([
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
            ])
            ->defaultSort('created_at', 'desc')
            ->searchPlaceholder('Cari penghuni, kos...')
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->emptyStateHeading('Tidak Ada Pengajuan Sewa')
            ->emptyStateDescription('Belum ada data pengajuan atau transaksi sewa yang terdaftar.');
    }
}
