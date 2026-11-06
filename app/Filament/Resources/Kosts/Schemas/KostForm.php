<?php

namespace App\Filament\Resources\Kosts\Schemas;

use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class KostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pemilik Kos')
                    ->options(User::pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Kos')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('Tipe Kos')
                    ->options([
                        'campur' => 'Campur',
                        'putri' => 'Putri',
                        'putra' => 'Putra',
                    ])
                    ->required(),

                Select::make('province_id')
                    ->label('Provinsi')
                    ->options(Province::pluck('name', 'id'))
                    ->searchable()
                    ->reactive()
                    ->required(),

                Select::make('regency_id')
                    ->label('Kabupaten / Kota')
                    ->options(function (callable $get) {
                        $provId = $get('province_id');
                        if (!$provId) {
                            return Regency::pluck('name', 'id');
                        }
                        return Regency::where('province_id', $provId)->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),

                TextInput::make('price_start')
                    ->label('Tarif Sewa (Rp)')
                    ->numeric()
                    ->required(),

                Textarea::make('address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->columnSpanFull(),

                Select::make('verification_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'draft' => 'Draft',
                        'pending' => 'Pending Review',
                        'verified' => 'Terverifikasi (Lencana)',
                        'rejected' => 'Ditolak',
                    ])
                    ->default('draft')
                    ->required(),

                Toggle::make('is_featured')
                    ->label('Iklan Unggulan')
                    ->default(false),

                Toggle::make('is_available')
                    ->label('Tersedia')
                    ->default(true),

                Textarea::make('rejection_reason')
                    ->label('Alasan Penolakan')
                    ->columnSpanFull(),
            ]);
    }
}
