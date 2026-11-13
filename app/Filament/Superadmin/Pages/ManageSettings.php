<?php

namespace App\Filament\Superadmin\Pages;

use App\Models\AppSetting;
use App\Models\User;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Throwable;
use UnitEnum;

class ManageSettings extends Page
{
    protected static ?string $navigationLabel = 'Pengaturan Aplikasi';

    protected static ?string $title = 'Pengaturan Aplikasi & Tampilan';

    protected static ?string $slug = 'pengaturan-aplikasi';

    protected string $view = 'filament.superadmin.pages.manage-settings';

    protected static string|UnitEnum|null $navigationGroup = 'Pengaturan Sistem';

    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    /**
     * @var array<string, mixed>
     */
    public ?array $data = [];

    public function getHeading(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return null;
    }

    protected function getHeaderActions(): array
    {
        return [];
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();

        return $user && $user->hasRole('superadmin');
    }

    public function mount(): void
    {
        $settings = [];
        foreach (AppSetting::all() as $setting) {
            $settings[$setting->key] = match ($setting->type) {
                'number' => is_numeric($setting->value) ? (str_contains((string) $setting->value, '.') ? (float) $setting->value : (int) $setting->value) : null,
                'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
                'json' => json_decode((string) $setting->value, true) ?: [],
                default => $setting->value,
            };
        }

        // Jika belum ada social_links di database, inisialisasi dengan 5 sosmed default
        if (empty($settings['social_links']) || ! is_array($settings['social_links'])) {
            $settings['social_links'] = [
                ['platform' => 'instagram', 'url' => $settings['social_instagram'] ?? 'https://instagram.com/sevenkost', 'is_active' => true],
                ['platform' => 'tiktok', 'url' => $settings['social_tiktok'] ?? 'https://tiktok.com/@sevenkost', 'is_active' => true],
                ['platform' => 'youtube', 'url' => $settings['social_youtube'] ?? 'https://youtube.com/@sevenkost', 'is_active' => true],
                ['platform' => 'facebook', 'url' => $settings['social_facebook'] ?? 'https://facebook.com/sevenkost', 'is_active' => true],
                ['platform' => 'whatsapp', 'url' => 'https://wa.me/6281100000001', 'is_active' => true],
            ];
        }

        // Inisialisasi default tarif biaya layanan, paket VIP & banner jika belum ada di database
        $settings['fee_booking_tenant'] ??= 5000;
        $settings['ad_cpc_fee'] ??= 500;
        $settings['vip_price_1_month'] ??= 49000;
        $settings['vip_price_3_months'] ??= 129000;
        $settings['vip_price_1_year'] ??= 399000;
        $settings['banner_price_30'] ??= 250000;
        $settings['banner_price_90'] ??= 650000;
        $settings['banner_price_365'] ??= 1990000;

        $this->content->fill($settings);
    }

    /**
     * @return array<string, string>
     */
    public static function getSocialPlatformNames(): array
    {
        return [
            'instagram' => 'Instagram',
            'tiktok' => 'TikTok',
            'youtube' => 'YouTube',
            'facebook' => 'Facebook',
            'twitter' => 'X (Twitter)',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'linkedin' => 'LinkedIn',
            'threads' => 'Threads',
            'discord' => 'Discord',
            'github' => 'GitHub',
            'pinterest' => 'Pinterest',
            'website' => 'Website Resmi / Blog',
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function getSocialPlatformOptions(): array
    {
        $platforms = [
            'instagram' => 'Instagram',
            'tiktok' => 'TikTok',
            'youtube' => 'YouTube',
            'facebook' => 'Facebook',
            'twitter' => 'X (Twitter)',
            'whatsapp' => 'WhatsApp',
            'telegram' => 'Telegram',
            'linkedin' => 'LinkedIn',
            'threads' => 'Threads',
            'discord' => 'Discord',
            'github' => 'GitHub',
            'pinterest' => 'Pinterest',
            'website' => 'Website Resmi',
        ];

        $options = [];
        foreach ($platforms as $key => $label) {
            $meta = app_social_meta($key);
            $options[$key] = '<div style="display:inline-flex; align-items:center; gap:0.625rem; color:#0f172a;">'
                .'<span style="display:inline-flex; align-items:center; justify-content:center; width:1.375rem; height:1.375rem; flex-shrink:0; color:#0f172a;">'
                .$meta['svg']
                .'</span>'
                .'<span style="font-weight:600; font-size:0.875rem; color:#0f172a;">'.$label.'</span>'
                .'</div>';
        }

        return $options;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Form::make([
                    Tabs::make('Pengaturan')
                        ->tabs([
                            // TAB 1: IDENTITAS VISUAL & LOGO
                            Tab::make('Identitas & Logo')
                                ->icon('heroicon-o-paint-brush')
                                ->schema([
                                    Section::make('Identitas Brand')
                                        ->icon('heroicon-o-sparkles')
                                        ->schema([
                                            Grid::make(['default' => 1, 'md' => 2])
                                                ->schema([
                                                    TextInput::make('app_name')
                                                        ->label('Nama Aplikasi')
                                                        ->required()
                                                        ->placeholder('Seven Kost'),

                                                    TextInput::make('app_slogan')
                                                        ->label('Slogan')
                                                        ->placeholder('Platform Cari & Kelola Kos Modern'),
                                                ]),

                                            Textarea::make('app_description')
                                                ->label('Deskripsi Singkat')
                                                ->rows(3)
                                                ->placeholder('Temukan kos impian dan kelola bisnis properti kos dengan mudah di Seven Kost...'),
                                        ]),

                                    Section::make('Logo & Favicon')
                                        ->icon('heroicon-o-photo')
                                        ->schema([
                                            Grid::make(['default' => 1, 'sm' => 3])
                                                ->schema([
                                                    FileUpload::make('app_logo')
                                                        ->label('Logo Terang')
                                                        ->image()
                                                        ->directory('settings')
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->maxSize(2048)
                                                        ->imagePreviewHeight('100')
                                                        ->extraAttributes(['class' => 'logo-light-uploader']),

                                                    FileUpload::make('app_logo_dark')
                                                        ->label('Logo Gelap')
                                                        ->image()
                                                        ->directory('settings')
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->maxSize(2048)
                                                        ->imagePreviewHeight('100')
                                                        ->extraAttributes(['class' => 'logo-dark-uploader']),

                                                    FileUpload::make('app_favicon')
                                                        ->label('Favicon')
                                                        ->image()
                                                        ->directory('settings')
                                                        ->disk('public')
                                                        ->visibility('public')
                                                        ->maxSize(1024)
                                                        ->imagePreviewHeight('100')
                                                        ->extraAttributes(['class' => 'favicon-uploader']),
                                                ]),
                                        ]),
                                ]),

                            // TAB 2: KONTAK & MEDIA SOSIAL
                            Tab::make('Kontak & Media Sosial')
                                ->icon('heroicon-o-chat-bubble-left-right')
                                ->schema([
                                    Section::make('Kontak Layanan')
                                        ->icon('heroicon-o-phone')
                                        ->schema([
                                            Grid::make(['default' => 1, 'md' => 2])
                                                ->schema([
                                                    TextInput::make('contact_email')
                                                        ->label('Email')
                                                        ->email()
                                                        ->placeholder('support@sevenkos.com')
                                                        ->prefixIcon('heroicon-o-envelope'),

                                                    TextInput::make('contact_phone')
                                                        ->label('WhatsApp / Telepon')
                                                        ->tel()
                                                        ->placeholder('081234567890')
                                                        ->prefixIcon('heroicon-o-phone'),
                                                ]),

                                            TextInput::make('contact_address')
                                                ->label('Alamat Kantor')
                                                ->placeholder('Jl. Contoh No. 123, Sleman, Yogyakarta')
                                                ->prefixIcon('heroicon-o-map-pin'),
                                        ]),

                                    Section::make('Media Sosial (Maks. 5)')
                                        ->icon('heroicon-o-share')
                                        ->schema([
                                            Repeater::make('social_links')
                                                ->label('Daftar Akun')
                                                ->maxItems(5)
                                                ->collapsible()
                                                ->collapsed(false)
                                                ->cloneable()
                                                ->reorderable()
                                                ->reorderableWithButtons()
                                                ->addActionLabel('+ Tambah Akun')
                                                ->itemLabel(fn (array $state): ?string => isset($state['platform'])
                                                        ? (self::getSocialPlatformNames()[$state['platform']] ?? ucfirst($state['platform']))
                                                        : null
                                                )
                                                ->schema([
                                                    Grid::make(['default' => 1, 'md' => 12])
                                                        ->schema([
                                                            Select::make('platform')
                                                                ->label('Platform')
                                                                ->options(self::getSocialPlatformOptions())
                                                                ->allowHtml()
                                                                ->required()
                                                                ->native(false)
                                                                ->searchable()
                                                                ->columnSpan(['default' => 12, 'md' => 4]),

                                                            TextInput::make('url')
                                                                ->label('URL Tautan')
                                                                ->placeholder('https://...')
                                                                ->url()
                                                                ->required()
                                                                ->columnSpan(['default' => 12, 'md' => 6]),

                                                            Toggle::make('is_active')
                                                                ->label('Aktif')
                                                                ->default(true)
                                                                ->inline(false)
                                                                ->columnSpan(['default' => 12, 'md' => 2]),
                                                        ]),
                                                ]),
                                        ]),
                                ]),

                            // TAB 3: TARIF LAYANAN, VIP & IKLAN BANNER
                            Tab::make('Tarif & Iklan')
                                ->icon('heroicon-o-currency-dollar')
                                ->schema([
                                    Section::make('Biaya Transaksi & CPC')
                                        ->icon('heroicon-o-banknotes')
                                        ->schema([
                                            Grid::make(['default' => 1, 'md' => 2])
                                                ->schema([
                                                    TextInput::make('fee_booking_tenant')
                                                        ->label('Biaya Tambahan Transaksi Penghuni')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('5000'),

                                                    TextInput::make('ad_cpc_fee')
                                                        ->label('Biaya per Klik Iklan Kos (CPC)')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('500'),
                                                ]),
                                        ]),

                                    Section::make('Tarif Paket VIP Berlangganan Pemilik')
                                        ->icon('heroicon-o-star')
                                        ->schema([
                                            Grid::make(['default' => 1, 'md' => 3])
                                                ->schema([
                                                    TextInput::make('vip_price_1_month')
                                                        ->label('VIP 1 Bulan')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('49000'),

                                                    TextInput::make('vip_price_3_months')
                                                        ->label('VIP 3 Bulan')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('129000'),

                                                    TextInput::make('vip_price_1_year')
                                                        ->label('VIP 1 Tahun')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('399000'),
                                                ]),
                                        ]),

                                    Section::make('Tarif Paket Iklan Banner Beranda')
                                        ->icon('heroicon-o-megaphone')
                                        ->schema([
                                            Grid::make(['default' => 1, 'md' => 3])
                                                ->schema([
                                                    TextInput::make('banner_price_30')
                                                        ->label('Banner 1 Bulan (30 Hari)')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('250000'),

                                                    TextInput::make('banner_price_90')
                                                        ->label('Banner 3 Bulan (90 Hari)')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('650000'),

                                                    TextInput::make('banner_price_365')
                                                        ->label('Banner 1 Tahun (365 Hari)')
                                                        ->numeric()
                                                        ->prefix('Rp')
                                                        ->placeholder('1990000'),
                                                ]),
                                        ]),
                                ]),
                        ]),
                ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->content->getState();

        foreach ($data as $key => $value) {
            $type = 'string';
            $group = 'general';

            if ($key === 'social_links') {
                $type = 'json';
                $group = 'social';
                $socialArray = is_array($value) ? array_values(array_slice($value, 0, 5)) : [];
                $serializedValue = json_encode($socialArray);

                // Sinkronkan juga key legacy untuk backward compatibility
                foreach ($socialArray as $item) {
                    if (($item['is_active'] ?? true) && ! empty($item['platform']) && ! empty($item['url'])) {
                        AppSetting::updateOrCreate(
                            ['key' => 'social_'.$item['platform']],
                            ['value' => $item['url'], 'group' => 'social', 'type' => 'string']
                        );
                    }
                }
            } elseif (is_array($value)) {
                $serializedValue = count($value) > 0 ? (string) reset($value) : null;
            } else {
                $serializedValue = $value;
            }

            // Jalankan auto-compress otomatis untuk berkas gambar yang diunggah
            if (in_array($key, ['app_logo', 'app_logo_dark', 'app_favicon'], true) && is_string($serializedValue) && $serializedValue !== '') {
                $this->autoCompressImage($serializedValue);
            }

            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $serializedValue, 'group' => $group, 'type' => $type]
            );

            // Sinkronkan logo gelap dengan logo utama jika app_logo_dark belum diisi
            if ($key === 'app_logo' && empty($data['app_logo_dark'])) {
                AppSetting::firstOrCreate(
                    ['key' => 'app_logo_dark'],
                    ['value' => $serializedValue, 'group' => 'general', 'type' => 'string']
                );
            }
        }

        Cache::forget('all_app_settings');
        Cache::forget('app_setting_social_links');
        Cache::forget('app_vip_packages');
        Cache::forget('app_banner_packages');

        Notification::make()
            ->title('Pengaturan Berhasil Disimpan')
            ->body('Seluruh konfigurasi identitas, logo, kontak, 5 media sosial resmi, dan tarif telah berhasil diperbarui.')
            ->success()
            ->send();
    }

    /**
     * Kompresi dan optimasi otomatis berkas gambar pada disk publik
     * untuk menjaga efisiensi penyimpanan dan deploy super ringan.
     */
    protected function autoCompressImage(string $path): void
    {
        try {
            $disk = Storage::disk('public');

            if (! $disk->exists($path)) {
                return;
            }

            $fullPath = $disk->path($path);

            if (! file_exists($fullPath) || ! is_file($fullPath)) {
                return;
            }

            $imageInfo = @getimagesize($fullPath);
            if (! $imageInfo) {
                return;
            }

            $mime = $imageInfo['mime'] ?? '';
            $origWidth = $imageInfo[0];
            $origHeight = $imageInfo[1];

            // Abaikan SVG karena format vektor sudah sangat ringan dan lossless
            if ($mime === 'image/svg+xml') {
                return;
            }

            // Batas maksimal dimensi 800px (sangat cukup untuk logo & favicon)
            $maxDimension = 800;
            $newWidth = $origWidth;
            $newHeight = $origHeight;

            if ($origWidth > $maxDimension || $origHeight > $maxDimension) {
                if ($origWidth >= $origHeight) {
                    $newWidth = $maxDimension;
                    $newHeight = (int) max(1, round(($origHeight / $origWidth) * $maxDimension));
                } else {
                    $newHeight = $maxDimension;
                    $newWidth = (int) max(1, round(($origWidth / $origHeight) * $maxDimension));
                }
            }

            $source = match ($mime) {
                'image/jpeg', 'image/jpg' => @imagecreatefromjpeg($fullPath),
                'image/png' => @imagecreatefrompng($fullPath),
                'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($fullPath) : null,
                default => null,
            };

            if (! $source) {
                return;
            }

            $target = imagecreatetruecolor($newWidth, $newHeight);

            // Pertahankan transparansi alpha channel untuk PNG & WebP
            if ($mime === 'image/png' || $mime === 'image/webp') {
                imagealphablending($target, false);
                imagesavealpha($target, true);
                $transparent = imagecolorallocatealpha($target, 0, 0, 0, 127);
                imagefilledrectangle($target, 0, 0, $newWidth, $newHeight, $transparent);
            }

            imagecopyresampled(
                $target,
                $source,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $origWidth,
                $origHeight
            );

            // Simpan gambar dengan kompresi teroptimasi
            match ($mime) {
                'image/jpeg', 'image/jpg' => imagejpeg($target, $fullPath, 85),
                'image/png' => imagepng($target, $fullPath, 8),
                'image/webp' => function_exists('imagewebp') ? imagewebp($target, $fullPath, 85) : null,
                default => null,
            };

            imagedestroy($source);
            imagedestroy($target);
        } catch (Throwable $e) {
            // Log silently agar tidak memblokir penyimpanan jika ada format khusus
            report($e);
        }
    }
}
