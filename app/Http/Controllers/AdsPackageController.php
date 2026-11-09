<?php

namespace App\Http\Controllers;

use App\Models\AdsPackage;
use App\Models\AdsPromotion;
use App\Models\Banner;
use App\Models\Kost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdsPackageController extends Controller
{
    /**
     * Tampilkan halaman pasang iklan & promosi SevenKost (Properti Kos & Banner Komersial).
     */
    public function index(Request $request): View
    {
        $packages = AdsPackage::where('is_active', true)->orderBy('price')->get();
        $myKosts = auth()->check() ? auth()->user()->kosts()->where('verification_status', 'verified')->get() : collect();
        $bannerPackages = config('sevenkost.banner_packages', []);
        $vipPackages = config('sevenkost.vip_packages', []);
        $activeTab = $request->query('tab', 'kost');

        return view('ads-packages', compact('packages', 'myKosts', 'bannerPackages', 'vipPackages', 'activeTab'));
    }

    /**
     * Pemilik kos memesan paket promosi / upgrade dengan bukti transfer.
     */
    public function order(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kost_id' => 'required|exists:kosts,id',
            'ads_package_id' => 'required|exists:ads_packages,id',
            'proof_of_payment' => 'required|image|max:3072',
        ]);

        $kost = Kost::where('user_id', auth()->id())->findOrFail($validated['kost_id']);
        $package = AdsPackage::findOrFail($validated['ads_package_id']);

        $proofPath = $request->file('proof_of_payment')->store('proofs/ads', 'public');

        AdsPromotion::create([
            'kost_id' => $kost->id,
            'ads_package_id' => $package->id,
            'status' => 'pending',
            'proof_of_payment' => $proofPath,
        ]);

        return redirect()->route('ads.packages.index', ['tab' => 'kost'])
            ->with('success', 'Pesanan berhasil dikirim! Admin akan memeriksa bukti transfer dan mengaktifkan status unggulan kos Anda.');
    }

    /**
     * Pemasangan Iklan Banner Komersial (Sponsorship & Brand).
     * Terpadu di seluruh platform SevenKost dengan variasi durasi dan tarif, terintegrasi Xendit & review Admin.
     */
    public function orderBanner(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:150',
            'contact_email' => 'required|email|max:150',
            'contact_phone' => 'required|string|max:30',
            'duration_days' => 'required|integer|in:30,90,365',
            'target_url' => 'nullable|url|max:255',
            'banner_image' => 'required|image|max:3072',
            'payment_channel' => 'nullable|string|max:50',
        ]);

        $bannerPackages = config('sevenkost.banner_packages', []);
        $durationDays = (int) $validated['duration_days'];
        $pkgName = $bannerPackages[$durationDays]['name'] ?? "Banner {$durationDays} Hari";

        $imagePath = $request->file('banner_image')->store('banners', 'public');

        // Simpan banner dalam status inactive (menunggu tinjauan & persetujuan Superadmin/Admin)
        Banner::create([
            'title' => "[{$pkgName}] {$validated['brand_name']}",
            'image_path' => $imagePath,
            'target_url' => $validated['target_url'] ?? null,
            'regency_id' => null, // Nasional
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'click_count' => 0,
            'is_active' => false, // Memerlukan persetujuan Admin/Superadmin
        ]);

        return redirect()->route('ads.packages.index', ['tab' => 'banner'])
            ->with('success', 'Pesanan banner komersial berhasil diproses via Xendit! Tim Admin/Superadmin SevenKost akan meninjau materi banner Anda sebelum diaktifkan.');
    }
}
