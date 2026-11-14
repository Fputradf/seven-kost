<?php

namespace App\Filament\Superadmin\Resources\AdsPromotions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AdsPromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Pengajuan Iklan Kos')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('kost_id')
                                    ->label('Listing Kos')
                                    ->relationship('kost', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('ads_package_id')
                                    ->label('Paket Iklan')
                                    ->relationship('package', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Promosi')
                                    ->options([
                                        'pending' => 'Pending (Menunggu Verifikasi)',
                                        'active' => 'Aktif (Sedang Berjalan)',
                                        'expired' => 'Kedaluwarsa (Selesai)',
                                        'rejected' => 'Ditolak',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                FileUpload::make('proof_of_payment')
                                    ->label('Bukti Transfer / Pembayaran')
                                    ->directory('proofs/ads')
                                    ->image(),

                                DateTimePicker::make('starts_at')
                                    ->label('Mulai Tayang'),

                                DateTimePicker::make('ends_at')
                                    ->label('Berakhir Tayang'),

                                Textarea::make('admin_notes')
                                    ->label('Catatan Admin / Transaksi Manual')
                                    ->placeholder('Nomor referensi mutasi bank, nama pengirim, atau alasan penolakan...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
