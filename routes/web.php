<?php

use App\Http\Controllers\AdsPackageController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KostDetailController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\Owner\KostController as OwnerKostController;
use App\Http\Controllers\Owner\RoomTenantController as OwnerRoomTenantController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SeoWilayahController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Pencarian & Filter Kos Nasional
Route::get('/cari', [SearchController::class, 'index'])->name('search');

// Peta Pencarian Nasional (Leaflet + Marker Cluster)
Route::get('/peta', [MapController::class, 'index'])->name('map');

// Paket Iklan Unggulan (Monetisasi BR-10, BR-15)
Route::get('/paket-iklan', [AdsPackageController::class, 'index'])->name('ads.packages.index');

// Sitemap XML untuk Search Engine Crawler (BR-19)
Route::get('/sitemap.xml', [SeoWilayahController::class, 'sitemap'])->name('sitemap');

// Halaman Landing SEO Regional per Wilayah (BR-19: /kos/{provinsi}/{kota})
Route::get('/kos/{provinsi}/{kota}', [SeoWilayahController::class, 'show'])->name('seo.wilayah');

// Detail Kos Publik
Route::get('/kos/{slug}', [KostDetailController::class, 'show'])->name('kost.detail');

// Laporkan Pelanggaran Iklan Kos
Route::post('/kos/{kost}/lapor', [ReportController::class, 'store'])->name('kost.report');

// API Data Wilayah Dinamis
Route::get('/api/wilayah/provinces/{province}/regencies', [WilayahController::class, 'getRegenciesByProvince'])->name('api.wilayah.regencies');

// Rute Penghuni & Pemilik (Memerlukan Login)
Route::middleware('auth')->group(function () {
    // Pesan Paket Iklan
    Route::post('/paket-iklan/pesan', [AdsPackageController::class, 'order'])->name('ads.packages.order');
    // Alur Booking Sewa (Minggu 3 Hari 1)
    Route::post('/kos/{kost}/booking', [BookingController::class, 'store'])->name('tenant.bookings.store');
    Route::get('/penghuni/booking-saya', [BookingController::class, 'myBookings'])->name('tenant.bookings.index');
    Route::delete('/penghuni/booking/{booking}/batal', [BookingController::class, 'cancel'])->name('tenant.bookings.cancel');

    // Kelola Iklan Pemilik
    Route::get('/iklan/kelola', [OwnerKostController::class, 'index'])->name('owner.kosts.index');
    Route::get('/iklan/buat', [OwnerKostController::class, 'create'])->name('owner.kosts.create');
    Route::post('/iklan', [OwnerKostController::class, 'store'])->name('owner.kosts.store');
    Route::get('/iklan/{kost}/edit', [OwnerKostController::class, 'edit'])->name('owner.kosts.edit');
    Route::put('/iklan/{kost}', [OwnerKostController::class, 'update'])->name('owner.kosts.update');
    Route::delete('/iklan/{kost}', [OwnerKostController::class, 'destroy'])->name('owner.kosts.destroy');

    // Kelola Kamar & Penyewa (Minggu 2 Hari 6)
    Route::get('/owner/kosts/{kost}/rooms', [OwnerRoomTenantController::class, 'rooms'])->name('owner.rooms.index');
    Route::post('/owner/kosts/{kost}/rooms', [OwnerRoomTenantController::class, 'storeRoom'])->name('owner.rooms.store');
    Route::delete('/owner/kosts/{kost}/rooms/{room}', [OwnerRoomTenantController::class, 'destroyRoom'])->name('owner.rooms.destroy');
    Route::get('/owner/penyewa', [OwnerRoomTenantController::class, 'tenants'])->name('owner.tenants.index');
    Route::patch('/owner/bookings/{booking}/status', [OwnerRoomTenantController::class, 'updateBookingStatus'])->name('owner.bookings.updateStatus');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
