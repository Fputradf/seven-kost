<?php

namespace App\Filament\Superadmin\Resources\MarketplaceItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MarketplaceItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Barang Marketplace')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Nama Barang')
                                    ->placeholder('Contoh: Kipas Angin Dinding Cosmos')
                                    ->required()
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Penjual (User)')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Moderasi')
                                    ->options([
                                        'pending' => 'Menunggu Review (Pending)',
                                        'active' => 'Aktif (Disetujui)',
                                        'rejected' => 'Ditolak',
                                        'sold' => 'Terjual',
                                    ])
                                    ->default('active')
                                    ->required(),

                                Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'kasur' => 'Kasur',
                                        'kipas' => 'Kipas Angin',
                                        'rak' => 'Rak & Lemari',
                                        'perabot' => 'Perabot Kamar',
                                        'elektronik' => 'Elektronik Kecil',
                                        'buku' => 'Buku & Kuliah',
                                        'other' => 'Lain-lain',
                                    ])
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Harga (Rp)')
                                    ->numeric()
                                    ->required(),

                                Select::make('condition')
                                    ->label('Kondisi Barang')
                                    ->options([
                                        'baru' => 'Baru',
                                        'bekas_seperti_baru' => 'Bekas (Mulus / Seperti Baru)',
                                        'bekas_baik' => 'Bekas (Layak Pakai / Baik)',
                                        'bekas_cukup' => 'Bekas (Cukup)',
                                    ])
                                    ->required(),

                                TextInput::make('city')
                                    ->label('Kota')
                                    ->placeholder('Contoh: Sleman, Yogyakarta')
                                    ->maxLength(255),

                                TextInput::make('meetup_location')
                                    ->label('Lokasi COD / Temu')
                                    ->placeholder('Contoh: Sekitar Kampus UGM / Depan Indomaret')
                                    ->maxLength(255)
                                    ->columnSpanFull(),

                                Toggle::make('is_featured')
                                    ->label('Jadikan Produk Unggulan')
                                    ->default(false)
                                    ->inline(false)
                                    ->columnSpanFull(),

                                Textarea::make('description')
                                    ->label('Deskripsi Barang')
                                    ->placeholder('Tuliskan rincian kelengkapan barang, minus fisik jika ada, atau informasi penting lainnya...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
