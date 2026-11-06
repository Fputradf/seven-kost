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

        // 1. Buat 5 peran MVP
        $roles = [
            'superadmin',
            'admin',
            'pemilik',
            'penghuni',
            'pengunjung',
        ];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
        }

        // 2. Berikan role superadmin ke akun admin@sevenkos.com yang sudah dibuat sebelumnya
        $superadmin = User::where('email', 'admin@sevenkos.com')->first();
        if ($superadmin) {
            $superadmin->assignRole('superadmin');
        } else {
            // Jika belum ada, buat akun superadmin default
            $superadmin = User::firstOrCreate(
                ['email' => 'admin@sevenkos.com'],
                [
                    'name' => 'Super Admin Seven Kost',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );
            $superadmin->assignRole('superadmin');
        }

        // 3. Buat contoh user untuk pemilik dan penghuni (untuk memudahkan testing)
        $pemilik = User::firstOrCreate(
            ['email' => 'pemilik@sevenkos.com'],
            [
                'name' => 'Budi Pemilik Kos',
                'phone' => '081234567890',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $pemilik->assignRole('pemilik');

        $penghuni = User::firstOrCreate(
            ['email' => 'penghuni@sevenkos.com'],
            [
                'name' => 'Siti Calon Penghuni',
                'phone' => '089876543210',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $penghuni->assignRole('penghuni');
    }
}

