<?php

namespace App\Filament\Superadmin\Resources\Reports;

use App\Filament\Superadmin\Resources\Reports\Pages\CreateReport;
use App\Filament\Superadmin\Resources\Reports\Pages\EditReport;
use App\Filament\Superadmin\Resources\Reports\Pages\ListReports;
use App\Filament\Superadmin\Resources\Reports\Schemas\ReportForm;
use App\Filament\Superadmin\Resources\Reports\Tables\ReportsTable;
use App\Models\Report;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReportResource extends Resource
{
    protected static ?string $model = Report::class;

    protected static ?string $navigationLabel = 'Laporan Pelanggaran';

    protected static ?string $modelLabel = 'Laporan';

    protected static ?string $pluralModelLabel = 'Laporan Pelanggaran';

    protected static string|\UnitEnum|null $navigationGroup = 'Moderasi & Konten';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    public static function form(Schema $schema): Schema
    {
        return ReportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReportsTable::configure($table);
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
            'index' => ListReports::route('/'),
            'create' => CreateReport::route('/create'),
            'edit' => EditReport::route('/{record}/edit'),
        ];
    }
}
