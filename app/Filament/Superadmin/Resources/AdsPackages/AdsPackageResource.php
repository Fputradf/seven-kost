<?php

namespace App\Filament\Superadmin\Resources\AdsPackages;

use App\Filament\Superadmin\Resources\AdsPackages\Pages\CreateAdsPackage;
use App\Filament\Superadmin\Resources\AdsPackages\Pages\EditAdsPackage;
use App\Filament\Superadmin\Resources\AdsPackages\Pages\ListAdsPackages;
use App\Filament\Superadmin\Resources\AdsPackages\Schemas\AdsPackageForm;
use App\Filament\Superadmin\Resources\AdsPackages\Tables\AdsPackagesTable;
use App\Models\AdsPackage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class AdsPackageResource extends Resource
{
    protected static ?string $model = AdsPackage::class;

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static ?string $navigationLabel = 'Tarif Paket Iklan';

    protected static ?string $modelLabel = 'Paket Iklan';

    protected static ?string $pluralModelLabel = 'Tarif Paket Iklan';

    protected static string|UnitEnum|null $navigationGroup = 'Monetisasi & Iklan';

    protected static ?int $navigationSort = 3;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    public static function form(Schema $schema): Schema
    {
        return AdsPackageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdsPackagesTable::configure($table);
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
            'index' => ListAdsPackages::route('/'),
            'create' => CreateAdsPackage::route('/create'),
            'edit' => EditAdsPackage::route('/{record}/edit'),
        ];
    }
}
