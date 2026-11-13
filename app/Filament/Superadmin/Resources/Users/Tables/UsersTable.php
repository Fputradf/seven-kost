<?php

namespace App\Filament\Superadmin\Resources\Users\Tables;

use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nama Pengguna')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->icon('heroicon-o-envelope'),

                TextColumn::make('phone')
                    ->label('Telepon / WA')
                    ->searchable()
                    ->default('-')
                    ->copyable(),

                TextColumn::make('roles.name')
                    ->label('Peran')
                    ->badge()
                    ->colors([
                        'danger' => 'superadmin',
                        'warning' => 'admin',
                        'info' => 'pemilik',
                        'success' => 'penghuni',
                        'primary' => 'mitra',
                        'gray' => 'pengunjung',
                    ]),

                TextColumn::make('status')
                    ->label('Status Akun')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'suspended' => 'Ditangguhkan',
                        'banned' => 'Diblokir',
                        default => ucfirst($state),
                    })
                    ->colors([
                        'success' => 'active',
                        'warning' => 'suspended',
                        'danger' => 'banned',
                    ]),

                TextColumn::make('suspended_until')
                    ->label('Batas Suspend')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('moderation_reason')
                    ->label('Alasan Tindakan')
                    ->limit(25)
                    ->placeholder('-')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Bergabung')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label('Filter Peran')
                    ->relationship('roles', 'name')
                    ->preload(),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'active' => 'Aktif',
                        'suspended' => 'Ditangguhkan (Suspend)',
                        'banned' => 'Diblokir (Banned)',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),

                // Aksi Cepat: Suspend Akun Pengguna
                Action::make('suspendUser')
                    ->label('Suspend')
                    ->icon('heroicon-o-pause-circle')
                    ->color('warning')
                    ->visible(fn (User $record): bool => $record->status !== 'suspended' &&
                        $record->id !== Auth::id() &&
                        ! $record->hasRole('superadmin')
                    )
                    ->form([
                        Radio::make('duration')
                            ->label('Pilih Durasi Penangguhan')
                            ->options([
                                '1_day' => '1 Hari',
                                '3_days' => '3 Hari',
                                '7_days' => '7 Hari (1 Minggu)',
                                '30_days' => '30 Hari (1 Bulan)',
                                'custom' => 'Pilih Tanggal & Jam Bebas',
                            ])
                            ->default('3_days')
                            ->required()
                            ->reactive(),

                        DateTimePicker::make('custom_date')
                            ->label('Batas Waktu Suspend')
                            ->visible(fn (callable $get) => $get('duration') === 'custom')
                            ->required(fn (callable $get) => $get('duration') === 'custom')
                            ->minDate(now()),

                        Textarea::make('moderation_reason')
                            ->label('Alasan Penangguhan')
                            ->required()
                            ->placeholder('Contoh: Terindikasi laporan iklan fiktif atau perilaku spam.'),
                    ])
                    ->action(function (User $record, array $data): void {
                        $until = match ($data['duration']) {
                            '1_day' => now()->addDay(),
                            '3_days' => now()->addDays(3),
                            '7_days' => now()->addWeek(),
                            '30_days' => now()->addDays(30),
                            'custom' => Carbon::parse($data['custom_date']),
                            default => now()->addDays(3),
                        };

                        $record->update([
                            'status' => 'suspended',
                            'suspended_until' => $until,
                            'moderation_reason' => $data['moderation_reason'],
                            'moderated_at' => now(),
                            'moderated_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Akun Berhasil Ditangguhkan')
                            ->body("Akun {$record->name} ditangguhkan hingga ".$until->translatedFormat('d M Y H:i'))
                            ->warning()
                            ->send();
                    }),

                // Aksi Cepat: Blokir Permanen (Banned)
                Action::make('banUser')
                    ->label('Blokir')
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->visible(fn (User $record): bool => $record->status !== 'banned' &&
                        $record->id !== Auth::id() &&
                        ! $record->hasRole('superadmin')
                    )
                    ->form([
                        Textarea::make('moderation_reason')
                            ->label('Alasan Pemblokiran Permanen')
                            ->required()
                            ->placeholder('Contoh: Penipuan transaksi sewa kos terbukti berulang.'),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'status' => 'banned',
                            'suspended_until' => null,
                            'moderation_reason' => $data['moderation_reason'],
                            'moderated_at' => now(),
                            'moderated_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Akun Berhasil Diblokir Permanen')
                            ->body("Akun {$record->name} telah diblokir secara permanen dari sistem Seven Kost.")
                            ->danger()
                            ->send();
                    }),

                // Aksi Cepat: Pulihkan / Aktifkan Kembali Akun
                Action::make('restoreUser')
                    ->label('Pulihkan')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (User $record): bool => $record->status !== 'active')
                    ->requiresConfirmation()
                    ->modalHeading('Pulihkan Akun Pengguna')
                    ->modalDescription('Apakah Anda yakin ingin memulihkan akun ini ke status aktif normal?')
                    ->action(function (User $record): void {
                        $record->update([
                            'status' => 'active',
                            'suspended_until' => null,
                            'moderation_reason' => null,
                            'moderated_at' => now(),
                            'moderated_by' => Auth::id(),
                        ]);

                        Notification::make()
                            ->title('Akun Berhasil Diaktifkan')
                            ->body("Akun {$record->name} kini kembali berstatus aktif.")
                            ->success()
                            ->send();
                    }),

                // Aksi Cepat: Ubah Peran (Assign Role)
                Action::make('changeRole')
                    ->label('Ubah Peran')
                    ->icon('heroicon-o-shield-check')
                    ->color('info')
                    ->visible(fn (User $record): bool => $record->id !== Auth::id())
                    ->form([
                        Select::make('roles')
                            ->label('Peran Baru')
                            ->options(Role::pluck('name', 'name'))
                            ->multiple()
                            ->default(fn (User $record): array => $record->roles->pluck('name')->toArray())
                            ->required(),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->syncRoles($data['roles']);

                        Notification::make()
                            ->title('Peran Berhasil Diperbarui')
                            ->body("Hak akses akun {$record->name} telah diperbarui.")
                            ->success()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
