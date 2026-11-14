<?php

namespace App\Filament\Superadmin\Resources\AdsPromotions\Tables;

use App\Models\AdsPromotion;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdsPromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->columns([
                TextColumn::make('kost.name')
                    ->label('Nama Kos')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kost.owner.name')
                    ->label('Pemilik Kos')
                    ->searchable(),

                TextColumn::make('package.name')
                    ->label('Paket Iklan')
                    ->badge()
                    ->color('info'),

                TextColumn::make('package.price')
                    ->label('Tarif')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        'expired' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'pending' => 'Pending',
                        'rejected' => 'Ditolak',
                        'expired' => 'Kedaluwarsa',
                        default => ucfirst($state),
                    }),

                TextColumn::make('starts_at')
                    ->label('Periode Tayang')
                    ->formatStateUsing(function (AdsPromotion $record): string {
                        if (! $record->starts_at && ! $record->ends_at) {
                            return 'Belum diatur';
                        }
                        $start = $record->starts_at ? $record->starts_at->translatedFormat('d M Y') : '-';
                        $end = $record->ends_at ? $record->ends_at->translatedFormat('d M Y') : '-';

                        return "{$start} - {$end}";
                    })
                    ->description(function (AdsPromotion $record): ?string {
                        if ($record->status === 'active' && $record->ends_at) {
                            $days = (int) now()->diffInDays($record->ends_at, false);

                            return $days >= 0 ? "Sisa {$days} hari" : 'Telah berakhir';
                        }

                        return null;
                    }),

                ImageColumn::make('proof_of_payment')
                    ->label('Bukti Bayar')
                    ->disk('public'),

                TextColumn::make('created_at')
                    ->label('Tanggal Order')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'pending' => 'Pending',
                        'active' => 'Aktif',
                        'expired' => 'Kedaluwarsa',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->button()
                    ->color('success')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui & Aktifkan Iklan')
                    ->modalDescription('Apakah pembayaran sudah diverifikasi dan promosi kos ini ingin diaktifkan sekarang?')
                    ->modalSubmitActionLabel('Ya, Setujui & Aktifkan')
                    ->action(function (AdsPromotion $record) {
                        $duration = $record->package?->duration_days ?? 7;
                        $now = now();
                        $record->update([
                            'status' => 'active',
                            'starts_at' => $now,
                            'ends_at' => (clone $now)->addDays($duration),
                        ]);

                        if ($record->kost) {
                            $record->kost->update(['is_featured' => true]);
                        }

                        Notification::make()
                            ->title('Iklan Berhasil Diaktifkan')
                            ->body("Promosi untuk '{$record->kost?->name}' kini telah aktif tayang.")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (AdsPromotion $record): bool => $record->status !== 'active'),

                Action::make('reject')
                    ->label('Tolak')
                    ->button()
                    ->color('danger')
                    ->size('sm')
                    ->modalHeading('Tolak Pengajuan Iklan')
                    ->modalSubmitActionLabel('Tolak Pengajuan')
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan alasan penolakan (misal: bukti transfer tidak valid)...')
                            ->required(),
                    ])
                    ->action(function (AdsPromotion $record, array $data) {
                        $record->update([
                            'status' => 'rejected',
                            'admin_notes' => $data['admin_notes'],
                        ]);

                        if ($record->kost) {
                            $hasActivePromotions = AdsPromotion::where('kost_id', $record->kost_id)
                                ->where('id', '!=', $record->id)
                                ->where('status', 'active')
                                ->where('ends_at', '>', now())
                                ->exists();

                            if (! $hasActivePromotions) {
                                $record->kost->update(['is_featured' => false]);
                            }
                        }

                        Notification::make()
                            ->title('Pengajuan Iklan Ditolak')
                            ->body("Pengajuan promosi untuk '{$record->kost?->name}' telah ditolak.")
                            ->danger()
                            ->send();
                    })
                    ->visible(fn (AdsPromotion $record): bool => $record->status !== 'rejected'),

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
