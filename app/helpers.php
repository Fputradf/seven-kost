<?php

use App\Models\AppSetting;

if (! function_exists('setting')) {
    /**
     * Dapatkan nilai pengaturan aplikasi dengan fallback default.
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return AppSetting::get($key, $default);
    }
}

if (! function_exists('app_logo_url')) {
    /**
     * Dapatkan URL berkas logo aplikasi (unified master logo).
     *
     * @param  string|null  $type  'light' (latar belakang terang/putih) atau 'dark' (latar belakang gelap/navy)
     */
    function app_logo_url(?string $type = null): string
    {
        $settingLogo = null;

        if ($type === 'dark') {
            $settingLogo = setting('app_logo_dark');

            // Jika admin belum mengunggah custom dark logo, gunakan default SVG varian latar gelap
            if (! $settingLogo && file_exists(public_path('images/logo-seven-kost-dark.svg'))) {
                return asset('images/logo-seven-kost-dark.svg');
            }

            $settingLogo = $settingLogo ?: setting('app_logo');
        } else {
            $settingLogo = setting('app_logo') ?: setting('app_logo_dark');
        }

        if (! $settingLogo) {
            return asset('images/logo-seven-kost.png');
        }

        if (str_starts_with($settingLogo, 'http://') || str_starts_with($settingLogo, 'https://')) {
            return $settingLogo;
        }

        if (file_exists(public_path($settingLogo))) {
            return asset($settingLogo);
        }

        return asset('storage/'.ltrim($settingLogo, '/'));
    }
}

if (! function_exists('app_favicon_url')) {
    /**
     * Dapatkan URL favicon aplikasi dengan fallback ke logo.
     */
    function app_favicon_url(): string
    {
        $fav = setting('app_favicon') ?: setting('app_logo');

        if (! $fav) {
            return asset('favicon.png');
        }

        if (str_starts_with($fav, 'http://') || str_starts_with($fav, 'https://')) {
            return $fav;
        }

        if (file_exists(public_path($fav))) {
            return asset($fav);
        }

        return asset('storage/'.ltrim($fav, '/'));
    }
}

if (! function_exists('app_name')) {
    /**
     * Dapatkan nama resmi aplikasi platform.
     */
    function app_name(string $default = 'SevenKost'): string
    {
        return (string) setting('app_name', $default);
    }
}

if (! function_exists('app_social_meta')) {
    /**
     * Dapatkan metadata icon SVG, nama, dan hover warna untuk tiap platform media sosial.
     *
     * @return array{name: string, color_class: string, svg: string}
     */
    function app_social_meta(string $platform): array
    {
        return match (strtolower(trim($platform))) {
            'instagram' => [
                'name' => 'Instagram',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
            ],
            'tiktok' => [
                'name' => 'TikTok',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.24 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
            ],
            'youtube' => [
                'name' => 'YouTube',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
            ],
            'facebook' => [
                'name' => 'Facebook',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
            ],
            'twitter', 'x' => [
                'name' => 'X (Twitter)',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 24.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
            ],
            'whatsapp' => [
                'name' => 'WhatsApp',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 0C5.394 0 0 5.394 0 12.031c0 2.118.553 4.184 1.606 6.008L.068 24l6.143-1.611a11.96 11.96 0 005.82 1.495h.005c6.632 0 12.026-5.394 12.026-12.031 0-3.213-1.252-6.233-3.525-8.506A11.95 11.95 0 0012.031 0zm0 22.046h-.004c-1.8 0-3.565-.484-5.105-1.398l-.366-.217-3.795.996 1.013-3.699-.239-.38a9.99 9.99 0 01-1.533-5.317c0-5.526 4.498-10.024 10.029-10.024 2.678 0 5.195 1.044 7.088 2.937a10.007 10.007 0 012.934 7.087c0 5.526-4.498 10.024-10.027 10.024zm5.498-7.514c-.301-.151-1.782-.879-2.058-.979-.276-.1-.477-.151-.678.151-.201.301-.779.979-.955 1.18-.176.201-.351.226-.652.075-.301-.151-1.272-.469-2.423-1.495-.895-.799-1.5-1.786-1.676-2.087-.176-.301-.019-.464.132-.614.136-.135.301-.351.452-.527.151-.176.201-.301.301-.502.101-.201.05-.376-.025-.527-.075-.151-.678-1.631-.929-2.234-.245-.587-.494-.507-.678-.516l-.578-.01c-.201 0-.527.075-.803.376s-1.054 1.029-1.054 2.51c0 1.48 1.079 2.912 1.23 3.113.151.201 2.124 3.243 5.147 4.545.719.311 1.28.497 1.717.636.722.23 1.379.197 1.899.12.579-.086 1.782-.728 2.033-1.431.251-.703.251-1.305.176-.151-.075-.151-.276-.226-.577-.376z"/></svg>',
            ],
            'telegram' => [
                'name' => 'Telegram',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.894 8.221l-1.97 9.28c-.145.658-.537.818-1.084.508l-3-2.21-1.446 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953l11.57-4.461c.537-.194 1.006.131.832.932z"/></svg>',
            ],
            'linkedin' => [
                'name' => 'LinkedIn',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>',
            ],
            'threads' => [
                'name' => 'Threads',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12.186 24h-.007c-3.581-.026-6.348-1.285-8.223-3.742-1.921-2.518-2.671-5.908-2.23-10.076.467-4.407 2.753-8.084 6.438-10.354C10.233.568 12.632 0 15.309 0c3.279 0 6.136.924 8.261 2.671 2.054 1.688 3.257 4.095 3.388 6.776.134 2.765-.826 5.253-2.705 7.006-1.767 1.649-4.148 2.502-6.703 2.402-1.849-.072-3.414-.686-4.526-1.775-.246.337-.534.646-.86.922-1.42 1.205-3.218 1.83-5.201 1.808-2.079-.022-3.878-.711-5.202-1.992-1.275-1.233-1.936-2.909-1.862-4.717.075-1.855.882-3.473 2.274-4.555 1.34-1.042 3.097-1.579 5.081-1.552 1.157.016 2.236.216 3.208.594.004-.848.06-1.637.168-2.355.263-1.748.877-3.167 1.826-4.218.991-1.096 2.348-1.656 4.032-1.666 1.763-.01 3.265.597 4.464 1.806 1.168 1.177 1.758 2.754 1.706 4.558-.046 1.574-.523 2.92-1.417 4.001-.849 1.026-2.012 1.696-3.456 1.993-1.517.311-3.149.332-4.851.062-.053.47-.091.956-.112 1.455-.062 1.472.046 2.767.322 3.847.458 1.794 1.597 2.755 3.298 2.776 1.687.021 3.223-.746 4.441-2.218l2.067 1.583c-1.585 1.917-3.649 2.96-6.134 3.104-.338.019-.68.029-1.025.029zm-2.042-8.895c-.776-.328-1.62-.505-2.508-.525-1.32-.03-2.428.322-3.201 1.018-.767.692-1.182 1.644-1.201 2.753-.019 1.121.365 2.115 1.111 2.873.743.755 1.762 1.154 3.027 1.187 1.272.032 2.383-.34 3.212-1.077.587-.522.996-1.181 1.218-1.96.223-.78.336-1.697.336-2.73 0-.528-.016-1.042-.047-1.539h-.947z"/></svg>',
            ],
            'discord' => [
                'name' => 'Discord',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994.021-.041.001-.09-.041-.106a13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.929 1.793 8.18 1.793 12.061 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.894.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.028zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/></svg>',
            ],
            'github' => [
                'name' => 'GitHub',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.31 3.435 9.795 8.205 11.385.6.105.825-.255.825-.57 0-.285-.015-1.23-.015-2.235-3.015.555-3.795-.735-4.035-1.41-.135-.345-.72-1.41-1.23-1.695-.42-.225-1.02-.78-.015-.795.945-.015 1.62.87 1.845 1.23 1.08 1.815 2.805 1.305 3.495.99.105-.78.42-1.305.765-1.605-2.67-.3-5.46-1.335-5.46-5.925 0-1.305.465-2.385 1.23-3.225-.12-.3-.54-1.53.12-3.18 0 0 1.005-.315 3.3 1.23.96-.27 1.98-.405 3-.405s2.04.135 3 .405c2.295-1.56 3.3-1.23 3.3-1.23.66 1.65.24 2.88.12 3.18.765.84 1.23 1.905 1.23 3.225 0 4.605-2.805 5.625-5.475 5.925.435.375.81 1.095.81 2.22 0 1.605-.015 2.895-.015 3.3 0 .315.225.69.825.57A12.02 12.02 0 0 0 24 12c0-6.63-5.37-12-12-12z"/></svg>',
            ],
            'pinterest' => [
                'name' => 'Pinterest',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0a12 12 0 0 0-4.37 23.18c-.06-.94-.12-2.38.02-3.41.13-.93.89-6.32.89-6.32s-.23-.46-.23-1.14c0-1.07.62-1.87 1.4-1.87.66 0 .98.49.98 1.09 0 .66-.42 1.66-.64 2.58-.18.77.39 1.4 1.15 1.4 1.38 0 2.44-1.46 2.44-3.56 0-1.86-1.34-3.16-3.25-3.16-2.21 0-3.51 1.66-3.51 3.37 0 .67.26 1.38.58 1.77.06.08.07.15.05.23-.07.28-.22.91-.25 1.04-.04.17-.14.21-.32.13-1.19-.55-1.93-2.29-1.93-3.69 0-3 2.18-5.76 6.29-5.76 3.3 0 5.87 2.35 5.87 5.5 0 3.28-2.07 5.92-4.94 5.92-.96 0-1.87-.5-2.18-1.09l-.59 2.27c-.22.83-.8 1.87-1.19 2.5A12 12 0 1 0 12 0z"/></svg>',
            ],
            default => [
                'name' => 'Website',
                'color_class' => 'hover:bg-slate-900 hover:text-white',
                'svg' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-.778.099-1.533.284-2.253"/></svg>',
            ],
        };
    }
}

if (! function_exists('app_social_links')) {
    /**
     * Dapatkan daftar tautan media sosial aktif (maksimal 5).
     *
     * @return array<int, array{platform: string, name: string, url: string, color_class: string, svg: string}>
     */
    function app_social_links(): array
    {
        $links = setting('social_links');

        // Fallback default jika belum dikonfigurasi dalam format repeater
        if (empty($links) || ! is_array($links)) {
            $links = [
                ['platform' => 'instagram', 'url' => setting('social_instagram', 'https://instagram.com/sevenkost'), 'is_active' => true],
                ['platform' => 'tiktok', 'url' => setting('social_tiktok', 'https://tiktok.com/@sevenkost'), 'is_active' => true],
                ['platform' => 'youtube', 'url' => setting('social_youtube', 'https://youtube.com/@sevenkost'), 'is_active' => true],
                ['platform' => 'facebook', 'url' => setting('social_facebook', 'https://facebook.com/sevenkost'), 'is_active' => true],
                ['platform' => 'whatsapp', 'url' => setting('social_whatsapp', 'https://wa.me/6281100000001'), 'is_active' => true],
            ];
        }

        $active = [];
        foreach ($links as $item) {
            if (! is_array($item)) {
                continue;
            }

            $isActive = filter_var($item['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);
            $platform = strtolower(trim((string) ($item['platform'] ?? '')));
            $url = trim((string) ($item['url'] ?? ''));

            if (! $isActive || empty($platform) || empty($url)) {
                continue;
            }

            $meta = app_social_meta($platform);
            $active[] = [
                'platform' => $platform,
                'name' => $item['label'] ?? $meta['name'],
                'url' => $url,
                'color_class' => $meta['color_class'],
                'svg' => $meta['svg'],
            ];

            if (count($active) >= 5) {
                break;
            }
        }

        return $active;
    }
}

if (! function_exists('app_vip_packages')) {
    /**
     * Dapatkan daftar paket VIP pemilik kos (dinamis dari pengaturan).
     *
     * @return array<string, array<string, mixed>>
     */
    function app_vip_packages(): array
    {
        $defaults = config('sevenkost.vip_packages', []);

        $priceMap = [
            '1_month' => (int) setting('vip_price_1_month', $defaults['1_month']['price'] ?? 49000),
            '3_months' => (int) setting('vip_price_3_months', $defaults['3_months']['price'] ?? 129000),
            '1_year' => (int) setting('vip_price_1_year', $defaults['1_year']['price'] ?? 399000),
        ];

        foreach ($priceMap as $key => $price) {
            if (isset($defaults[$key])) {
                $defaults[$key]['price'] = $price;
                $defaults[$key]['price_label'] = 'Rp '.number_format($price, 0, ',', '.');
            }
        }

        return $defaults;
    }
}

if (! function_exists('app_banner_packages')) {
    /**
     * Dapatkan daftar paket banner komersial beranda (dinamis dari pengaturan).
     *
     * @return array<int, array<string, mixed>>
     */
    function app_banner_packages(): array
    {
        $defaults = config('sevenkost.banner_packages', []);

        $priceMap = [
            30 => (int) setting('banner_price_30', $defaults[30]['price'] ?? 250000),
            90 => (int) setting('banner_price_90', $defaults[90]['price'] ?? 650000),
            365 => (int) setting('banner_price_365', $defaults[365]['price'] ?? 1990000),
        ];

        foreach ($priceMap as $days => $price) {
            if (isset($defaults[$days])) {
                $defaults[$days]['price'] = $price;
                $defaults[$days]['price_label'] = 'Rp '.number_format($price, 0, ',', '.');
            }
        }

        return $defaults;
    }
}
