<?php

namespace Database\Seeders;

use App\Models\Province;
use App\Models\Regency;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataPath = database_path('data');
        if (!File::exists($dataPath)) {
            File::makeDirectory($dataPath, 0755, true);
        }

        $provincesFile = $dataPath . '/provinces.json';
        $regenciesFile = $dataPath . '/regencies.json';

        // 1. Ambil data Provinsi (Gunakan cache lokal jika ada, atau unduh jika belum)
        if (File::exists($provincesFile)) {
            $provinces = json_decode(File::get($provincesFile), true);
        } else {
            $this->command->info('Mengunduh data Provinsi dari repository resmi...');
            $response = Http::timeout(30)->get('https://emsifa.github.io/api-wilayah-indonesia/api/provinces.json');
            if ($response->successful()) {
                $provinces = $response->json();
                File::put($provincesFile, json_encode($provinces, JSON_PRETTY_PRINT));
            } else {
                $this->command->error('Gagal mengunduh data provinsi.');
                return;
            }
        }

        // 2. Ambil data Kabupaten / Kota untuk semua provinsi
        if (File::exists($regenciesFile)) {
            $regencies = json_decode(File::get($regenciesFile), true);
        } else {
            $this->command->info('Mengunduh data Kabupaten / Kota (514 Kab/Kota)...');
            $regencies = [];
            foreach ($provinces as $province) {
                $provId = $province['id'];
                $response = Http::timeout(15)->get("https://emsifa.github.io/api-wilayah-indonesia/api/regencies/{$provId}.json");
                if ($response->successful()) {
                    $regList = $response->json();
                    foreach ($regList as $reg) {
                        $regencies[] = $reg;
                    }
                }
            }
            File::put($regenciesFile, json_encode($regencies, JSON_PRETTY_PRINT));
        }

        // 3. Masukkan ke database tabel provinces
        $this->command->info('Memasukkan data Provinsi ke database...');
        $provinceRows = [];
        $now = now();
        foreach ($provinces as $prov) {
            $provinceRows[] = [
                'id' => (string) $prov['id'],
                'name' => ucwords(strtolower($prov['name'])),
                'latitude' => null,
                'longitude' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        Province::upsert($provinceRows, ['id'], ['name', 'updated_at']);

        // 4. Masukkan ke database tabel regencies
        $this->command->info('Memasukkan data Kabupaten/Kota ke database...');
        $regencyRows = [];
        foreach ($regencies as $reg) {
            $rawName = $reg['name'];
            $isKota = str_starts_with(strtoupper($rawName), 'KOTA');
            $type = $isKota ? 'Kota' : 'Kabupaten';

            // Bersihkan prefix 'KABUPATEN ' atau 'KOTA ' untuk tampilan yang rapi jika diinginkan
            $cleanName = ucwords(strtolower($rawName));

            $regencyRows[] = [
                'id' => (string) $reg['id'],
                'province_id' => (string) $reg['province_id'],
                'name' => $cleanName,
                'type' => $type,
                'latitude' => null,
                'longitude' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Batch insert/upsert tiap 100 baris agar ringan
        foreach (array_chunk($regencyRows, 100) as $chunk) {
            Regency::upsert($chunk, ['id'], ['province_id', 'name', 'type', 'updated_at']);
        }

        $this->command->info('Selesai! ' . count($provinceRows) . ' Provinsi dan ' . count($regencyRows) . ' Kabupaten/Kota berhasil tersimpan.');
    }
}

