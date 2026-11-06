<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\KostDetailController;
use App\Http\Controllers\Owner\KostController as OwnerKostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Pencarian & Filter Kos Nasional
Route::get('/cari', [SearchController::class, 'index'])->name('search');

// Detail Kos Publik
Route::get('/kos/{slug}', [KostDetailController::class, 'show'])->name('kost.detail');

// API Data Wilayah Dinamis
Route::get('/api/wilayah/provinces/{province}/regencies', [WilayahController::class, 'getRegenciesByProvince'])->name('api.wilayah.regencies');

// Rute Pemilik Kos (Memerlukan Login)
Route::middleware('auth')->group(function () {
    Route::get('/iklan/kelola', [OwnerKostController::class, 'index'])->name('owner.kosts.index');
    Route::get('/iklan/buat', [OwnerKostController::class, 'create'])->name('owner.kosts.create');
    Route::post('/iklan', [OwnerKostController::class, 'store'])->name('owner.kosts.store');
    Route::get('/iklan/{kost}/edit', [OwnerKostController::class, 'edit'])->name('owner.kosts.edit');
    Route::put('/iklan/{kost}', [OwnerKostController::class, 'update'])->name('owner.kosts.update');
    Route::delete('/iklan/{kost}', [OwnerKostController::class, 'destroy'])->name('owner.kosts.destroy');
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
