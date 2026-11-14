<?php

namespace App\Filament\Superadmin\Resources\Kosts;

use App\Filament\Superadmin\Resources\Kosts\Pages\CreateKost;
use App\Filament\Superadmin\Resources\Kosts\Pages\EditKost;
use App\Filament\Superadmin\Resources\Kosts\Pages\ListKosts;
use App\Filament\Superadmin\Resources\Kosts\Schemas\KostForm;
use App\Filament\Superadmin\Resources\Kosts\Tables\KostsTable;
use App\Models\Kost;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KostResource extends Resource
{
    protected static ?string $model = Kost::class;

    protected static ?string $navigationLabel = 'Manajemen Kos';

    protected static ?string $modelLabel = 'Listing Kos';

    protected static ?string $pluralModelLabel = 'Manajemen Kos';

    protected static string|\UnitEnum|null $navigationGroup = 'Moderasi & Konten';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    public static function form(Schema $schema): Schema
    {
        return KostForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KostsTable::configure($table);
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
            'index' => ListKosts::route('/'),
            'create' => CreateKost::route('/create'),
            'edit' => EditKost::route('/{record}/edit'),
        ];
    }
}
