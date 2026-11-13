<?php

namespace App\Filament\Superadmin\Resources\Bookings;

use App\Filament\Superadmin\Resources\Bookings\Pages\CreateBooking;
use App\Filament\Superadmin\Resources\Bookings\Pages\EditBooking;
use App\Filament\Superadmin\Resources\Bookings\Pages\ListBookings;
use App\Filament\Superadmin\Resources\Bookings\Schemas\BookingForm;
use App\Filament\Superadmin\Resources\Bookings\Tables\BookingsTable;
use App\Models\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationLabel = 'Data Pengajuan & Sewa';

    protected static ?string $modelLabel = 'Booking Sewa';

    protected static ?string $pluralModelLabel = 'Data Booking Sewa';

    protected static string|\UnitEnum|null $navigationGroup = 'Transaksi & Keuangan';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return BookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }
}
