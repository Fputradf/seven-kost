<?php

namespace Database\Seeders;

use App\Models\AdsPackage;
use App\Models\AdsPromotion;
use App\Models\Booking;
use App\Models\Kost;
use App\Models\Report;
use App\Models\Room;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DashboardDemoSeeder extends Seeder
{
    public function run(): void
    {
        $kosts = Kost::all();
        $users = User::whereHas('roles', fn ($q) => $q->where('name', 'penghuni'))->get();

        if ($users->isEmpty()) {
            $users = User::where('id', '>', 5)->get();
        }

        $packages = AdsPackage::all();

        // 1. Seed Bookings across 6 months
        if (Booking::count() === 0 && $kosts->isNotEmpty() && $users->isNotEmpty()) {
            $now = Carbon::now();
            $sampleBookings = [
                ['months_ago' => 5, 'duration' => 3, 'amount' => 4500000, 'status' => 'completed'],
                ['months_ago' => 4, 'duration' => 1, 'amount' => 1500000, 'status' => 'completed'],
                ['months_ago' => 4, 'duration' => 6, 'amount' => 7800000, 'status' => 'completed'],
                ['months_ago' => 3, 'duration' => 1, 'amount' => 1800000, 'status' => 'completed'],
                ['months_ago' => 3, 'duration' => 3, 'amount' => 5400000, 'status' => 'completed'],
                ['months_ago' => 2, 'duration' => 1, 'amount' => 2200000, 'status' => 'completed'],
                ['months_ago' => 2, 'duration' => 2, 'amount' => 3600000, 'status' => 'completed'],
                ['months_ago' => 1, 'duration' => 1, 'amount' => 1750000, 'status' => 'active'],
                ['months_ago' => 1, 'duration' => 3, 'amount' => 5100000, 'status' => 'active'],
                ['months_ago' => 1, 'duration' => 6, 'amount' => 9000000, 'status' => 'active'],
                ['months_ago' => 0, 'duration' => 1, 'amount' => 1650000, 'status' => 'approved'],
                ['months_ago' => 0, 'duration' => 2, 'amount' => 3500000, 'status' => 'pending'],
                ['months_ago' => 0, 'duration' => 1, 'amount' => 2000000, 'status' => 'pending'],
            ];

            foreach ($sampleBookings as $idx => $item) {
                $kost = $kosts[$idx % $kosts->count()];
                $user = $users[$idx % $users->count()];
                $room = Room::where('kost_id', $kost->id)->first() ?? Room::first();

                if (! $room) {
                    continue;
                }

                $createdAt = $now->copy()->subMonths($item['months_ago'])->subDays($idx * 2 + 1);
                $startDate = $createdAt->copy()->addDays(2);
                $endDate = $startDate->copy()->addMonths($item['duration']);

                Booking::create([
                    'user_id' => $user->id,
                    'kost_id' => $kost->id,
                    'room_id' => $room->id,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'duration_months' => $item['duration'],
                    'total_amount' => $item['amount'],
                    'status' => $item['status'],
                    'tenant_name' => $user->name,
                    'tenant_phone' => $user->phone ?? '08123456789',
                    'notes' => 'Pengajuan sewa via platform SevenKost.',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // 2. Seed Ads Promotions
        if (AdsPromotion::count() === 0 && $kosts->isNotEmpty() && $packages->isNotEmpty()) {
            $now = Carbon::now();
            $sampleAds = [
                ['months_ago' => 5, 'pkg_idx' => 0, 'cpc' => 125000, 'clicks' => 250, 'status' => 'expired'],
                ['months_ago' => 4, 'pkg_idx' => 1, 'cpc' => 280000, 'clicks' => 560, 'status' => 'expired'],
                ['months_ago' => 3, 'pkg_idx' => 2, 'cpc' => 450000, 'clicks' => 900, 'status' => 'expired'],
                ['months_ago' => 2, 'pkg_idx' => 1, 'cpc' => 310000, 'clicks' => 620, 'status' => 'expired'],
                ['months_ago' => 1, 'pkg_idx' => 0, 'cpc' => 195000, 'clicks' => 390, 'status' => 'active'],
                ['months_ago' => 0, 'pkg_idx' => 2, 'cpc' => 520000, 'clicks' => 1040, 'status' => 'active'],
                ['months_ago' => 0, 'pkg_idx' => 1, 'cpc' => 150000, 'clicks' => 300, 'status' => 'pending'],
            ];

            foreach ($sampleAds as $idx => $item) {
                $kost = $kosts[$idx % $kosts->count()];
                $package = $packages[$item['pkg_idx'] % $packages->count()];
                $createdAt = $now->copy()->subMonths($item['months_ago'])->subDays($idx * 3 + 1);

                AdsPromotion::create([
                    'kost_id' => $kost->id,
                    'ads_package_id' => $package->id,
                    'starts_at' => $createdAt,
                    'ends_at' => $createdAt->copy()->addDays($package->duration_days),
                    'status' => $item['status'],
                    'click_count' => $item['clicks'],
                    'cpc_spent' => $item['cpc'],
                    'admin_notes' => 'Promosi iklan kos aktif di halaman jelajah.',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }

        // 3. Seed Reports
        if (Report::count() === 0 && $kosts->isNotEmpty() && $users->isNotEmpty()) {
            Report::create([
                'user_id' => $users->first()->id,
                'kost_id' => $kosts->first()->id,
                'reason' => 'Informasi Kamar Tidak Sesuai',
                'details' => 'Foto kamar mandi dalam pada iklan berbeda dengan aslinya saat disurvei.',
                'status' => 'pending',
                'admin_notes' => null,
            ]);

            Report::create([
                'user_id' => $users->last()->id,
                'kost_id' => $kosts->last()->id,
                'reason' => 'Nomor Pemilik Tidak Dapat Dihubungi',
                'details' => 'Nomor WhatsApp pemilik kos tidak aktif lebih dari seminggu.',
                'status' => 'resolved',
                'admin_notes' => 'Sudah diverifikasi dan pemilik telah memperbarui nomor kontak.',
            ]);
        }
    }
}
