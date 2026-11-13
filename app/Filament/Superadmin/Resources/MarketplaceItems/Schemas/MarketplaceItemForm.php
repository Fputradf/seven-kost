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
            ->components([
                Section::make('Informasi Produk Marketplace')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->label('Penjual (User)')
                                    ->required()
                                    ->searchable(),

                                TextInput::make('title')
                                    ->label('Nama Barang')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('category')
                                    ->label('Kategori')
                                    ->options([
                                        'furniture' => 'Furnitur',
                                        'electronic' => 'Elektronik',
                                        'kitchen' => 'Peralatan Dapur',
                                        'vehicle' => 'Kendaraan',
                                        'other' => 'Lain-lain',
                                    ])
                                    ->required(),

                                TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Select::make('condition')
                                    ->label('Kondisi Barang')
                                    ->options([
                                        'like_new' => 'Seperti Baru',
                                        'good' => 'Bagus / Layak Pakai',
                                        'fair' => 'Cukup',
                                    ])
                                    ->required(),

                                TextInput::make('city')
                                    ->label('Kota')
                                    ->maxLength(255),

                                TextInput::make('meetup_location')
                                    ->label('Lokasi COD / Temu')
                                    ->maxLength(255),

                                Select::make('status')
                                    ->label('Status Moderasi')
                                    ->options([
                                        'pending' => 'Menunggu Review',
                                        'active' => 'Aktif (Disetujui)',
                                        'rejected' => 'Ditolak',
                                        'sold' => 'Terjual',
                                    ])
                                    ->default('active')
                                    ->required(),

                                Toggle::make('is_featured')
                                    ->label('Produk Unggulan')
                                    ->default(false),
                            ]),

                        Textarea::make('description')
                            ->label('Deskripsi Barang')
                            ->rows(4),
                    ]),
            ]);
    }
}
