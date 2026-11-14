<?php

namespace App\Filament\Superadmin\Widgets;

use App\Filament\Superadmin\Resources\Bookings\BookingResource;
use App\Models\Booking;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestBookingsTableWidget extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()->latest()->limit(5)
            )
            ->heading('Pengajuan Sewa & Transaksi Booking Terbaru')
            ->description('Daftar 5 aktivitas sewa kos terbaru yang diajukan oleh pengguna di platform')
            ->columns([
                TextColumn::make('tenant_name')
                    ->label('Penyewa')
                    ->searchable()
                    ->weight('bold')
                    ->default(fn (Booking $record): ?string => $record->tenant?->name),

                TextColumn::make('kost.name')
                    ->label('Nama Kos')
                    ->searchable(),

                TextColumn::make('duration_months')
                    ->label('Durasi')
                    ->formatStateUsing(fn ($state): string => "{$state} Bulan"),

                TextColumn::make('total_amount')
                    ->label('Nominal Transaksi')
                    ->money('IDR', locale: 'id'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved', 'active', 'completed' => 'success',
                        'pending' => 'warning',
                        'rejected', 'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Konfirmasi',
                        'approved' => 'Disetujui',
                        'active' => 'Sewa Berjalan',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak',
                        'cancelled' => 'Dibatalkan',
                        default => ucfirst($state),
                    }),

                TextColumn::make('created_at')
                    ->label('Waktu Pengajuan')
                    ->dateTime('d M Y, H:i'),
            ])
            ->recordActions([
                Action::make('view')
                    ->label('Detail')
                    ->button()
                    ->color('primary')
                    ->size('sm')
                    ->url(fn (Booking $record): string => BookingResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
