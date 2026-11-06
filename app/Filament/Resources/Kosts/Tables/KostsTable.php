<?php

namespace App\Filament\Resources\Kosts\Tables;

use App\Models\Kost;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class KostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Kos')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('owner.name')
                    ->label('Pemilik')
                    ->searchable(),

                TextColumn::make('province.name')
                    ->label('Provinsi')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('regency.name')
                    ->label('Kab/Kota')
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipe')
                    ->badge()
                    ->colors([
                        'info' => 'putra',
                        'danger' => 'putri',
                        'warning' => 'campur',
                    ]),

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
                // Aksi Verifikasi & Beri Lencana
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
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

                // Aksi Tolak Listing dengan Alasan
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->form([
                        Textarea::make('rejection_reason')
                            ->label('Alasan Penolakan')
                            ->placeholder('Jelaskan mengapa iklan ini ditolak (misal: foto buram, alamat tidak jelas)...')
                            ->required(),
                    ])
                    ->action(function (Kost $record, array $data) {
                        $record->update([
                            'verification_status' => 'rejected',
                            'rejection_reason' => $data['rejection_reason'],
                        ]);
                    })
                    ->visible(fn (Kost $record): bool => $record->verification_status !== 'rejected'),

                // Aksi Jadikan Unggulan
                Action::make('toggle_featured')
                    ->label(fn (Kost $record): string => $record->is_featured ? 'Batal Unggulan' : 'Set Unggulan')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->action(fn (Kost $record) => $record->update(['is_featured' => !$record->is_featured])),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
