<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use App\Models\ServicePartner;
use App\Models\ServicePartnerBranch;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Simpan cabang baru untuk mitra layanan yang sedang login.
     */
    public function store(Request $request): RedirectResponse
    {
        $partner = $this->resolvePartner();

        $validated = $this->validateBranch($request);
        $validated['service_partner_id'] = $partner->id;
        $validated['is_open'] = $request->has('is_open') ? $request->boolean('is_open') : true;
        $validated['service_radius_km'] = $validated['service_radius_km'] ?? $partner->service_radius_km ?? 5.0;

        ServicePartnerBranch::create($validated);

        return redirect()->route('partner.profile.index', ['tab' => 'location'])
            ->with('success', 'Cabang baru "'.$validated['branch_name'].'" berhasil ditambahkan dan siap beroperasi.');
    }

    /**
     * Perbarui data cabang milik mitra layanan.
     */
    public function update(Request $request, ServicePartnerBranch $branch): RedirectResponse
    {
        $partner = $this->resolvePartner();
        $this->authorizeBranch($partner, $branch);

        $validated = $this->validateBranch($request);
        $validated['is_open'] = $request->has('is_open') ? $request->boolean('is_open') : false;
        $validated['service_radius_km'] = $validated['service_radius_km'] ?? $branch->service_radius_km ?? 5.0;

        $branch->update($validated);

        return redirect()->route('partner.profile.index', ['tab' => 'location'])
            ->with('success', 'Data cabang "'.$branch->branch_name.'" berhasil diperbarui.');
    }

    /**
     * Hapus cabang milik mitra layanan.
     */
    public function destroy(ServicePartnerBranch $branch): RedirectResponse
    {
        $partner = $this->resolvePartner();
        $this->authorizeBranch($partner, $branch);

        $branchName = $branch->branch_name;
        $branch->delete();

        return redirect()->route('partner.profile.index', ['tab' => 'location'])
            ->with('success', 'Cabang "'.$branchName.'" berhasil dihapus.');
    }

    /**
     * Toggle status buka/tutup operasional cabang.
     */
    public function toggleStatus(ServicePartnerBranch $branch): RedirectResponse
    {
        $partner = $this->resolvePartner();
        $this->authorizeBranch($partner, $branch);

        $newStatus = ! $branch->is_open;
        $branch->update(['is_open' => $newStatus]);

        $statusLabel = $newStatus ? 'DIBUKA (siap menerima pesanan)' : 'DITUTUP sementara';

        return redirect()->route('partner.profile.index', ['tab' => 'location'])
            ->with('success', 'Status cabang "'.$branch->branch_name.'" kini '.$statusLabel.'.');
    }

    /**
     * Validasi input formulir cabang.
     *
     * @return array<string, mixed>
     */
    private function validateBranch(Request $request): array
    {
        return $request->validate([
            'branch_name' => ['required', 'string', 'max:150'],
            'phone' => ['nullable', 'string', 'max:30'],
            'province_id' => ['required', 'string', 'max:2'],
            'regency_id' => ['required', 'string', 'max:4'],
            'address' => ['required', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'service_radius_km' => ['nullable', 'numeric', 'min:0.5', 'max:25'],
            'operational_hours' => ['nullable', 'string', 'max:100'],
        ], [
            'branch_name.required' => 'Nama cabang wajib diisi.',
            'province_id.required' => 'Provinsi lokasi cabang wajib dipilih.',
            'regency_id.required' => 'Kota/Kabupaten lokasi cabang wajib dipilih.',
            'address.required' => 'Alamat lengkap cabang wajib diisi.',
        ]);
    }

    /**
     * Pastikan pengguna memiliki peran mitra dan mengembalikan profil usahanya.
     */
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

    /**
     * Pastikan cabang benar-benar dimiliki oleh mitra yang sedang login.
     */
    private function authorizeBranch(ServicePartner $partner, ServicePartnerBranch $branch): void
    {
        if ((int) $branch->service_partner_id !== (int) $partner->id) {
            abort(403, 'Anda tidak memiliki akses ke cabang mitra ini.');
        }
    }
}
