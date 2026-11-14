<?php

namespace App\Filament\Superadmin\Resources\AdsPromotions;

use App\Filament\Superadmin\Resources\AdsPromotions\Pages\CreateAdsPromotion;
use App\Filament\Superadmin\Resources\AdsPromotions\Pages\EditAdsPromotion;
use App\Filament\Superadmin\Resources\AdsPromotions\Pages\ListAdsPromotions;
use App\Filament\Superadmin\Resources\AdsPromotions\Schemas\AdsPromotionForm;
use App\Filament\Superadmin\Resources\AdsPromotions\Tables\AdsPromotionsTable;
use App\Models\AdsPromotion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdsPromotionResource extends Resource
{
    protected static ?string $model = AdsPromotion::class;

    protected static ?string $navigationLabel = 'Manajemen Iklan Kos';

    protected static ?string $modelLabel = 'Iklan Kos';

    protected static ?string $pluralModelLabel = 'Manajemen Iklan Kos';

    protected static string|UnitEnum|null $navigationGroup = 'Monetisasi & Iklan';

    protected static ?int $navigationSort = 1;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMegaphone;

    public static function form(Schema $schema): Schema
    {
        return AdsPromotionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdsPromotionsTable::configure($table);
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
            'index' => ListAdsPromotions::route('/'),
            'create' => CreateAdsPromotion::route('/create'),
            'edit' => EditAdsPromotion::route('/{record}/edit'),
        ];
    }
}
