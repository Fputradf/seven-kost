<?php

use App\Http\Controllers\AdsPackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KostDetailController;
use App\Http\Controllers\KycController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\Owner\ComplaintController as OwnerComplaintController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\KostController as OwnerKostController;
use App\Http\Controllers\Owner\PromotionController as OwnerPromotionController;
use App\Http\Controllers\Owner\RoomTenantController as OwnerRoomTenantController;
use App\Http\Controllers\Partner\DashboardController as PartnerDashboardController;
use App\Http\Controllers\Partner\FinanceController as PartnerFinanceController;
use App\Http\Controllers\Partner\OrderController as PartnerOrderController;
use App\Http\Controllers\Partner\ProfileController as PartnerProfileController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Tenant\BillController;
use App\Http\Controllers\Tenant\ComplaintController as TenantComplaintController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
use App\Http\Controllers\Tenant\WalletController as TenantWalletController;
use App\Http\Controllers\Tenant\WishlistController as TenantWishlistController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Pencarian & Filter Kos Nasional
Route::get('/cari', [SearchController::class, 'index'])->name('search');

// Peta Pencarian Nasional (Leaflet + Marker Cluster)
Route::get('/peta', [MapController::class, 'index'])->name('map');

// Paket Iklan & Promosi (Monetisasi BR-10, BR-15, Banner Komersial)
Route::get('/paket-iklan', [AdsPackageController::class, 'index'])->name('ads.packages.index');

// Layanan Kebutuhan Kos (Layanan Mitra)
Route::get('/layanan', [ServiceController::class, 'index'])->name('services.index');

// Marketplace Barang Kos (Jual Beli Sesama Anak Kos - Sistem COD)
Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
Route::get('/marketplace/{slug}', [MarketplaceController::class, 'show'])->name('marketplace.show');

// Pusat Bantuan & FAQ (Form bantuan & FAQ)
Route::get('/bantuan', function () {
    if (auth()->check() && auth()->user()->hasRole('penghuni')) {
        return view('tenant.help');
    }

    return view('bantuan');
})->name('help');

// Kebijakan Privasi & Syarat Ketentuan (Kepatuhan UU PDP No. 27/2022)
Route::get('/syarat-ketentuan', function () {
    return view('terms-and-conditions');
})->name('terms.conditions');

Route::get('/kebijakan-privasi', function () {
    return view('privacy-policy');
})->name('privacy.policy');

// Detail Kos Publik
Route::get('/kos/{slug}', [KostDetailController::class, 'show'])->name('kost.detail');

// Laporkan Pelanggaran Iklan Kos
Route::post('/kos/{kost}/lapor', [ReportController::class, 'store'])->name('kost.report');

// API Data Wilayah Dinamis
Route::get('/api/wilayah/provinces/{province}/regencies', [WilayahController::class, 'getRegenciesByProvince'])->name('api.wilayah.regencies');

// Public API Pembayaran (Status Polling & Xendit Webhook)
Route::get('/api/pembayaran/status/{referenceId}', [PaymentController::class, 'status'])->name('payments.status');
Route::post('/api/pembayaran/xendit/webhook', [PaymentController::class, 'webhook'])->name('payments.webhook');

// Rute Penghuni & Pemilik (Memerlukan Login)
// Pengalihan URL login lama panel ke halaman login terpadu
Route::redirect('/superadmin/login', '/login');
Route::redirect('/admin/login', '/login');

Route::middleware('auth')->group(function () {
    // Marketplace: Jual Barang Kos (Hanya Pengguna Login)
    Route::get('/marketplace/jual/barang', [MarketplaceController::class, 'create'])->name('marketplace.create');
    Route::post('/marketplace/jual/simpan', [MarketplaceController::class, 'store'])->name('marketplace.store');
    Route::get('/marketplace/{marketplaceItem}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
    Route::put('/marketplace/{marketplaceItem}', [MarketplaceController::class, 'update'])->name('marketplace.update');
    Route::delete('/marketplace/{marketplaceItem}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');

    // Pesan Paket Iklan, VIP & Banner via Xendit
    Route::post('/paket-iklan/pesan', [AdsPackageController::class, 'order'])->name('ads.packages.order');
    Route::post('/paket-iklan/vip', [AdsPackageController::class, 'orderVip'])->name('ads.vip.order');
    Route::post('/paket-iklan/banner', [AdsPackageController::class, 'orderBanner'])->name('ads.banner.order');

    // Xendit Checkout, Simulasi & Sukses (Auth)
    Route::get('/pembayaran/checkout/{referenceId}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/pembayaran/sukses/{referenceId}', [PaymentController::class, 'success'])->name('payments.success');
    Route::post('/pembayaran/simulasi/{referenceId}', [PaymentController::class, 'simulate'])->name('payments.simulate');
    Route::post('/pembayaran/upload-bukti/{referenceId}', [PaymentController::class, 'uploadProof'])->name('payments.upload-proof');

    // Portal Penghuni (Dashboard, Booking, Wishlist, Keluhan Fasilitas)
    Route::get('/penghuni/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/penghuni/cari', [SearchController::class, 'tenantIndex'])->name('tenant.search');
    Route::get('/penghuni/peta', [MapController::class, 'tenantIndex'])->name('tenant.map');
    Route::get('/penghuni/kos/{slug}', [KostDetailController::class, 'tenantShow'])->name('tenant.kost.detail');
    Route::get('/penghuni/booking-saya', [BookingController::class, 'myBookings'])->name('tenant.bookings.index');
    Route::get('/penghuni/booking/{booking}', [BookingController::class, 'show'])->name('tenant.bookings.show');
    Route::post('/penghuni/booking/{booking}/bayar', [BookingController::class, 'pay'])->name('tenant.bookings.pay');
    Route::post('/penghuni/booking/{booking}/bayar-dompet', [BookingController::class, 'payWithWallet'])->name('tenant.bookings.pay-wallet');
    Route::post('/penghuni/booking/{booking}/perpanjang', [BookingController::class, 'renew'])->name('tenant.bookings.renew');
    Route::post('/penghuni/booking/{booking}/perpanjang-dompet', [BookingController::class, 'renewWithWallet'])->name('tenant.bookings.renew-wallet');
    Route::get('/penghuni/pembayaran/{referenceId}', [BookingController::class, 'paymentCheckout'])->name('tenant.bookings.payment');
    Route::get('/penghuni/pembayaran/{referenceId}/sukses', [BookingController::class, 'paymentSuccess'])->name('tenant.bookings.payment-success');
    Route::post('/penghuni/pembayaran/{referenceId}/simulasi', [BookingController::class, 'paymentSimulate'])->name('tenant.bookings.simulate-payment');
    Route::post('/penghuni/pembayaran/{referenceId}/upload-bukti', [PaymentController::class, 'uploadProof'])->name('tenant.bookings.upload-proof');
    Route::post('/kos/{kost}/booking', [BookingController::class, 'store'])->name('tenant.bookings.store');
    Route::delete('/penghuni/booking/{booking}/batal', [BookingController::class, 'cancel'])->name('tenant.bookings.cancel');
    Route::post('/penghuni/booking/{booking}/akhiri-sewa', [BookingController::class, 'terminateLease'])->name('tenant.bookings.terminate');

    // Dompet Digital Penghuni (Top Up, Pembayaran & Tarik Saldo / Withdrawal)
    Route::get('/penghuni/dompet', [TenantWalletController::class, 'index'])->name('tenant.wallet.index');
    Route::post('/penghuni/dompet/topup', [TenantWalletController::class, 'topup'])->name('tenant.wallet.topup');
    Route::get('/penghuni/dompet/pembayaran/{referenceId}', [TenantWalletController::class, 'paymentCheckout'])->name('tenant.wallet.payment');
    Route::get('/penghuni/dompet/pembayaran/{referenceId}/sukses', [TenantWalletController::class, 'paymentSuccess'])->name('tenant.wallet.payment-success');
    Route::post('/penghuni/dompet/pembayaran/{referenceId}/simulasi', [TenantWalletController::class, 'paymentSimulate'])->name('tenant.wallet.simulate-payment');
    Route::post('/penghuni/dompet/pembayaran/{referenceId}/upload-bukti', [PaymentController::class, 'uploadProof'])->name('tenant.wallet.upload-proof');
    Route::post('/penghuni/dompet/tarik', [TenantWalletController::class, 'withdraw'])->name('tenant.wallet.withdraw');

    // Riwayat Tagihan & Pusat Pembayaran Penghuni
    Route::get('/penghuni/tagihan', [BillController::class, 'index'])->name('tenant.bills.index');
    Route::post('/penghuni/tagihan/{referenceId}/batal', [BillController::class, 'cancel'])->name('tenant.bills.cancel');

    // Riwayat Tagihan & Pusat Pembayaran Penghuni
    Route::get('/penghuni/tagihan', [BillController::class, 'index'])->name('tenant.bills.index');
    Route::post('/penghuni/tagihan/{referenceId}/batal', [BillController::class, 'cancel'])->name('tenant.bills.cancel');

    // Wishlist / Favorit Kos Penghuni
    Route::get('/penghuni/favorit', [TenantWishlistController::class, 'index'])->name('tenant.wishlists.index');
    Route::post('/penghuni/favorit/{kost}/toggle', [TenantWishlistController::class, 'toggle'])->name('tenant.wishlists.toggle');

    // Keluhan & Perbaikan Fasilitas Kamar
    Route::get('/penghuni/keluhan', [TenantComplaintController::class, 'index'])->name('tenant.complaints.index');
    Route::get('/penghuni/keluhan/buat', [TenantComplaintController::class, 'create'])->name('tenant.complaints.create');
    Route::post('/penghuni/keluhan', [TenantComplaintController::class, 'store'])->name('tenant.complaints.store');

    // Marketplace Barang Kos Khusus Penghuni
    Route::get('/penghuni/marketplace', [MarketplaceController::class, 'index'])->name('tenant.marketplace.index');
    Route::get('/penghuni/marketplace/barang-saya', [MarketplaceController::class, 'myItems'])->name('tenant.marketplace.my-items');
    Route::get('/penghuni/marketplace/jual', [MarketplaceController::class, 'create'])->name('tenant.marketplace.create');
    Route::post('/penghuni/marketplace/jual', [MarketplaceController::class, 'store'])->name('tenant.marketplace.store');
    Route::get('/penghuni/marketplace/{marketplaceItem}/edit', [MarketplaceController::class, 'edit'])->name('tenant.marketplace.edit');
    Route::put('/penghuni/marketplace/{marketplaceItem}', [MarketplaceController::class, 'update'])->name('tenant.marketplace.update');
    Route::delete('/penghuni/marketplace/{marketplaceItem}', [MarketplaceController::class, 'destroy'])->name('tenant.marketplace.destroy');
    Route::patch('/penghuni/marketplace/{marketplaceItem}/terjual', [MarketplaceController::class, 'markAsSold'])->name('tenant.marketplace.mark-sold');
    Route::get('/penghuni/marketplace/{slug}', [MarketplaceController::class, 'show'])->name('tenant.marketplace.show');

    // Pemesanan & Pembayaran Layanan Mitra Kamar Kos
    Route::get('/penghuni/layanan', [ServiceController::class, 'tenantIndex'])->name('tenant.services.index');
    Route::post('/layanan/pesan', [ServiceController::class, 'order'])->name('services.order');
    Route::get('/penghuni/layanan/pembayaran/{referenceId}', [ServiceController::class, 'paymentCheckout'])->name('tenant.services.payment');
    Route::get('/penghuni/layanan/pembayaran/{referenceId}/sukses', [ServiceController::class, 'paymentSuccess'])->name('tenant.services.payment-success');
    Route::get('/penghuni/layanan/pesanan/{referenceId}/lacak', [ServiceController::class, 'orderTracking'])->name('tenant.services.tracking');
    Route::patch('/penghuni/layanan/pesanan/{referenceId}/catatan', [ServiceController::class, 'updateNotes'])->name('tenant.services.update-notes');
    Route::patch('/penghuni/layanan/pesanan/{referenceId}/jadwalkan-ulang', [ServiceController::class, 'reschedule'])->name('tenant.services.reschedule');
    Route::post('/penghuni/layanan/pesanan/{referenceId}/batalkan-refund', [ServiceController::class, 'cancelRefund'])->name('tenant.services.cancel-refund');
    Route::post('/penghuni/layanan/pembayaran/{referenceId}/upload-bukti', [ServiceController::class, 'uploadProof'])->name('tenant.services.upload-proof');
    Route::post('/penghuni/layanan/pembayaran/{referenceId}/simulasi', [ServiceController::class, 'paymentSimulate'])->name('tenant.services.simulate-payment');

    // Pusat Bantuan & FAQ Khusus Penghuni
    Route::get('/penghuni/bantuan', function () {
        return view('tenant.help');
    })->name('tenant.help');

    // Portal Pemilik Kos (Owner Dashboard, Listing, Kamar, Penyewa, Promosi)
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/owner/promosi', [OwnerPromotionController::class, 'index'])->name('owner.promosi.index');

    // Kelola Iklan Pemilik
    Route::get('/iklan/kelola', [OwnerKostController::class, 'index'])->name('owner.kosts.index');
    Route::get('/iklan/buat', [OwnerKostController::class, 'create'])->name('owner.kosts.create');
    Route::post('/iklan', [OwnerKostController::class, 'store'])->name('owner.kosts.store');
    Route::get('/iklan/{kost}/edit', [OwnerKostController::class, 'edit'])->name('owner.kosts.edit');
    Route::put('/iklan/{kost}', [OwnerKostController::class, 'update'])->name('owner.kosts.update');
    Route::delete('/iklan/{kost}', [OwnerKostController::class, 'destroy'])->name('owner.kosts.destroy');

    // Kelola Kamar & Penyewa
    Route::get('/owner/kosts/{kost}/rooms', [OwnerRoomTenantController::class, 'rooms'])->name('owner.rooms.index');
    Route::post('/owner/kosts/{kost}/rooms', [OwnerRoomTenantController::class, 'storeRoom'])->name('owner.rooms.store');
    Route::put('/owner/kosts/{kost}/rooms/{room}', [OwnerRoomTenantController::class, 'updateRoom'])->name('owner.rooms.update');
    Route::delete('/owner/kosts/{kost}/rooms/{room}', [OwnerRoomTenantController::class, 'destroyRoom'])->name('owner.rooms.destroy');
    Route::get('/owner/penyewa', [OwnerRoomTenantController::class, 'tenants'])->name('owner.tenants.index');
    Route::patch('/owner/bookings/{booking}/status', [OwnerRoomTenantController::class, 'updateBookingStatus'])->name('owner.bookings.updateStatus');

    // Kelola Keluhan & Laporan
    Route::get('/owner/keluhan', [OwnerComplaintController::class, 'index'])->name('owner.complaints.index');
    Route::patch('/owner/keluhan/{report}/status', [OwnerComplaintController::class, 'updateStatus'])->name('owner.complaints.updateStatus');

    // Portal Mitra Layanan SevenKost (Order Fulfillment, Kurir, Keuangan & Profil)
    Route::prefix('mitra')->name('partner.')->middleware('kyc.verified')->group(function () {
        Route::get('/dashboard', [PartnerDashboardController::class, 'index'])->name('dashboard');

        Route::get('/pesanan', [PartnerOrderController::class, 'index'])->name('orders.index');
        Route::get('/pesanan/{referenceId}', [PartnerOrderController::class, 'show'])->name('orders.show');
        Route::post('/pesanan/{referenceId}/terima', [PartnerOrderController::class, 'accept'])->name('orders.accept');
        Route::post('/pesanan/{referenceId}/tugaskan-kurir', [PartnerOrderController::class, 'assignCourier'])->name('orders.assign-courier');
        Route::post('/pesanan/{referenceId}/mulai-kerja', [PartnerOrderController::class, 'startWork'])->name('orders.start-work');
        Route::post('/pesanan/{referenceId}/siap-diambil', [PartnerOrderController::class, 'markReady'])->name('orders.mark-ready');
        Route::post('/pesanan/{referenceId}/selesai', [PartnerOrderController::class, 'complete'])->name('orders.complete');
        Route::post('/pesanan/{referenceId}/tolak', [PartnerOrderController::class, 'reject'])->name('orders.reject');

        Route::get('/keuangan', [PartnerFinanceController::class, 'index'])->name('finances.index');
        Route::post('/keuangan/tarik-saldo', [PartnerFinanceController::class, 'withdraw'])->name('finances.withdraw');

        Route::get('/profil', [PartnerProfileController::class, 'index'])->name('profile.index');
        Route::put('/profil', [PartnerProfileController::class, 'update'])->name('profile.update');
        Route::patch('/profil/toggle-status', [PartnerProfileController::class, 'toggleStatus'])->name('profile.toggle-status');
    });
});

Route::get('/dashboard', function () {
    if (auth()->user()?->hasRole('superadmin')) {
        return redirect('/superadmin');
    }

    if (auth()->user()?->hasRole('admin')) {
        return redirect('/admin');
    }

    if (auth()->user()?->hasRole('pemilik')) {
        return redirect()->route('owner.dashboard');
    }

    if (auth()->user()?->hasRole('mitra')) {
        return redirect()->route('partner.dashboard');
    }

    return redirect()->route('tenant.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/kyc/verifikasi', [KycController::class, 'index'])->name('kyc.index');
    Route::post('/kyc/verifikasi', [KycController::class, 'store'])->name('kyc.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
