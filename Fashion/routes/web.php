<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PemasokController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Auth;

// Rute default diarahkan ke login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    if (Auth::check()) {

        switch (Auth::user()->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'kasir':
                return redirect()->route('kasir.dashboard');
            default:
                return view('admin.dashboard');
        }
    }
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

    // Rute login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    //role admin
    Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])-> name('admin.dashboard');
    
    Route::resource('user', UserController::class);
    
    //kategori
    Route::resource('kategori', KategoriController::class);
    
    //produk
    Route::resource('produk', ProdukController::class);
    
    //pemasok
    Route::resource('pemasok', PemasokController::class);
    
    //peelanggan
    Route::resource('pelanggan', PelangganController::class);

    //penjualan
    Route::resource('penjualan', PenjualanController::class)->middleware('auth');
    // Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

    //kasir
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');  // untuk simpan penjualan
    Route::get('/kasir/pembayaran/{id}', [KasirController::class, 'pembayaran'])->name('kasir.pembayaran');
    Route::post('/kasir/pembayaran/{id}', [KasirController::class, 'prosesBayar'])->name('kasir.prosesBayar');
    Route::get('/kasir/cetak-nota/{id}', [KasirController::class, 'cetakNota'])->name('kasir.cetakNota');

    //laporan
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.laporan_barang');
    Route::get('/laporan/barang/cetak', [LaporanController::class, 'cetakLaporan'])->name('laporan.barang.cetak');
    Route::get('/penjualan/laporan-pdf', [PenjualanController::class, 'cetakPdf'])->name('penjualan.laporan_pdf');
    });

    //transaksi
    Route::resource('transaksi', TransaksiController::class);


    //role kasir
    Route::middleware(['auth', 'role:kasir'])->group(function () {
    Route::get('/kasir/dashboard', [DashboardController::class, 'index'])-> name('kasir.dashboard');
    });


