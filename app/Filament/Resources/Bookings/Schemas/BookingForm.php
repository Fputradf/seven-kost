<?php

namespace App\Filament\Resources\Bookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('status')
                    ->label('Status Sewa')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Tanggal Masuk')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Tanggal Selesai'),

                TextInput::make('duration_months')
                    ->label('Durasi (Bulan)')
                    ->numeric()
                    ->required(),

                TextInput::make('total_amount')
                    ->label('Total Biaya (Rp)')
                    ->numeric()
                    ->required(),

                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }
}
