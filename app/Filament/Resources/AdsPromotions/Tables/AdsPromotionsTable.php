<?php

namespace App\Filament\Resources\AdsPromotions\Tables;

use App\Models\AdsPromotion;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AdsPromotionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kost.name')
                    ->label('Nama Kos')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('kost.owner.name')
                    ->label('Pemilik Kos')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                    }),

                TextColumn::make('starts_at')
                    ->label('Mulai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                TextColumn::make('ends_at')
                    ->label('Berakhir')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

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
                        'pending' => 'Pending (Menunggu Verifikasi)',
                        'active' => 'Aktif',
                        'expired' => 'Kedaluwarsa',
                        'rejected' => 'Ditolak',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Aktifkan Iklan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Aktifkan Iklan Unggulan')
                    ->modalDescription('Apakah pembayaran sudah diverifikasi dan promosi kos ini ingin diaktifkan sekarang?')
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
                    })
                    ->visible(fn (AdsPromotion $record): bool => $record->status !== 'active'),

                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Alasan Penolakan / Catatan')
                            ->placeholder('Jelaskan alasan (misal bukti transfer tidak valid/belum masuk)...')
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

                            if (!$hasActivePromotions) {
                                $record->kost->update(['is_featured' => false]);
                            }
                        }
                    })
                    ->visible(fn (AdsPromotion $record): bool => $record->status !== 'rejected'),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
