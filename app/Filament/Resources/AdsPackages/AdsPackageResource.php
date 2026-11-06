<?php

namespace App\Filament\Resources\AdsPackages;

use App\Filament\Resources\AdsPackages\Pages\CreateAdsPackage;
use App\Filament\Resources\AdsPackages\Pages\EditAdsPackage;
use App\Filament\Resources\AdsPackages\Pages\ListAdsPackages;
use App\Filament\Resources\AdsPackages\Schemas\AdsPackageForm;
use App\Filament\Resources\AdsPackages\Tables\AdsPackagesTable;
use App\Models\AdsPackage;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdsPackageResource extends Resource
{
    protected static ?string $model = AdsPackage::class;

    protected static ?string $navigationLabel = 'Tarif Paket Iklan';
    protected static ?string $modelLabel = 'Paket Iklan';
    protected static ?string $pluralModelLabel = 'Tarif Paket Iklan';
    protected static string|UnitEnum|null $navigationGroup = 'Monetisasi & Iklan';

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
