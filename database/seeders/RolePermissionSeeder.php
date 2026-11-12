<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Buat peran MVP & Mitra Layanan
        $roles = [
            'superadmin',
            'admin',
            'pemilik',
            'penghuni',
            'pengunjung',
            'mitra',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Akun SUPERADMIN (Akses penuh Filament Admin Panel & Sistem)
        $superadmin = User::updateOrCreate(
            ['email' => 'admin@sevenkos.com'],
            [
                'name' => 'Super Admin Seven Kost',
                'phone' => '081100000001',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superadmin->syncRoles(['superadmin']);

        // 3. Akun ADMIN (Staf Operasional, Verifikator Iklan Kos & Layanan)
        $admin = User::updateOrCreate(
            ['email' => 'admin.operasional@sevenkos.com'],
            [
                'name' => 'Maya Kartika (Admin Verifikator)',
                'phone' => '081100000002',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->syncRoles(['admin']);

        // 4. Akun PEMILIK KOS (Mitra Pemilik Properti)
        $pemilik1 = User::updateOrCreate(
            ['email' => 'pemilik@sevenkos.com'],
            [
                'name' => 'Budi Santoso (Mitra Jakarta & Depok)',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $pemilik1->syncRoles(['pemilik']);

        $pemilik2 = User::updateOrCreate(
            ['email' => 'pemilik.ratna@sevenkos.com'],
            [
                'name' => 'Hj. Ratna Sari (Mitra Yogyakarta)',
                'phone' => '081298765432',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $pemilik2->syncRoles(['pemilik']);

        $pemilik3 = User::updateOrCreate(
            ['email' => 'pemilik.hendra@sevenkos.com'],
            [
                'name' => 'Hendra Wijaya (Mitra Bandung & Surabaya)',
                'phone' => '081377889900',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $pemilik3->syncRoles(['pemilik']);

        // 5. Akun PENGHUNI (Penyewa Kos Terdaftar)
        $penghuni1 = User::updateOrCreate(
            ['email' => 'penghuni@sevenkos.com'],
            [
                'name' => 'Siti Nurhaliza (Mahasiswi UGM)',
                'phone' => '089876543210',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $penghuni1->syncRoles(['penghuni']);

        $penghuni2 = User::updateOrCreate(
            ['email' => 'penghuni.rizky@sevenkos.com'],
            [
                'name' => 'Rizky Pratama (Karyawan Swasta)',
                'phone' => '085711223344',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $penghuni2->syncRoles(['penghuni']);

        // 6. Akun PENGUNJUNG (Pencari Kos Baru)
        $pengunjung = User::updateOrCreate(
            ['email' => 'pengunjung@sevenkos.com'],
            [
                'name' => 'Ahmad Fauzi (Pencari Kos)',
                'phone' => '087899887766',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $pengunjung->syncRoles(['pengunjung']);
    }
}
