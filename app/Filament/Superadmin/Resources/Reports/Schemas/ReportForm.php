<?php

namespace App\Filament\Superadmin\Resources\Reports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->inlineLabel(false)
            ->components([
                Section::make('Informasi Laporan Pelanggaran')
                    ->inlineLabel(false)
                    ->columnSpanFull()
                    ->schema([
                        Grid::make(['default' => 1, 'sm' => 2])
                            ->schema([
                                Select::make('kost_id')
                                    ->relationship('kost', 'name')
                                    ->label('Kos Dilaporkan')
                                    ->searchable()
                                    ->preload()
                                    ->required(),

                                Select::make('user_id')
                                    ->relationship('reporter', 'name')
                                    ->label('Pelapor (User)')
                                    ->searchable()
                                    ->preload()
                                    ->nullable()
                                    ->placeholder('Pengunjung Anonim'),

                                TextInput::make('reason')
                                    ->label('Alasan Laporan')
                                    ->placeholder('Contoh: Foto tidak sesuai, kontak palsu, penipuan...')
                                    ->required()
                                    ->maxLength(255),

                                Select::make('status')
                                    ->label('Status Penanganan Laporan')
                                    ->options([
                                        'pending' => 'Pending (Perlu Ditinjau)',
                                        'reviewed' => 'Sedang Ditinjau',
                                        'resolved' => 'Selesai Ditindaklanjuti',
                                        'rejected' => 'Ditolak (Tidak Terbukti)',
                                    ])
                                    ->default('pending')
                                    ->required(),

                                Textarea::make('details')
                                    ->label('Rincian Keluhan Pelapor')
                                    ->placeholder('Kronologi atau penjelasan indikasi pelanggaran dari pelapor...')
                                    ->rows(3)
                                    ->columnSpanFull(),

                                Textarea::make('admin_notes')
                                    ->label('Catatan Tim Moderasi')
                                    ->placeholder('Tuliskan langkah investigasi, hasil konfirmasi pemilik, atau tindakan sanksi yang diambil...')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
