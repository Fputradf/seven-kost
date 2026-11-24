<?php

namespace Tests\Feature;

use App\Models\KycVerification;
use App\Models\ServicePartner;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KycVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            RolePermissionSeeder::class,
        ]);
        Storage::fake('public');
    }

    /**
     * Test registrasi akun mitra otomatis mengarahkan ke halaman verifikasi KYC.
     */
    public function test_newly_registered_mitra_is_redirected_to_kyc_verification_page(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Mitra Baru Laundry',
            'email' => 'mitra_baru_'.uniqid().'@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'mitra',
        ]);

        $response->assertRedirect(route('kyc.index'));
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertTrue($user->hasRole('mitra'));
        $this->assertNotNull($user->servicePartner);
        $this->assertEquals('pending', $user->servicePartner->verification_status);
    }

    /**
     * Test registrasi akun penghuni tidak dipaksa verifikasi KYC dan langsung diarahkan ke dashboard.
     */
    public function test_newly_registered_penghuni_is_not_forced_to_kyc_and_redirects_to_dashboard(): void
    {
        $response = $this->post(route('register'), [
            'name' => 'Penghuni Kos Baru',
            'email' => 'penghuni_baru_'.uniqid().'@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'penghuni',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();

        $user = auth()->user();
        $this->assertTrue($user->hasRole('penghuni'));
    }

    /**
     * Test pengguna dapat membuka formulir pengajuan KYC dan mengirimkan berkas lengkap.
     */
    public function test_user_can_submit_kyc_verification_with_valid_documents(): void
    {
        $user = User::factory()->create();
        $user->assignRole('mitra');

        $partner = ServicePartner::create([
            'user_id' => $user->id,
            'business_name' => 'Laundry Express Cilandak',
            'category' => 'laundry',
            'phone' => '081234567890',
            'is_open' => true,
            'verification_status' => 'pending',
        ]);

        $nik = '3171012345678901';

        $response = $this->actingAs($user)->post(route('kyc.store'), [
            'nik' => $nik,
            'full_name' => 'Budi Santoso',
            'ktp_image' => UploadedFile::fake()->image('ktp.jpg', 800, 600),
            'selfie_image' => UploadedFile::fake()->image('selfie.jpg', 600, 600),
            'business_document' => UploadedFile::fake()->create('nib.pdf', 500, 'application/pdf'),
            'business_document_name' => 'NIB Laundry Express',
        ]);

        $response->assertRedirect(route('kyc.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('kyc_verifications', [
            'user_id' => $user->id,
            'nik' => $nik,
            'full_name' => 'Budi Santoso',
            'status' => 'pending',
            'business_document_name' => 'NIB Laundry Express',
        ]);

        // Halaman KYC kini menampilkan status "SEDANG DITINJAU ADMIN"
        $resIndex = $this->actingAs($user->fresh())->get(route('kyc.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('SEDANG DITINJAU ADMIN');
        $resIndex->assertSee('Dokumen Verifikasi Sedang Diproses');
    }

    /**
     * Test jika ada NIK yang sama, pengguna lain TIDAK BISA melakukan verifikasi (Unique Constraint).
     */
    public function test_duplicate_nik_is_strictly_rejected_with_validation_error(): void
    {
        $existingUser = User::factory()->create();
        $existingUser->assignRole('penghuni');

        $nikTerdaftar = '3271019988776655';

        // Pengguna 1 telah mendaftarkan NIK tersebut
        KycVerification::create([
            'user_id' => $existingUser->id,
            'role' => 'penghuni',
            'nik' => $nikTerdaftar,
            'full_name' => 'Ahmad Dahlan',
            'ktp_path' => 'kyc/ktp/dummy1.jpg',
            'selfie_path' => 'kyc/selfie/dummy1.jpg',
            'status' => 'verified',
            'submitted_at' => now(),
        ]);

        // Pengguna 2 mencoba mendaftar dengan NIK yang sama
        $newUser = User::factory()->create();
        $newUser->assignRole('mitra');

        $response = $this->actingAs($newUser)->post(route('kyc.store'), [
            'nik' => $nikTerdaftar, // NIK DUPLIKAT
            'full_name' => 'Orang Lain',
            'ktp_image' => UploadedFile::fake()->image('ktp2.jpg'),
            'selfie_image' => UploadedFile::fake()->image('selfie2.jpg'),
        ]);

        // Harus gagal validasi NIK unik
        $response->assertSessionHasErrors(['nik']);
        $this->assertDatabaseMissing('kyc_verifications', [
            'user_id' => $newUser->id,
        ]);
    }

    /**
     * Test format NIK harus 16 digit angka.
     */
    public function test_nik_must_be_exact_16_digits(): void
    {
        $user = User::factory()->create();
        $user->assignRole('penghuni');

        // NIK kurang dari 16 digit
        $resShort = $this->actingAs($user)->post(route('kyc.store'), [
            'nik' => '12345678',
            'full_name' => 'Budi Pendek',
            'ktp_image' => UploadedFile::fake()->image('ktp.jpg'),
            'selfie_image' => UploadedFile::fake()->image('selfie.jpg'),
        ]);
        $resShort->assertSessionHasErrors(['nik']);

        // NIK mengandung huruf
        $resAlphabet = $this->actingAs($user)->post(route('kyc.store'), [
            'nik' => '317101ABC4567890',
            'full_name' => 'Budi Huruf',
            'ktp_image' => UploadedFile::fake()->image('ktp.jpg'),
            'selfie_image' => UploadedFile::fake()->image('selfie.jpg'),
        ]);
        $resAlphabet->assertSessionHasErrors(['nik']);
    }

    /**
     * Test persetujuan admin memperbarui status KYC menjadi verified dan mengaktifkan mitra.
     */
    public function test_admin_approval_sets_kyc_status_to_verified_and_activates_partner(): void
    {
        $user = User::factory()->create();
        $user->assignRole('mitra');

        $partner = ServicePartner::create([
            'user_id' => $user->id,
            'business_name' => 'Teknisi AC Cepat',
            'category' => 'maintenance',
            'phone' => '081234567890',
            'is_open' => true,
            'verification_status' => 'pending',
        ]);

        $kyc = KycVerification::create([
            'user_id' => $user->id,
            'role' => 'mitra',
            'nik' => '3578012345678901',
            'full_name' => 'Santoso Teknisi',
            'ktp_path' => 'kyc/ktp/ktp.jpg',
            'selfie_path' => 'kyc/selfie/selfie.jpg',
            'status' => 'pending',
            'submitted_at' => now(),
        ]);

        $this->assertFalse($user->fresh()->isKycVerified());

        // Admin approve
        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $kyc->update([
            'status' => 'verified',
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
        ]);
        $partner->update(['verification_status' => 'verified']);

        $this->assertTrue($user->fresh()->isKycVerified());
        $this->assertEquals('verified', $user->fresh()->servicePartner->verification_status);

        // Halaman KYC menampilkan badge Terverifikasi Resmi
        $resIndex = $this->actingAs($user)->get(route('kyc.index'));
        $resIndex->assertStatus(200);
        $resIndex->assertSee('TERVERIFIKASI RESMI');
    }
}
