<?php

namespace App\Filament\Resources\Reports\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reason')
                    ->label('Alasan')
                    ->disabled(),

                Select::make('status')
                    ->label('Status Laporan')
                    ->options([
                        'pending' => 'Pending',
                        'reviewed' => 'Reviewed',
                        'resolved' => 'Resolved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),

                Textarea::make('details')
                    ->label('Rincian Keluhan Pelapor')
                    ->disabled()
                    ->columnSpanFull(),

                Textarea::make('admin_notes')
                    ->label('Catatan Tim Moderasi')
                    ->columnSpanFull(),
            ]);
    }
}
