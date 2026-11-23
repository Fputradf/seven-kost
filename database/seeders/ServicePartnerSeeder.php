<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use App\Models\ServicePartner;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class ServicePartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'mitra', 'guard_name' => 'web']);

        $partnersData = [
            [
                'email' => 'mitra.laundry@sevenkos.com',
                'name' => 'Budi Santoso (Mitra Laundry)',
                'phone' => '081234567890',
                'business_name' => 'Seven Laundry Melati Jakarta',
                'category' => 'laundry',
                'regency_search' => 'Jakarta Selatan',
                'address' => 'Jl. RS Fatmawati No. 45, Cilandak, Jakarta Selatan',
                'latitude' => -6.2615,
                'longitude' => 106.8106,
                'bank_name' => 'BCA',
                'bank_account_number' => '8730129841',
                'bank_account_holder' => 'Budi Santoso',
                'operational_hours' => '07:30 - 21:00 WIB',
            ],
            [
                'email' => 'mitra.cleaning@sevenkos.com',
                'name' => 'Agus Supriyanto (Mitra Kebersihan)',
                'phone' => '081298765432',
                'business_name' => 'Seven Clean Bandung Parahyangan',
                'category' => 'cleaning',
                'regency_search' => 'Kota Bandung',
                'address' => 'Jl. Dago Asri No. 18, Coblong, Kota Bandung',
                'latitude' => -6.8920,
                'longitude' => 107.6105,
                'bank_name' => 'Bank Mandiri',
                'bank_account_number' => '137009823145',
                'bank_account_holder' => 'Agus Supriyanto',
                'operational_hours' => '08:00 - 18:00 WIB',
            ],
            [
                'email' => 'mitra.teknisi@sevenkos.com',
                'name' => 'Dedi Suryana (Mitra Teknisi)',
                'phone' => '081223344556',
                'business_name' => 'Seven Care Teknisi AC Depok',
                'category' => 'maintenance',
                'regency_search' => 'Kota Depok',
                'address' => 'Jl. Margonda Raya No. 120, Beji, Kota Depok',
                'latitude' => -6.3686,
                'longitude' => 106.8331,
                'bank_name' => 'BRI',
                'bank_account_number' => '034101002345501',
                'bank_account_holder' => 'Dedi Suryana',
                'operational_hours' => '08:30 - 19:00 WIB',
            ],
            [
                'email' => 'mitra.catering@sevenkos.com',
                'name' => 'Rian Hidayat (Mitra Katering)',
                'phone' => '081345678901',
                'business_name' => 'Seven Kitchen UGM Jogja',
                'category' => 'catering',
                'regency_search' => 'Sleman',
                'address' => 'Jl. Kaliurang KM 5.5, Depok, Sleman, D.I. Yogyakarta',
                'latitude' => -7.7713,
                'longitude' => 110.3770,
                'bank_name' => 'BNI',
                'bank_account_number' => '0823918239',
                'bank_account_holder' => 'Rian Hidayat',
                'operational_hours' => '06:00 - 20:00 WIB',
            ],
        ];

        foreach ($partnersData as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make('password'),
                    'status' => 'active',
                    'email_verified_at' => now(),
                    'wallet_balance' => 0,
                ]
            );

            $user->syncRoles(['mitra']);

            $regency = Regency::where('name', 'LIKE', '%'.$data['regency_search'].'%')->first();
            $provinceId = $regency?->province_id ?? Province::first()?->id;

            ServicePartner::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'business_name' => $data['business_name'],
                    'category' => $data['category'],
                    'province_id' => $provinceId,
                    'regency_id' => $regency?->id,
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'service_radius_km' => 5.0,
                    'is_open' => true,
                    'bank_name' => $data['bank_name'],
                    'bank_account_number' => $data['bank_account_number'],
                    'bank_account_holder' => $data['bank_account_holder'],
                    'verification_status' => 'verified',
                    'operational_hours' => $data['operational_hours'],
                ]
            );
        }
    }
}
