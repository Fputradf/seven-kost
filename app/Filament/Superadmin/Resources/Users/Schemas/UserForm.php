<?php

namespace App\Filament\Superadmin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Profil & Kontak')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                TextInput::make('name')
                                    ->label('Nama Lengkap')
                                    ->placeholder('Contoh: Budi Santoso')
                                    ->required()
                                    ->maxLength(255),

                                TextInput::make('email')
                                    ->label('Alamat Email')
                                    ->email()
                                    ->placeholder('user@sevenkos.com')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(ignoreRecord: true),

                                TextInput::make('phone')
                                    ->label('Nomor WhatsApp / Telepon')
                                    ->tel()
                                    ->placeholder('081234567890')
                                    ->maxLength(20),

                                TextInput::make('password')
                                    ->label('Kata Sandi')
                                    ->password()
                                    ->revealable()
                                    ->placeholder('Masukkan kata sandi')
                                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                                    ->dehydrated(fn (?string $state): bool => filled($state))
                                    ->required(fn (string $operation): bool => $operation === 'create')
                                    ->visible(fn (string $operation): bool => $operation === 'create')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Peran & Status Moderasi Akun')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('roles')
                                    ->label('Peran Akun')
                                    ->relationship('roles', 'name')
                                    ->preload()
                                    ->searchable()
                                    ->required(),

                                Select::make('status')
                                    ->label('Status Akun')
                                    ->options([
                                        'active' => 'Aktif (Normal)',
                                        'suspended' => 'Ditangguhkan (Suspend Sementara)',
                                        'banned' => 'Diblokir (Banned Permanen)',
                                    ])
                                    ->default('active')
                                    ->required()
                                    ->live(),

                                DateTimePicker::make('suspended_until')
                                    ->label('Batas Waktu Suspend Selesai')
                                    ->visible(fn (callable $get) => $get('status') === 'suspended')
                                    ->required(fn (callable $get) => $get('status') === 'suspended'),

                                Textarea::make('moderation_reason')
                                    ->label('Alasan Penindakan / Moderasi')
                                    ->visible(fn (callable $get) => in_array($get('status'), ['suspended', 'banned']))
                                    ->required(fn (callable $get) => in_array($get('status'), ['suspended', 'banned']))
                                    ->placeholder('Tuliskan alasan penindakan agar terdokumentasi dan transparan.')
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
