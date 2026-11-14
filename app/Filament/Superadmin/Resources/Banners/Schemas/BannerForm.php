<?php

namespace App\Filament\Superadmin\Resources\Banners\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Banner Promosi')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('title')
                                    ->label('Judul / Label Banner')
                                    ->placeholder('Contoh: Promo Ramadhan Kos Putri Jogja')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('target_url')
                                    ->label('Target URL (Opsional)')
                                    ->placeholder('https:// atau /cari?provinsi=...')
                                    ->maxLength(255),

                                DateTimePicker::make('starts_at')
                                    ->label('Mulai Tayang'),

                                DateTimePicker::make('ends_at')
                                    ->label('Selesai Tayang'),

                                Toggle::make('is_active')
                                    ->label('Status Aktif Banner')
                                    ->default(true)
                                    ->inline(false)
                                    ->columnSpanFull(),

                                FileUpload::make('image_path')
                                    ->label('Gambar Banner (Rasio 16:9 atau 21:9)')
                                    ->directory('banners')
                                    ->image()
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
