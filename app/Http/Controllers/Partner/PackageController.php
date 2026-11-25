<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\ServicePartner;
use App\Models\ServicePartnerPackage;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * Simpan paket layanan baru milik mitra.
     */
    public function store(Request $request): RedirectResponse
    {
        $partner = $this->resolvePartner();

        $validated = $this->validatePackage($request);
        $validated['service_partner_id'] = $partner->id;
        $validated['is_active'] = true;

        ServicePartnerPackage::create($validated);

        return redirect()->route('partner.profile.index', ['tab' => 'profile'])
            ->with('success', 'Paket layanan "'.$validated['name'].'" berhasil ditambahkan.');
    }

    /**
     * Perbarui paket layanan milik mitra.
     */
    public function update(Request $request, ServicePartnerPackage $package): RedirectResponse
    {
        $partner = $this->resolvePartner();
        $this->authorizePackage($partner, $package);

        $validated = $this->validatePackage($request);
        $validated['is_active'] = true;

        $package->update($validated);

        return redirect()->route('partner.profile.index', ['tab' => 'profile'])
            ->with('success', 'Paket layanan "'.$package->name.'" berhasil diperbarui.');
    }

    /**
     * Hapus paket layanan milik mitra.
     */
    public function destroy(ServicePartnerPackage $package): RedirectResponse
    {
        $partner = $this->resolvePartner();
        $this->authorizePackage($partner, $package);

        $packageName = $package->name;
        $package->delete();

        return redirect()->route('partner.profile.index', ['tab' => 'profile'])
            ->with('success', 'Paket layanan "'.$packageName.'" berhasil dihapus.');
    }

    /**
     * Validasi input formulir paket layanan.
     *
     * @return array<string, mixed>
     */
    private function validatePackage(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'price' => ['required', 'integer', 'min:1000', 'max:100000000'],
            'unit' => ['required', 'string', 'max:30'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'name.required' => 'Nama paket layanan wajib diisi.',
            'price.required' => 'Harga paket wajib diisi.',
            'price.min' => 'Harga paket minimal Rp 1.000.',
            'unit.required' => 'Satuan paket wajib diisi (contoh: /kg, /sesi, /hari).',
        ]);
    }

    private function resolvePartner(): ServicePartner
    {
        /** @var User $user */
        $user = auth()->user();

        if (! $user || ! $user->hasRole('mitra')) {
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

        return $partner;
    }

    private function authorizePackage(ServicePartner $partner, ServicePartnerPackage $package): void
    {
        if ((int) $package->service_partner_id !== (int) $partner->id) {
            abort(403, 'Anda tidak memiliki akses ke paket layanan mitra ini.');
        }
    }
}
