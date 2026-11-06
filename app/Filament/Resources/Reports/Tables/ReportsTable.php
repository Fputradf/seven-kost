<?php

namespace App\Filament\Resources\Reports\Tables;

use App\Models\Report;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kost.name')
                    ->label('Kos Dilaporkan')
                    ->searchable()
                    ->weight('bold'),

                TextColumn::make('reason')
                    ->label('Alasan Laporan')
                    ->badge(),

                TextColumn::make('details')
                    ->label('Rincian')
                    ->limit(40),

                TextColumn::make('reporter.name')
                    ->label('Pelapor')
                    ->default('Pengunjung Anonim'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'resolved' => 'success',
                        'reviewed' => 'info',
                        'rejected' => 'gray',
                        default => 'danger',
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu Lapor')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending (Perlu Tindakan)',
                        'reviewed' => 'Sedang Ditinjau',
                        'resolved' => 'Selesai Ditindaklanjuti',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('resolve')
                    ->label('Tindaklanjuti')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Report $record) => $record->update(['status' => 'resolved'])),

                Action::make('add_note')
                    ->label('Beri Catatan')
                    ->icon('heroicon-o-pencil-square')
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Catatan Tim Moderasi')
                            ->required(),
                    ])
                    ->action(function (Report $record, array $data) {
                        $record->update([
                            'admin_notes' => $data['admin_notes'],
                            'status' => 'reviewed',
                        ]);
                    }),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
