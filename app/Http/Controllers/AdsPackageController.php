<?php

namespace App\Http\Controllers;

use App\Models\AdsPackage;
use App\Models\AdsPromotion;
use App\Models\Banner;
use App\Models\Kost;
use App\Models\Transaction;
use App\Services\XenditService;
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
        $bannerPackages = app_banner_packages();
        $vipPackages = app_vip_packages();
        $activeTab = $request->query('tab', 'kost');

        return view('ads-packages', compact('packages', 'myKosts', 'bannerPackages', 'vipPackages', 'activeTab'));
    }

    /**
     * Pemilik kos memesan paket promosi / upgrade manual dengan bukti transfer (Legacy compatibility).
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
     * Pemilik memesan dan berlangganan Akun VIP langsung via Xendit Payment Gateway.
     */
    public function orderVip(Request $request, XenditService $xenditService): RedirectResponse
    {
        $validated = $request->validate([
            'vip_package_id' => 'required|in:1_month,3_months,1_year',
            'payment_channel' => 'required|in:qris,bca_va,mandiri_va,bri_va,bni_va,ewallet',
        ]);

        $vipPackages = app_vip_packages();
        $pkgId = $validated['vip_package_id'];
        $package = $vipPackages[$pkgId] ?? abort(404, 'Paket VIP tidak ditemukan');

        $price = (float) $package['price'];
        $pkgName = $package['name'];
        $refId = 'INV-VIP-'.strtoupper(uniqid());

        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'reference_id' => $refId,
            'type' => 'vip',
            'item_id' => $pkgId,
            'title' => "Berlangganan {$pkgName} SevenKost",
            'amount' => $price,
            'payment_channel' => $validated['payment_channel'],
            'status' => 'pending',
            'payload' => [
                'package_id' => $pkgId,
                'duration_label' => $package['duration_label'] ?? '',
            ],
        ]);

        $xenditService->createInvoice($transaction, [
            'name' => auth()->user()?->name,
            'email' => auth()->user()?->email,
            'phone' => auth()->user()?->phone,
        ]);

        return redirect()->route('payments.checkout', $transaction->reference_id)
            ->with('success', "Pesanan {$pkgName} berhasil dibuat! Silakan selesaikan pembayaran via Xendit.");
    }

    /**
     * Pemasangan Iklan Banner Komersial (Sponsorship & Brand).
     * Terintegrasi langsung dengan Xendit Payment Gateway.
     */
    public function orderBanner(Request $request, XenditService $xenditService): RedirectResponse
    {
        $validated = $request->validate([
            'brand_name' => 'required|string|max:150',
            'contact_email' => 'required|email|max:150',
            'contact_phone' => 'required|string|max:30',
            'duration_days' => 'required|integer|in:30,90,365',
            'target_url' => 'nullable|url|max:255',
            'banner_image' => 'required|image|max:3072',
            'payment_channel' => 'nullable|string|in:qris,bca_va,mandiri_va,bri_va,bni_va,ewallet',
        ]);

        $bannerPackages = app_banner_packages();
        $durationDays = (int) $validated['duration_days'];
        $pkgName = $bannerPackages[$durationDays]['name'] ?? "Banner {$durationDays} Hari";
        $price = (float) ($bannerPackages[$durationDays]['price'] ?? 250000);
        $paymentChannel = $validated['payment_channel'] ?? 'qris';

        $imagePath = $request->file('banner_image')->store('banners', 'public');

        // Simpan banner dalam status inactive (menunggu verifikasi pembayaran & persetujuan Admin)
        $banner = Banner::create([
            'title' => "[{$pkgName}] {$validated['brand_name']}",
            'image_path' => $imagePath,
            'target_url' => $validated['target_url'] ?? null,
            'user_id' => auth()->id(),
            'advertiser_name' => $validated['brand_name'],
            'regency_id' => null, // Nasional
            'starts_at' => now(),
            'ends_at' => now()->addDays($durationDays),
            'click_count' => 0,
            'is_active' => false,
            'payment_status' => 'pending',
            'payment_channel' => $paymentChannel,
            'amount' => $price,
        ]);

        $refId = 'INV-BNR-'.strtoupper(uniqid());
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'reference_id' => $refId,
            'type' => 'banner',
            'item_id' => (string) $banner->id,
            'title' => "Pemasangan {$pkgName} ({$validated['brand_name']})",
            'amount' => $price,
            'payment_channel' => $paymentChannel,
            'status' => 'pending',
            'payload' => [
                'banner_id' => $banner->id,
                'brand_name' => $validated['brand_name'],
                'duration_days' => $durationDays,
            ],
        ]);

        $xenditService->createInvoice($transaction, [
            'name' => $validated['brand_name'],
            'email' => $validated['contact_email'],
            'phone' => $validated['contact_phone'],
        ]);

        return redirect()->route('payments.checkout', $transaction->reference_id)
            ->with('success', 'Pesanan banner komersial berhasil diproses via Xendit! Silakan selesaikan pembayaran.');
    }
}
