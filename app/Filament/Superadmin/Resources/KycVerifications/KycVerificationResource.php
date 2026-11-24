<?php

namespace App\Filament\Superadmin\Resources\KycVerifications;

use App\Filament\Superadmin\Resources\KycVerifications\Pages\ListKycVerifications;
use App\Filament\Superadmin\Resources\KycVerifications\Tables\KycVerificationsTable;
use App\Models\KycVerification;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class KycVerificationResource extends Resource
{
    protected static ?string $model = KycVerification::class;

    protected static ?string $navigationLabel = 'Verifikasi KYC';

    protected static ?string $modelLabel = 'Verifikasi KYC';

    protected static ?string $pluralModelLabel = 'Verifikasi KYC (KTP & Selfie)';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Pengguna & Akses';

    protected static ?int $navigationSort = 2;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->hasAnyRole(['superadmin', 'admin']);
    }

    public static function table(Table $table): Table
    {
        return KycVerificationsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListKycVerifications::route('/'),
        ];
    }
}
