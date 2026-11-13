<?php

namespace App\Filament\Superadmin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required()
                    ->maxLength(255),

                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('phone')
                    ->label('Nomor WhatsApp / HP')
                    ->tel()
                    ->maxLength(20),

                TextInput::make('password')
                    ->label('Kata Sandi')
                    ->password()
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->maxLength(255)
                    ->helperText('Kosongkan jika tidak ingin mengubah kata sandi.'),

                Select::make('roles')
                    ->label('Peran (Role)')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->options(Role::pluck('name', 'id'))
                    ->required(),

                Select::make('status')
                    ->label('Status Akun')
                    ->options([
                        'active' => 'Aktif Normal',
                        'suspended' => 'Ditangguhkan (Suspend Sementara)',
                        'banned' => 'Diblokir Permanen (Banned)',
                    ])
                    ->default('active')
                    ->required()
                    ->reactive(),

                DateTimePicker::make('suspended_until')
                    ->label('Batas Waktu Penangguhan (Suspend Selesai)')
                    ->visible(fn (callable $get) => $get('status') === 'suspended')
                    ->required(fn (callable $get) => $get('status') === 'suspended')
                    ->helperText('Setelah waktu ini, akun akan otomatis kembali aktif.'),

                Textarea::make('moderation_reason')
                    ->label('Alasan Penangguhan / Pemblokiran')
                    ->visible(fn (callable $get) => in_array($get('status'), ['suspended', 'banned']))
                    ->required(fn (callable $get) => in_array($get('status'), ['suspended', 'banned']))
                    ->placeholder('Tuliskan alasan penindakan agar dapat dilihat oleh pengguna.')
                    ->columnSpanFull(),
            ]);
    }
}
