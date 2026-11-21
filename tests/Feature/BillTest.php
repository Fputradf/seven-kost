<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BillTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
    }

    /**
     * Test penghuni dapat melihat halaman daftar tagihan dengan transaksi pending.
     */
    public function test_tenant_can_view_bills_page_with_pending_transactions(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $trx = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'TOPUP-TEST-BILL',
            'type' => 'topup',
            'item_id' => 'topup',
            'title' => 'Top Up Saldo Dompet Rp 100.000',
            'amount' => 100000,
            'payment_channel' => 'manual',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.bills.index'));
        $response->assertStatus(200);
        $response->assertSee('Tagihan &amp; Pembayaran', false);
        $response->assertSee('TOPUP-TEST-BILL');
        $response->assertSee('Bayar Sekarang');
        $response->assertSee(route('tenant.wallet.payment', $trx->reference_id));

        // Test halaman checkout pembayaran
        $checkoutResponse = $this->actingAs($tenant)->get(route('tenant.wallet.payment', $trx->reference_id));
        $checkoutResponse->assertStatus(200);
        $checkoutResponse->assertSee('Pilih Metode Pembayaran');
        $checkoutResponse->assertSee('QRIS Standar Nasional');
        $checkoutResponse->assertSee('SEVEN KOST INDONESIA');
        $checkoutResponse->assertSee('ID102026778901');
        $content = $checkoutResponse->getContent();
        $this->assertTrue(str_contains($content, '<svg') || str_contains($content, 'data:image'));
    }

    /**
     * Test penghuni dapat membatalkan tagihan pending.
     */
    public function test_tenant_can_cancel_pending_bill(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        $trx = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'TOPUP-CANCEL-ME',
            'type' => 'topup',
            'item_id' => 'topup',
            'title' => 'Top Up Saldo Dompet Rp 50.000',
            'amount' => 50000,
            'payment_channel' => 'manual',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($tenant)->post(route('tenant.bills.cancel', $trx->reference_id));
        $response->assertSessionHas('success');
        $this->assertEquals('failed', $trx->fresh()->status);
    }

    /**
     * Test dashboard menampilkan banner peringatan tagihan jika ada transaksi pending.
     */
    public function test_dashboard_displays_pending_bills_banner(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'SRV-PENDING-DASH',
            'type' => 'service',
            'item_id' => 'laundry',
            'title' => 'Seven Laundry Kos: Cuci Setrika',
            'amount' => 25000,
            'payment_channel' => 'manual',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Tagihan Menunggu Pembayaran');
        $response->assertSee('SRV-PENDING-DASH');
        $response->assertSee('Bayar');
    }

    /**
     * Test halaman dompet menampilkan kartu alert topup yang masih pending.
     */
    public function test_wallet_displays_pending_topup_alert(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'TOPUP-WALLET-PENDING',
            'type' => 'topup',
            'item_id' => 'topup',
            'title' => 'Top Up Saldo Dompet',
            'amount' => 150000,
            'payment_channel' => 'manual',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($tenant)->get(route('tenant.wallet.index'));
        $response->assertStatus(200);
        $response->assertSee('Top Up Menunggu Pembayaran');
        $response->assertSee('TOPUP-WALLET-PENDING');
        $response->assertSee('Bayar');
    }

    /**
     * Test perhitungan batas waktu kadaluarsa (expiry duration) sesuai jenis transaksi.
     */
    public function test_transaction_expiry_durations_are_calculated_correctly_for_topup_booking_and_renewal(): void
    {
        $tenant = User::factory()->create();

        // 1. Topup: 1 Jam
        $topup = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'TOPUP-EXPIRY-TEST',
            'type' => 'topup',
            'item_id' => (string) $tenant->id,
            'title' => 'Top Up 50k',
            'amount' => 50000,
            'status' => 'pending',
        ]);
        $this->assertEquals(3600, $topup->created_at->diffInSeconds($topup->expires_at));
        $this->assertFalse($topup->isExpired());

        // 2. Booking: 2 Jam
        $bookingTx = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'BOOKING-EXPIRY-TEST',
            'type' => 'booking',
            'item_id' => '1',
            'title' => 'Booking Kamar',
            'amount' => 1500000,
            'status' => 'pending',
        ]);
        $this->assertEquals(7200, $bookingTx->created_at->diffInSeconds($bookingTx->expires_at));
        $this->assertFalse($bookingTx->isExpired());
    }

    /**
     * Test tagihan pending yang melewati batas waktu otomatis kadaluarsa saat membuka indeks tagihan.
     */
    public function test_expired_pending_bills_are_marked_as_expired_when_viewing_bills_index(): void
    {
        $tenant = User::factory()->create();
        $tenant->assignRole('penghuni');

        // Buat top up yang dibuat 3 jam yang lalu (sudah lewat batas 1 jam)
        $expiredTrx = Transaction::create([
            'user_id' => $tenant->id,
            'reference_id' => 'TOPUP-ALREADY-EXPIRED',
            'type' => 'topup',
            'item_id' => (string) $tenant->id,
            'title' => 'Top Up Kadaluarsa',
            'amount' => 20000,
            'status' => 'pending',
            'payload' => ['expires_at' => now()->subHour()->toIso8601String()],
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subHours(3),
        ]);

        $this->assertTrue($expiredTrx->isExpired());

        // Saat membuka halaman tagihan, tagihan kadaluarsa otomatis diperbarui statusnya
        $response = $this->actingAs($tenant)->get(route('tenant.bills.index'));
        $response->assertStatus(200);

        $this->assertEquals('expired', $expiredTrx->fresh()->status);
    }
}
