<?php

namespace App\Filament\Superadmin\Resources\Banners\Tables;

use App\Models\Banner;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class BannersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Banner')
                    ->height(36)
                    ->extraImgAttributes([
                        'style' => 'width: 72px; height: 36px; object-fit: cover; border-radius: 0.375rem; border: 1px solid #e2e8f0;',
                    ]),

                TextColumn::make('title')
                    ->label('Judul Banner')
                    ->searchable()
                    ->weight('bold')
                    ->limit(22)
                    ->tooltip(fn (Banner $record): string => $record->title),

                TextColumn::make('pengaju')
                    ->label('Pengaju')
                    ->state(function (Banner $record): string {
                        if ($record->user?->hasRole('superadmin') || $record->advertiser_name === 'Super Admin' || (! $record->user_id && ! $record->advertiser_name)) {
                            return 'Super Admin';
                        }

                        if ($record->user) {
                            return $record->user->name;
                        }

                        return $record->advertiser_name ?: 'Super Admin';
                    })
                    ->badge()
                    ->limit(16)
                    ->tooltip(function (Banner $record): string {
                        if ($record->user?->hasRole('superadmin') || $record->advertiser_name === 'Super Admin' || (! $record->user_id && ! $record->advertiser_name)) {
                            return 'Super Admin (Internal)';
                        }

                        return ($record->user?->name ?: $record->advertiser_name).' (Pemilik Kos)';
                    })
                    ->color(function (Banner $record): string {
                        if ($record->user?->hasRole('superadmin') || $record->advertiser_name === 'Super Admin' || (! $record->user_id && ! $record->advertiser_name)) {
                            return 'info';
                        }

                        return 'primary';
                    }),

                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => $state ? 'Aktif' : 'Menunggu Persetujuan')
                    ->color(fn ($state): string => $state ? 'success' : 'warning'),

                TextColumn::make('starts_at')
                    ->label('Periode')
                    ->date('d M Y')
                    ->description(fn (Banner $record): string => $record->ends_at ? 's/d '.$record->ends_at->format('d M Y') : 'Seterusnya')
                    ->sortable(),

                TextColumn::make('click_count')
                    ->label('Total Klik')
                    ->suffix(' x')
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Filter Status')
                    ->placeholder('Semua Banner')
                    ->trueLabel('Aktif Tayang')
                    ->falseLabel('Menunggu Persetujuan'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Setujui')
                    ->button()
                    ->color('success')
                    ->size('sm')
                    ->visible(fn (Banner $record): bool => ! $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Setujui & Terbitkan Banner')
                    ->modalDescription('Apakah banner ini sudah sesuai dan siap ditayangkan di beranda?')
                    ->modalSubmitActionLabel('Ya, Setujui & Terbitkan')
                    ->action(function (Banner $record): void {
                        $record->update([
                            'is_active' => true,
                            'starts_at' => $record->starts_at ?: now(),
                            'ends_at' => $record->ends_at ?: now()->addDays(30),
                        ]);

                        Notification::make()
                            ->title('Banner Berhasil Disetujui')
                            ->body("Banner '{$record->title}' kini aktif tayang di beranda Seven Kost.")
                            ->success()
                            ->send();
                    }),

                Action::make('deactivate')
                    ->label('Nonaktifkan')
                    ->button()
                    ->color('gray')
                    ->size('sm')
                    ->visible(fn (Banner $record): bool => (bool) $record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Nonaktifkan Banner')
                    ->modalDescription('Banner ini tidak akan lagi tampil di beranda. Lanjutkan?')
                    ->modalSubmitActionLabel('Ya, Nonaktifkan')
                    ->action(function (Banner $record): void {
                        $record->update(['is_active' => false]);

                        Notification::make()
                            ->title('Banner Dinonaktifkan')
                            ->body("Banner '{$record->title}' telah dinonaktifkan.")
                            ->warning()
                            ->send();
                    }),

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
            ->searchPlaceholder('Cari judul banner...')
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10])
            ->emptyStateHeading('Tidak Ada Banner')
            ->emptyStateDescription('Belum ada banner promosi yang didaftarkan.');
    }
}
