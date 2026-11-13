<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles & permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Pastikan role superadmin tersedia
        $role = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);

        // Akun Utama Super Admin
        $superadmin = User::updateOrCreate(
            ['email' => 'superadmin@sevenkos.com'],
            [
                'name' => 'Super Administrator Seven Kost',
                'phone' => '081100000001',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $superadmin->syncRoles([$role]);

        // Akun Alternatif (admin@sevenkos.com jika sudah terdaftar sebelumnya)
        $altAdmin = User::updateOrCreate(
            ['email' => 'admin@sevenkos.com'],
            [
                'name' => 'Super Admin Seven Kost',
                'phone' => '081100000002',
                'password' => Hash::make('password'),
                'status' => 'active',
                'email_verified_at' => now(),
            ]
        );
        $altAdmin->syncRoles([$role]);
    }
}
