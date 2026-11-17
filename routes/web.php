<?php

use App\Http\Controllers\AdsPackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KostDetailController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\Owner\ComplaintController as OwnerComplaintController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\KostController as OwnerKostController;
use App\Http\Controllers\Owner\PromotionController as OwnerPromotionController;
use App\Http\Controllers\Owner\RoomTenantController as OwnerRoomTenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\Tenant\ComplaintController as TenantComplaintController;
use App\Http\Controllers\Tenant\DashboardController as TenantDashboardController;
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

    // Pesan Paket Iklan, VIP & Banner via Xendit
    Route::post('/paket-iklan/pesan', [AdsPackageController::class, 'order'])->name('ads.packages.order');
    Route::post('/paket-iklan/vip', [AdsPackageController::class, 'orderVip'])->name('ads.vip.order');
    Route::post('/paket-iklan/banner', [AdsPackageController::class, 'orderBanner'])->name('ads.banner.order');

    // Xendit Checkout, Simulasi & Sukses (Auth)
    Route::get('/pembayaran/checkout/{referenceId}', [PaymentController::class, 'checkout'])->name('payments.checkout');
    Route::get('/pembayaran/sukses/{referenceId}', [PaymentController::class, 'success'])->name('payments.success');
    Route::post('/pembayaran/simulasi/{referenceId}', [PaymentController::class, 'simulate'])->name('payments.simulate');

    // Portal Penghuni (Dashboard, Booking, Wishlist, Keluhan Fasilitas)
    Route::get('/penghuni/dashboard', [TenantDashboardController::class, 'index'])->name('tenant.dashboard');
    Route::get('/penghuni/booking-saya', [BookingController::class, 'myBookings'])->name('tenant.bookings.index');
    Route::post('/kos/{kost}/booking', [BookingController::class, 'store'])->name('tenant.bookings.store');
    Route::delete('/penghuni/booking/{booking}/batal', [BookingController::class, 'cancel'])->name('tenant.bookings.cancel');

    // Wishlist / Favorit Kos Penghuni
    Route::get('/penghuni/favorit', [TenantWishlistController::class, 'index'])->name('tenant.wishlists.index');
    Route::post('/penghuni/favorit/{kost}/toggle', [TenantWishlistController::class, 'toggle'])->name('tenant.wishlists.toggle');

    // Keluhan & Perbaikan Fasilitas Kamar
    Route::get('/penghuni/keluhan', [TenantComplaintController::class, 'index'])->name('tenant.complaints.index');
    Route::get('/penghuni/keluhan/buat', [TenantComplaintController::class, 'create'])->name('tenant.complaints.create');
    Route::post('/penghuni/keluhan', [TenantComplaintController::class, 'store'])->name('tenant.complaints.store');

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

    return redirect()->route('tenant.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
