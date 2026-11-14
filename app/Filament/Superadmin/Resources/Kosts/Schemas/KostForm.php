<?php

namespace App\Filament\Superadmin\Resources\Kosts\Schemas;

use App\Models\Province;
use App\Models\Regency;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Properti Kos')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Kos')
                                    ->placeholder('Contoh: Kos Putri Melati Hijau')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('user_id')
                                    ->label('Pemilik Kos')
                                    ->options(User::pluck('name', 'id'))
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('type')
                                    ->label('Tipe Kos')
                                    ->options([
                                        'campur' => 'Campur',
                                        'putri' => 'Putri',
                                        'putra' => 'Putra',
                                    ])
                                    ->required(),

                                TextInput::make('price_start')
                                    ->label('Tarif Sewa Mulai Dari (Rp)')
                                    ->numeric()
                                    ->required(),

                                Select::make('province_id')
                                    ->label('Provinsi')
                                    ->options(Province::pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->required(),

                                Select::make('regency_id')
                                    ->label('Kabupaten / Kota')
                                    ->options(function (callable $get) {
                                        $provId = $get('province_id');
                                        if (! $provId) {
                                            return Regency::pluck('name', 'id');
                                        }

                                        return Regency::where('province_id', $provId)->pluck('name', 'id');
                                    })
                                    ->searchable()
                                    ->required(),

                                Select::make('verification_status')
                                    ->label('Status Verifikasi')
                                    ->options([
                                        'draft' => 'Draft',
                                        'pending' => 'Pending Review',
                                        'verified' => 'Terverifikasi (Lencana)',
                                        'rejected' => 'Ditolak',
                                    ])
                                    ->default('draft')
                                    ->live()
                                    ->required(),

                                Toggle::make('is_available')
                                    ->label('Kamar Kos Tersedia')
                                    ->default(true)
                                    ->inline(false),

                                Toggle::make('is_featured')
                                    ->label('Jadikan Iklan Unggulan')
                                    ->default(false)
                                    ->inline(false),

                                Textarea::make('rejection_reason')
                                    ->label('Alasan Penolakan')
                                    ->placeholder('Tuliskan alasan jika pengajuan kos ditolak...')
                                    ->rows(3)
                                    ->visible(fn (callable $get) => $get('verification_status') === 'rejected')
                                    ->columnSpanFull(),

                                Textarea::make('address')
                                    ->label('Alamat Lengkap Kos')
                                    ->placeholder('Tuliskan nama jalan, nomor bangunan, patokan, RT/RW...')
                                    ->rows(3)
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
