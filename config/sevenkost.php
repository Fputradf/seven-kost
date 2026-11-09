<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Skema Komisi Platform SevenKost (Internal / Superadmin Config)
    |--------------------------------------------------------------------------
    | Default disetel 0. Superadmin nantinya dapat mengonfigurasi nilai ini.
    */
    'commission' => [
        'booking_percent' => (float) env('SEVENKOST_COMMISSION_BOOKING_PERCENT', 0),
        'cpc_fee' => (int) env('SEVENKOST_COMMISSION_CPC_FEE', 0),
        'is_free_promo_active' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Paket Berlangganan Akun VIP Pemilik Kos
    |--------------------------------------------------------------------------
    | Akses fitur prioritas, unlimited kos, lencana emas, dan fast-track verifikasi.
    */
    'vip_packages' => [
        '1_month' => [
            'id' => '1_month',
            'name' => 'VIP 1 Bulan',
            'duration_label' => '1 Bulan',
            'price' => 49000,
            'price_label' => 'Rp 49.000',
            'period' => '/ bulan',
            'description' => 'Akses penuh seluruh benefit VIP untuk 1 bulan.',
            'is_popular' => false,
        ],
        '3_months' => [
            'id' => '3_months',
            'name' => 'VIP 3 Bulan',
            'duration_label' => '3 Bulan',
            'price' => 129000,
            'price_label' => 'Rp 129.000',
            'period' => '/ 3 bulan',
            'description' => 'Pilihan paling hemat untuk juragan kos aktif.',
            'is_popular' => true,
        ],
        '1_year' => [
            'id' => '1_year',
            'name' => 'VIP 1 Tahun',
            'duration_label' => '1 Tahun',
            'price' => 399000,
            'price_label' => 'Rp 399.000',
            'period' => '/ tahun',
            'description' => 'Komitmen panjang terbaik untuk seluruh portofolio kos.',
            'is_popular' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Paket Iklan Banner Komersial (Sponsorship & Brand)
    |--------------------------------------------------------------------------
    | Durasi penayangan disamakan: 1 Bulan, 3 Bulan, dan 1 Tahun.
    */
    'banner_packages' => [
        30 => [
            'duration_days' => 30,
            'name' => 'Banner 1 Bulan',
            'duration_label' => '1 Bulan (30 Hari)',
            'price' => 250000,
            'price_label' => 'Rp 250.000',
            'period' => '/ bulan',
            'description' => 'Penayangan banner promosi brand Anda di platform SevenKost selama 1 bulan.',
            'is_popular' => false,
            'dimension' => 'Rasio 16:9 / 21:9 (Maks. 3MB)',
        ],
        90 => [
            'duration_days' => 90,
            'name' => 'Banner 3 Bulan',
            'duration_label' => '3 Bulan (90 Hari)',
            'price' => 650000,
            'price_label' => 'Rp 650.000',
            'period' => '/ 3 bulan',
            'description' => 'Paling populer untuk promosi berkala dan membangun brand awareness kuat.',
            'is_popular' => true,
            'dimension' => 'Rasio 16:9 / 21:9 (Maks. 3MB)',
        ],
        365 => [
            'duration_days' => 365,
            'name' => 'Banner 1 Tahun',
            'duration_label' => '1 Tahun (365 Hari)',
            'price' => 1990000,
            'price_label' => 'Rp 1.990.000',
            'period' => '/ tahun',
            'description' => 'Penayangan eksklusif selama 1 tahun penuh dengan eksposur tertinggi kepada pencari kos.',
            'is_popular' => false,
            'dimension' => 'Rasio 16:9 / 21:9 (Maks. 3MB)',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Konfigurasi Integrasi Pembayaran Xendit & Alur Approval
    |--------------------------------------------------------------------------
    */
    'payment_gateway' => [
        'provider' => 'Xendit Gateway (QRIS, VA, E-Wallet)',
        'auto_payout' => true,
        'require_admin_approval' => true,
    ],
];
