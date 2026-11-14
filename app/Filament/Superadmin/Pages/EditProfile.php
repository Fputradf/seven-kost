<?php

namespace App\Filament\Superadmin\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Facades\Auth;

class EditProfile extends BaseEditProfile
{
    protected static ?string $title = 'Pengaturan Akun & Keamanan';

    protected static ?string $slug = 'profile';

    protected string $view = 'filament.superadmin.pages.edit-profile';

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    protected function getFormActions(): array
    {
        return [];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->hasRole('superadmin');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->inlineLabel(false)
            ->components([
                Tabs::make('Pengaturan Akun')
                    ->tabs([
                        // TAB 1: IDENTITAS & KONTAK
                        Tab::make('Data Diri & Kontak')
                            ->schema([
                                Section::make('Informasi Akun Administrator')
                                    ->inlineLabel(false)
                                    ->schema([
                                        Grid::make(['default' => 1, 'lg' => 12])
                                            ->schema([
                                                FileUpload::make('avatar')
                                                    ->label('Foto Profil')
                                                    ->avatar()
                                                    ->image()
                                                    ->disk('public')
                                                    ->directory('avatars')
                                                    ->maxSize(2048)
                                                    ->alignCenter()
                                                    ->columnSpan(['default' => 12, 'lg' => 3]),

                                                Grid::make(['default' => 1, 'sm' => 2])
                                                    ->schema([
                                                        $this->getNameFormComponent()
                                                            ->label('Nama Lengkap')
                                                            ->placeholder('Nama Lengkap Administrator')
                                                            ->columnSpan(1),

                                                        $this->getEmailFormComponent()
                                                            ->label('Alamat Email Resmi')
                                                            ->placeholder('admin@sevenkost.com')
                                                            ->columnSpan(1),

                                                        TextInput::make('phone')
                                                            ->label('Nomor WhatsApp / Telepon')
                                                            ->tel()
                                                            ->maxLength(20)
                                                            ->placeholder('081234567890')
                                                            ->columnSpan(['default' => 1, 'sm' => 2]),
                                                    ])
                                                    ->columnSpan(['default' => 12, 'lg' => 9]),
                                            ]),
                                    ])
                                    ->footerActions([
                                        Action::make('saveProfile')
                                            ->label('Simpan Perubahan')
                                            ->color('primary')
                                            ->submit('save'),
                                    ])
                                    ->footerActionsAlignment(Alignment::End),
                            ]),

                        // TAB 2: KEAMANAN & KATA SANDI
                        Tab::make('Keamanan & Kata Sandi')
                            ->schema([
                                Section::make('Kredensial Kata Sandi')
                                    ->inlineLabel(false)
                                    ->schema([
                                        $this->getCurrentPasswordFormComponent()
                                            ->label('Kata Sandi Saat Ini')
                                            ->placeholder('Masukkan kata sandi lama Anda'),

                                        Grid::make(['default' => 1, 'sm' => 2])
                                            ->schema([
                                                $this->getPasswordFormComponent()
                                                    ->label('Kata Sandi Baru')
                                                    ->placeholder('Minimal 8 karakter'),

                                                $this->getPasswordConfirmationFormComponent()
                                                    ->label('Konfirmasi Kata Sandi Baru')
                                                    ->placeholder('Ulangi kata sandi baru'),
                                            ]),
                                    ])
                                    ->footerActions([
                                        Action::make('savePassword')
                                            ->label('Perbarui Kata Sandi')
                                            ->color('primary')
                                            ->submit('save'),
                                    ])
                                    ->footerActionsAlignment(Alignment::End),
                            ]),
                    ]),
            ]);
    }
}
