<?php

namespace App\Filament\Resources\Bookings\Tables;

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
                    ->weight('bold'),

                TextColumn::make('tenant_phone')
                    ->label('No. WA')
                    ->searchable(),

                TextColumn::make('kost.name')
                    ->label('Kos')
                    ->searchable(),

                TextColumn::make('room.name')
                    ->label('Tipe Kamar'),

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
                    ->colors([
                        'warning' => 'pending',
                        'success' => fn ($state) => in_array($state, ['approved', 'active']),
                        'danger' => fn ($state) => in_array($state, ['rejected', 'cancelled']),
                        'gray' => 'completed',
                    ]),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'active' => 'Active',
                        'completed' => 'Completed',
                    ]),
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
