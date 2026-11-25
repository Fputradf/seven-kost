<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Province;
use App\Models\Regency;
use App\Models\ServicePartner;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman formulir pengaturan profil usaha mitra (/mitra/profil).
     */
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user->hasRole('mitra')) {
            abort(403, 'Akses terbatas untuk akun Mitra Layanan SevenKost.');
        }

        $partner = $user->servicePartner;
        if (! $partner) {
            $partner = ServicePartner::create([
                'user_id' => $user->id,
                'business_name' => 'Mitra Layanan '.$user->name,
                'category' => 'laundry',
                'phone' => $user->phone,
                'is_open' => true,
                'verification_status' => 'verified',
            ]);
        }

        $partner->load(['branches.province', 'branches.regency', 'packages']);

        $provinces = Province::orderBy('name')->get();
        $regencies = $partner->province_id
            ? Regency::where('province_id', $partner->province_id)->orderBy('name')->get(['id', 'name', 'type', 'latitude', 'longitude'])
            : collect();

        $banner = Banner::where('user_id', $user->id)->first();

        return view('partner.profile', compact('partner', 'provinces', 'regencies', 'banner'));
    }

    /**
     * Perbarui data profil usaha mitra.
     */
    public function update(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:150'],
            'category' => ['required', 'string', 'in:laundry,cleaning,catering,maintenance'],
            'phone' => ['required', 'string', 'max:30'],
            'province_id' => ['nullable', 'string', 'max:2'],
            'regency_id' => ['nullable', 'string', 'max:4'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'service_radius_km' => ['nullable', 'numeric', 'min:0.5', 'max:25'],
            'operational_hours' => ['nullable', 'string', 'max:100'],
            'bank_name' => ['nullable', 'string', 'max:100'],
            'bank_account_number' => ['nullable', 'string', 'max:100'],
            'bank_account_holder' => ['nullable', 'string', 'max:150'],
            'avatar' => ['nullable', 'image', 'max:2048'],
            'banner_image' => ['nullable', 'image', 'max:4096'],
            'banner_title' => ['nullable', 'string', 'max:150'],
            'banner_target_url' => ['nullable', 'url', 'max:255'],
            'banner_is_active' => ['nullable', 'boolean'],
            'service_features' => ['nullable', 'array', 'max:6'],
            'service_features.*' => ['nullable', 'string', 'max:150'],
        ], [
            'business_name.required' => 'Nama brand atau usaha mitra wajib diisi.',
            'category.required' => 'Pilih kategori layanan utama Anda.',
            'phone.required' => 'Nomor kontak telepon atau WhatsApp operasional wajib diisi.',
            'banner_target_url.url' => 'Format tautan target banner harus berupa URL valid (contoh: https://...).',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->update(['avatar' => $avatarPath]);
        }

        $bannerPath = null;
        if ($request->hasFile('banner_image')) {
            $bannerPath = $request->file('banner_image')->store('service-cards', 'public');
            $validated['banner_image'] = $bannerPath;
        }

        $partnerData = collect($validated)->except(['banner_title', 'banner_target_url', 'banner_is_active'])->all();
        if ($request->has('service_features_submitted')) {
            $cleanedFeatures = array_values(array_filter(
                array_map(fn ($item) => trim((string) $item), $request->input('service_features', [])),
                fn ($item) => $item !== ''
            ));
            $partnerData['service_features'] = ! empty($cleanedFeatures) ? $cleanedFeatures : null;
        }
        $partner->update($partnerData);

        // Sinkronisasi Banner/Katalog Layanan Mitra (tampil di halaman /layanan)
        $existingBanner = Banner::where('user_id', $user->id)->first();
        $bannerImagePath = $bannerPath ?? $existingBanner?->image_path ?? $partner->banner_image ?? 'images/frontend/service-laundry.jpg';
        $bannerTitle = $request->input('banner_title') ?: ($existingBanner?->title ?: ('Promo Spesial '.$validated['business_name']));
        $bannerTargetUrl = $request->input('banner_target_url') ?: ($existingBanner?->target_url ?: route('services.index'));
        $bannerIsActive = $request->has('banner_is_active') ? $request->boolean('banner_is_active') : ($existingBanner ? (bool) $existingBanner->is_active : true);

        Banner::updateOrCreate(
            ['user_id' => $user->id],
            [
                'title' => $bannerTitle,
                'image_path' => $bannerImagePath,
                'target_url' => $bannerTargetUrl,
                'advertiser_name' => $validated['business_name'],
                'regency_id' => $validated['regency_id'] ?? $partner->regency_id,
                'is_active' => $bannerIsActive,
                'starts_at' => now()->subMinutes(5),
                'ends_at' => null,
                'payment_status' => 'paid',
            ]
        );

        $activeTab = $request->input('active_tab');

        return redirect()->route('partner.profile.index', array_filter(['tab' => $activeTab]))
            ->with('success', 'Pengaturan usaha dan gambar kartu layanan Anda berhasil diperbarui.');
    }

    /**
     * Toggle cepat status buka/tutup penerimaan pesanan mitra.
     */
    public function toggleStatus(): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $partner = $user->servicePartner;

        if ($partner) {
            $newStatus = ! $partner->is_open;
            $partner->update(['is_open' => $newStatus]);

            $message = $newStatus
                ? 'Status toko diaktifkan. Anda sekarang SIAP MENERIMA pesanan masuk dari penghuni kos.'
                : 'Status toko dinonaktifkan sementara. Toko Anda ditandai LIBUR/ISTIRAHAT.';

            return back()->with('success', $message);
        }

        return back();
    }
}
