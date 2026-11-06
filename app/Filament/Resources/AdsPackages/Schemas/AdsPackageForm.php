<?php

namespace App\Filament\Resources\AdsPackages\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AdsPackageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Paket')
                    ->required()
                    ->maxLength(255),

                TextInput::make('duration_days')
                    ->label('Durasi (Hari)')
                    ->numeric()
                    ->default(7)
                    ->required(),

                TextInput::make('price')
                    ->label('Tarif Paket (Rp)')
                    ->numeric()
                    ->prefix('Rp')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true),

                Textarea::make('description')
                    ->label('Deskripsi Keunggulan / Fitur')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
