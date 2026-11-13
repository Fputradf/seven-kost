<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
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
            ->login()
            ->darkMode(false)
            ->font('Plus Jakarta Sans')
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
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
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
}
