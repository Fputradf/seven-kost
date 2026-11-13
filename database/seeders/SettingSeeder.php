<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Identitas & Tampilan
            ['key' => 'app_name', 'value' => 'Seven Kost', 'group' => 'general', 'type' => 'string'],
            ['key' => 'app_tagline', 'value' => 'Platform Cari & Kelola Kos Modern', 'group' => 'general', 'type' => 'string'],
            ['key' => 'app_logo', 'value' => 'images/logo-seven-kost.png', 'group' => 'general', 'type' => 'image'],
            ['key' => 'app_logo_dark', 'value' => 'images/logo-seven-kost-dark.svg', 'group' => 'general', 'type' => 'image'],
            ['key' => 'app_favicon', 'value' => 'images/logo-seven-kost.png', 'group' => 'general', 'type' => 'image'],
            ['key' => 'meta_description', 'value' => 'Temukan kos impian dan kelola bisnis properti kos dengan mudah di Seven Kost.', 'group' => 'general', 'type' => 'text'],

            // Kontak & Sosial Media
            ['key' => 'contact_whatsapp', 'value' => '081100000001', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'contact_email', 'value' => 'support@sevenkos.com', 'group' => 'contact', 'type' => 'string'],
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/sevenkost', 'group' => 'social', 'type' => 'string'],
            ['key' => 'social_tiktok', 'value' => 'https://tiktok.com/@sevenkost', 'group' => 'social', 'type' => 'string'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/sevenkost', 'group' => 'social', 'type' => 'string'],
            ['key' => 'social_twitter', 'value' => 'https://twitter.com/sevenkost', 'group' => 'social', 'type' => 'string'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@sevenkost', 'group' => 'social', 'type' => 'string'],

            // Tarif & Biaya Platform
            ['key' => 'fee_booking_flat', 'value' => '5000', 'group' => 'pricing', 'type' => 'number'],
            ['key' => 'fee_platform_percentage', 'value' => '5', 'group' => 'pricing', 'type' => 'number'],
            ['key' => 'rate_banner_weekly', 'value' => '250000', 'group' => 'pricing', 'type' => 'number'],
            ['key' => 'rate_banner_monthly', 'value' => '850000', 'group' => 'pricing', 'type' => 'number'],
            ['key' => 'rate_featured_kost', 'value' => '50000', 'group' => 'pricing', 'type' => 'number'],
        ];

        foreach ($settings as $setting) {
            AppSetting::firstOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'group' => $setting['group'],
                    'type' => $setting['type'],
                ]
            );
        }
    }
}
