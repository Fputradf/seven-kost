<?php

namespace App\Filament\Superadmin\Resources\Kosts\Tables;

use App\Models\Kost;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kos')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('owner.name')
                    ->label('Pemilik')
                    ->searchable(),

                TextColumn::make('regency.name')
                    ->label('Kab/Kota')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->formatStateUsing(fn (?string $state): string => ucfirst((string) $state)),

                TextColumn::make('price_start')
                    ->label('Mulai Dari')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                TextColumn::make('verification_status')
                    ->label('Verifikasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'verified' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Menunggu Verifikasi',
                        'verified' => 'Terverifikasi (Lencana)',
                        'rejected' => 'Ditolak',
                    ]),

                SelectFilter::make('type')
                    ->label('Tipe Kos')
                    ->options([
                        'putra' => 'Putra',
                        'putri' => 'Putri',
                        'campur' => 'Campur',
                    ]),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->button()
                    ->color('success')
                    ->size('sm')
                    ->requiresConfirmation()
                    ->modalHeading('Verifikasi & Beri Lencana Resmi')
                    ->modalDescription('Apakah Anda yakin data kos ini valid dan siap diberi lencana terverifikasi?')
                    ->action(function (Kost $record) {
                        $record->update([
                            'verification_status' => 'verified',
                            'rejection_reason' => null,
                        ]);
                    })
                    ->visible(fn (Kost $record): bool => $record->verification_status !== 'verified'),

                Action::make('reject')
                    ->label('Tolak')
                    ->button()
                    ->color('danger')
                    ->size('sm')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan mengapa iklan kos ini ditolak...')
                            ->required(),
                    ])
                    ->action(function (Kost $record, array $data) {
                        $record->update([
                            'verification_status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                    })
                    ->visible(fn (Kost $record): bool => $record->verification_status !== 'rejected'),

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
