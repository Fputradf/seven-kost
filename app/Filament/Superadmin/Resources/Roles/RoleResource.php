<?php

namespace App\Filament\Superadmin\Resources\Roles;

use App\Filament\Superadmin\Resources\Roles\Pages\CreateRole;
use App\Filament\Superadmin\Resources\Roles\Pages\EditRole;
use App\Filament\Superadmin\Resources\Roles\Pages\ListRoles;
use App\Filament\Superadmin\Resources\Roles\Schemas\RoleForm;
use App\Filament\Superadmin\Resources\Roles\Tables\RolesTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use UnitEnum;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationLabel = 'Peran & Hak Akses';

    protected static ?string $modelLabel = 'Peran (Role)';

    protected static ?string $pluralModelLabel = 'Daftar Peran';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Pengguna & Akses';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldCheck;

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->hasRole('superadmin');
    }

    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
}
