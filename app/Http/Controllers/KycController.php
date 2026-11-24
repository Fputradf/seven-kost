<?php

namespace App\Http\Controllers;

use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class KycController extends Controller
{
    /**
     * Tampilkan formulir atau status verifikasi KYC pengguna.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = auth()->user();
        $kyc = $user->kyc;

        return view('kyc.index', compact('user', 'kyc'));
    }

    /**
     * Simpan atau perbarui berkas pengajuan KYC pengguna.
     */
    public function store(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $existingKyc = $user->kyc;

        $request->validate([
            'nik' => [
                'required',
                'string',
                'size:16',
                'regex:/^[0-9]{16}$/',
                Rule::unique('kyc_verifications', 'nik')->ignore($existingKyc?->id),
            ],
            'full_name' => ['required', 'string', 'max:150'],
            'ktp_image' => [$existingKyc?->ktp_path ? 'nullable' : 'required', 'image', 'max:4096'],
            'selfie_image' => [$existingKyc?->selfie_path ? 'nullable' : 'required', 'image', 'max:4096'],
            'business_document' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
            'business_document_name' => ['nullable', 'string', 'max:150'],
        ], [
            'nik.required' => 'Nomor Induk Kependudukan (NIK) wajib diisi.',
            'nik.size' => 'NIK harus berjumlah tepat 16 digit angka.',
            'nik.regex' => 'Format NIK hanya boleh berisi angka (16 digit).',
            'nik.unique' => 'NIK ini sudah terdaftar dalam sistem dan tidak dapat digunakan untuk verifikasi lagi.',
            'full_name.required' => 'Nama lengkap sesuai KTP wajib diisi.',
            'ktp_image.required' => 'Foto KTP asli wajib diunggah.',
            'selfie_image.required' => 'Foto selfie wajah bersama KTP wajib diunggah.',
        ]);

        $ktpPath = $existingKyc?->ktp_path;
        if ($request->hasFile('ktp_image')) {
            $ktpPath = $request->file('ktp_image')->store('kyc/ktp', 'public');
        }

        $selfiePath = $existingKyc?->selfie_path;
        if ($request->hasFile('selfie_image')) {
            $selfiePath = $request->file('selfie_image')->store('kyc/selfie', 'public');
        }

        $businessDocPath = $existingKyc?->business_document_path;
        if ($request->hasFile('business_document')) {
            $businessDocPath = $request->file('business_document')->store('kyc/documents', 'public');
        }

        $role = $user->roles->first()?->name ?? 'penghuni';

        KycVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'role' => $role,
                'nik' => $request->nik,
                'full_name' => $request->full_name,
                'ktp_path' => $ktpPath,
                'selfie_path' => $selfiePath,
                'business_document_path' => $businessDocPath,
                'business_document_name' => $request->business_document_name ?: ($businessDocPath ? 'Berkas Pendukung Usaha' : null),
                'status' => 'pending',
                'rejection_reason' => null,
                'submitted_at' => now(),
            ]
        );

        // Jika mitra layanan mengajukan KYC, perbarui status partner menjadi pending
        if ($user->servicePartner) {
            $user->servicePartner->update([
                'verification_status' => 'pending',
            ]);
        }

        return redirect()->route('kyc.index')->with('success', 'Pengajuan verifikasi identitas (KYC) Anda berhasil dikirim dan sedang dalam proses peninjauan oleh tim Admin SevenKost.');
    }
}
