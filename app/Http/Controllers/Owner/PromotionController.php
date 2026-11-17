<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PromotionController extends Controller
{
    /**
     * Tampilkan pusat kemitraan & promosi pemilik (/owner/promosi).
     * Terintegrasi dengan Akun VIP Pemilik & Iklan Banner Komersial.
     */
    public function index(): View
    {
        $user = auth()->user();

        if (! $user->hasAnyRole(['pemilik', 'admin', 'superadmin'])) {
            if ($user->kosts()->exists()) {
                $user->assignRole('pemilik');
            } else {
                abort(403, 'Akses terbatas untuk Mitra Pemilik Kos.');
            }
        }

        $myKosts = $user->kosts()
            ->with(['primaryImage', 'images', 'rooms'])
            ->latest()
            ->get();

        // Riwayat banner komersial pemilik
        $banners = $user->banners()
            ->latest()
            ->get();

        // Paket VIP dan Banner dinamis dari pengaturan sistem
        $vipPackages = app_vip_packages();
        $bannerPackages = app_banner_packages();

        // Metrik Ringkasan Promosi & Status Akun
        $activeBannersCount = $banners->where('is_active', true)->count();
        $totalBannerClicks = $banners->sum('click_count');
        $kostsCount = $myKosts->count();
        $isVip = $user->isVip();

        return view('owner.promosi.index', compact(
            'myKosts',
            'banners',
            'vipPackages',
            'bannerPackages',
            'activeBannersCount',
            'totalBannerClicks',
            'kostsCount',
            'isVip'
        ));
    }
}
