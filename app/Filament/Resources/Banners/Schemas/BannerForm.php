<?php

namespace App\Filament\Resources\Banners\Schemas;

use App\Models\Regency;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BannerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul / Label Banner')
                    ->placeholder('Contoh: Promo Ramadhan Kos Putri Jogja')
                    ->required()
                    ->maxLength(255),

                TextInput::make('target_url')
                    ->label('Target URL (Opsional)')
                    ->placeholder('https:// atau /cari?provinsi=...')
                    ->maxLength(255),

                Select::make('regency_id')
                    ->label('Target Wilayah Khusus (Opsional)')
                    ->helperText('Kosongkan jika ingin tampil secara nasional di seluruh wilayah.')
                    ->options(Regency::pluck('name', 'id'))
                    ->searchable()
                    ->nullable(),

                FileUpload::make('image_path')
                    ->label('Gambar Banner (Rasio 16:9 atau 21:9)')
                    ->directory('banners')
                    ->image()
                    ->required()
                    ->columnSpanFull(),

                DateTimePicker::make('starts_at')
                    ->label('Mulai Tayang'),

                DateTimePicker::make('ends_at')
                    ->label('Selesai Tayang'),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),
            ]);
    }
}
