<?php

namespace App\Filament\Superadmin\Resources\MarketplaceItems;

use App\Filament\Superadmin\Resources\MarketplaceItems\Pages\CreateMarketplaceItem;
use App\Filament\Superadmin\Resources\MarketplaceItems\Pages\EditMarketplaceItem;
use App\Filament\Superadmin\Resources\MarketplaceItems\Pages\ListMarketplaceItems;
use App\Filament\Superadmin\Resources\MarketplaceItems\Schemas\MarketplaceItemForm;
use App\Filament\Superadmin\Resources\MarketplaceItems\Tables\MarketplaceItemsTable;
use App\Models\MarketplaceItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MarketplaceItemResource extends Resource
{
    protected static ?string $model = MarketplaceItem::class;

    protected static ?string $navigationLabel = 'Moderasi Marketplace';

    protected static ?string $modelLabel = 'Barang Bekas';

    protected static ?string $pluralModelLabel = 'Moderasi Marketplace';

    protected static string|UnitEnum|null $navigationGroup = 'Moderasi & Konten';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    public static function form(Schema $schema): Schema
    {
        return MarketplaceItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarketplaceItemsTable::configure($table);
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
            'index' => ListMarketplaceItems::route('/'),
            'create' => CreateMarketplaceItem::route('/create'),
            'edit' => EditMarketplaceItem::route('/{record}/edit'),
        ];
    }
}
