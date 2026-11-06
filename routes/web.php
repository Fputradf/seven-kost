<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// Halaman Beranda (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// API Data Wilayah Dinamis
Route::get('/api/wilayah/provinces/{province}/regencies', [WilayahController::class, 'getRegenciesByProvince'])->name('api.wilayah.regencies');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
