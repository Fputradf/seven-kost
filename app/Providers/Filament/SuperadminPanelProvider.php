<?php

namespace App\Providers\Filament;

use App\Filament\Superadmin\Pages\Dashboard;
use App\Filament\Superadmin\Pages\EditProfile;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class SuperadminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('superadmin')
            ->path('superadmin')
            ->spa()
            ->login()
            ->profile(page: EditProfile::class, isSimple: false)
            ->breadcrumbs(false)
            ->maxContentWidth(Width::Full)
            ->darkMode(false)
            ->font('Geist')
            ->colors([
                'primary' => Color::Blue,
                'gray' => Color::Slate,
            ])
            ->sidebarWidth('15.5rem')
            ->brandName(fn () => app_name().' Super Admin')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('2.5rem')
            ->favicon(fn () => app_favicon_url())
            ->userMenuItems([
                MenuItem::make()
                    ->label('Lihat Website')
                    ->url('/')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square'),
                MenuItem::make()
                    ->label('Pengaturan Platform')
                    ->url('/superadmin/pengaturan-aplikasi')
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->navigationItems([
                NavigationItem::make('Pengaturan Akun')
                    ->group('Pengaturan Sistem')
                    ->icon('heroicon-o-user-circle')
                    ->url(fn (): string => filament()->getProfileUrl())
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.superadmin.auth.profile'))
                    ->sort(20),
            ])
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn () => view('filament.custom-theme'),
            )
            ->renderHook(
                PanelsRenderHook::TOPBAR_START,
                fn () => view('filament.superadmin.components.topbar-start'),
            )
            ->renderHook(
                PanelsRenderHook::USER_MENU_BEFORE,
                fn () => view('filament.superadmin.components.user-profile-header'),
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_FOOTER,
                fn () => view('filament.superadmin.components.sidebar-footer'),
            )
            ->renderHook(
                'tables::toolbar.start',
                fn () => view('filament.superadmin.components.table-toolbar-title'),
            )
            ->navigationGroups([
                'Moderasi & Konten',
                'Monetisasi & Iklan',
                'Transaksi & Keuangan',
                'Manajemen Pengguna & Akses',
                'Pengaturan Sistem',
            ])
            ->discoverResources(in: app_path('Filament/Superadmin/Resources'), for: 'App\Filament\Superadmin\Resources')
            ->discoverPages(in: app_path('Filament/Superadmin/Pages'), for: 'App\Filament\Superadmin\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Superadmin/Widgets'), for: 'App\Filament\Superadmin\Widgets')
            ->widgets([])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        Table::configureUsing(function (Table $table): void {
            $table
                ->defaultPaginationPageOption(10)
                ->paginationPageOptions([10]);
        });
    }
}
