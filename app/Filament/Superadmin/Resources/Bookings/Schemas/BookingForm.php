<?php

namespace App\Filament\Superadmin\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Pengajuan & Sewa Kos')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('user_id')
                                    ->label('Penyewa (User)')
                                    ->relationship('tenant', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('kost_id')
                                    ->label('Kos')
                                    ->relationship('kost', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('room_id')
                                    ->label('Tipe Kamar')
                                    ->relationship('room', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->nullable(),

                                Select::make('status')
                                    ->label('Status Sewa')
                                    ->options([
                                        'pending' => 'Menunggu Konfirmasi (Pending)',
                                        'approved' => 'Disetujui (Approved)',
                                        'rejected' => 'Ditolak (Rejected)',
                                        'active' => 'Sewa Aktif (Active)',
                                        'completed' => 'Selesai (Completed)',
                                        'cancelled' => 'Dibatalkan (Cancelled)',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                DatePicker::make('start_date')
                                    ->label('Tanggal Masuk')
                                    ->required(),

                                DatePicker::make('end_date')
                                    ->label('Tanggal Selesai'),

                                TextInput::make('duration_months')
                                    ->label('Durasi (Bulan)')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),

                                TextInput::make('total_amount')
                                    ->label('Total Biaya (Rp)')
                                    ->numeric()
                                    ->required(),

                                Textarea::make('notes')
                                    ->label('Catatan Tambahan')
                                    ->placeholder('Tuliskan catatan khusus atau instruksi transaksi ini jika diperlukan.')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
