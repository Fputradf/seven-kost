<?php

namespace App\Http\Controllers;

use App\Models\AdsPackage;
use App\Models\AdsPromotion;
use App\Models\Kost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdsPackageController extends Controller
{
    /**
     * Tampilkan halaman daftar paket iklan unggulan.
     */
    public function index(): View
    {
        $packages = AdsPackage::where('is_active', true)->orderBy('price')->get();
        $myKosts = auth()->check() ? auth()->user()->kosts()->where('verification_status', 'verified')->get() : collect();

        return view('ads-packages', compact('packages', 'myKosts'));
    }

    /**
     * Pemilik kos memesan paket iklan dengan mengunggah bukti transfer manual.
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

        return redirect()->route('ads.packages.index')
            ->with('success', 'Pesanan paket iklan unggulan berhasil dikirim! Admin akan memeriksa bukti transfer dan mengaktifkan status unggulan kos Anda.');
    }
}
