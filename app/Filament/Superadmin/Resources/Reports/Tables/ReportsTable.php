<?php

namespace App\Filament\Superadmin\Resources\Reports\Tables;

use App\Models\Report;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->columns([
                TextColumn::make('kost.name')
                    ->label('Kos Dilaporkan')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('reporter.name')
                    ->label('Pelapor')
                    ->default('Pengunjung Anonim')
                    ->searchable(),

                TextColumn::make('reason')
                    ->label('Alasan Laporan')
                    ->badge(),

                TextColumn::make('details')
                    ->label('Rincian')
                    ->limit(35),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'resolved' => 'success',
                        'reviewed' => 'info',
                        'rejected' => 'gray',
                        default => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pending',
                        'reviewed' => 'Sedang Ditinjau',
                        'resolved' => 'Selesai',
                        'rejected' => 'Ditolak',
                        default => ucfirst($state),
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu Lapor')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending (Perlu Ditinjau)',
                        'reviewed' => 'Sedang Ditinjau',
                        'resolved' => 'Selesai Ditindaklanjuti',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('resolve')
                    ->label('Selesai')
                    ->button()
                    ->color('success')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Tandai Laporan Selesai')
                    ->modalDescription('Apakah laporan ini sudah selesai diinvestigasi dan ditindaklanjuti?')
                    ->modalSubmitActionLabel('Ya, Selesai Ditindaklanjuti')
                    ->action(function (Report $record): void {
                        $record->update(['status' => 'resolved']);

                        Notification::make()
                            ->title('Laporan Ditandai Selesai')
                            ->body('Status laporan berhasil diperbarui menjadi selesai.')
                            ->success()
                            ->send();
                    })
                    ->visible(fn (Report $record): bool => $record->status !== 'resolved'),

                Action::make('reject')
                    ->label('Tolak')
                    ->button()
                    ->color('danger')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Laporan')
                    ->modalDescription('Apakah Anda yakin ingin menolak laporan ini (tidak terbukti / tidak valid)?')
                    ->modalSubmitActionLabel('Ya, Tolak Laporan')
                    ->action(function (Report $record): void {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('Laporan Ditolak')
                            ->body('Status laporan berhasil diperbarui menjadi ditolak.')
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (Report $record): bool => $record->status !== 'rejected'),

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
