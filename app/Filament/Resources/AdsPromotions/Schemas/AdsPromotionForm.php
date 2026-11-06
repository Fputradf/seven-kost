<?php

namespace App\Filament\Resources\AdsPromotions\Schemas;

use App\Models\AdsPackage;
use App\Models\Kost;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AdsPromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('kost_id')
                    ->label('Listing Kos')
                    ->options(Kost::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('ads_package_id')
                    ->label('Paket Iklan')
                    ->options(AdsPackage::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->label('Status Promosi')
                    ->options([
                        'pending' => 'Pending (Menunggu Verifikasi/Bayar)',
                        'active' => 'Aktif (Sedang Berjalan)',
                        'expired' => 'Kedaluwarsa (Selesai)',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('pending')
                    ->required(),

                DateTimePicker::make('starts_at')
                    ->label('Mulai Tayang'),

                DateTimePicker::make('ends_at')
                    ->label('Berakhir Tayang'),

                FileUpload::make('proof_of_payment')
                    ->label('Bukti Transfer / Pembayaran')
                    ->directory('proofs/ads')
                    ->image(),

                Textarea::make('admin_notes')
                    ->label('Catatan Admin / Transaksi Manual')
                    ->placeholder('Nomor referensi mutasi bank, nama pengirim, atau alasan penolakan...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
