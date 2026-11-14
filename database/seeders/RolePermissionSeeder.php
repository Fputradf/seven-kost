<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
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

        // 1b. Buat Hak Akses (Permissions) Sistem Terstruktur
        $permissions = [
            // Modul Pengguna
            'users.view',
            'users.create',
            'users.edit',
            'users.delete',
            'users.suspend',

            // Modul Peran & Akses
            'roles.view',
            'roles.create',
            'roles.edit',
            'roles.delete',

            // Modul Kos & Verifikasi
            'kosts.view',
            'kosts.create',
            'kosts.edit',
            'kosts.verify',
            'kosts.delete',

            // Modul Iklan & Paket Promosi
            'ads.view',
            'ads.create',
            'ads.verify',
            'ads.packages',

            // Modul Banner
            'banners.view',
            'banners.manage',

            // Modul Transaksi & Booking
            'bookings.view',
            'bookings.manage',

            // Modul Marketplace Kos
            'marketplace.view',
            'marketplace.moderate',

            // Modul Laporan & Moderasi
            'reports.view',
            'reports.resolve',

            // Modul Pengaturan Platform
            'settings.view',
            'settings.edit',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // Berikan semua izin ke superadmin
        $superadminRole = Role::where('name', 'superadmin')->first();
        if ($superadminRole) {
            $superadminRole->syncPermissions(Permission::all());
        }

        // Berikan izin operasional ke admin verifikator
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions([
                'users.view', 'users.suspend',
                'kosts.view', 'kosts.verify', 'kosts.edit',
                'ads.view', 'ads.verify',
                'banners.view', 'banners.manage',
                'bookings.view', 'bookings.manage',
                'marketplace.view', 'marketplace.moderate',
                'reports.view', 'reports.resolve',
                'settings.view',
            ]);
        }

        // 2. Akun SUPERADMIN (Akses penuh Filament Admin Panel & Sistem)
        $superadmin = User::updateOrCreate(
            ['email' => 'admin@sevenkos.com'],
            [
                'name' => 'Super Admin Seven Kost',
                'phone' => '081100000001',
                'password' => Hash::make('password'),
                'status' => 'active',
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
