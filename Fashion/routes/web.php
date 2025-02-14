<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PelangganController;
use App\Models\Pelanggan;

// Rute default diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rute login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Middleware untuk memastikan hanya user yang login bisa mengakses dashboard
Route::middleware('auth')->group(function () {
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Rute khusus admin
Route::middleware('admin')->group(function () {
Route::get('/admin/dashboard', [AdminController::class, 'index']);
});
});

//kategori
Route::resource('kategori', KategoriController::class);

//produk
Route::resource('produk', ProdukController::class);

//pemasok
Route::resource('pemasok', PemasokController::class);

//peelanggan
Route::resource('pelanggan', PelangganController::class);
