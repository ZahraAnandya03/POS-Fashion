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
use App\Http\Controllers\KasirController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PengajuanBarangController;
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
                return redirect()->route('kasir.index');
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
    Route::group(['middleware' => ['role:admin,kasir']], function () {

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
    
    //pembelian
    Route::resource('pembelian', PembelianController::class);
    Route::get('/get-produk-by-pemasok', [PembelianController::class, 'getProdukByPemasok'])->name('getProdukByPemasok');

    //laporan
    Route::get('/laporan/barang', [LaporanController::class, 'laporanBarang'])->name('laporan.laporan_barang');
    Route::get('/laporan/barang/cetak', [LaporanController::class, 'cetakLaporan'])->name('laporan.barang.cetak');
    Route::get('/laporan/keuntungan', [LaporanController::class, 'laporanKeuntungan'])->name('laporan.keuntungan');
    Route::get('/laporan/keuntungan/cetak', [LaporanController::class, 'cetakLaporanKeuntungan'])->name('laporan.keuntungan.cetak');
    //penjualan
    Route::resource('penjualan', PenjualanController::class);   
    Route::get('/cetak', [PenjualanController::class, 'exportPdf'])->name('penjualan.pdf');

    //pengajuan barang
    Route::get('pengajuan', [PengajuanBarangController::class, 'index'])->name('pengajuan.index');
    Route::get('pengajuan/create', [PengajuanBarangController::class, 'create'])->name('pengajuan.create');
    Route::post('pengajuan', [PengajuanBarangController::class, 'store'])->name('pengajuan.store');
    Route::get('pengajuan/{id}/edit', [PengajuanBarangController::class, 'edit'])->name('pengajuan.edit');
    Route::post('pengajuan/{pengajuan}', [PengajuanBarangController::class, 'update'])->name('pengajuan.update');
    Route::delete('pengajuan/{pengajuan}', [PengajuanBarangController::class, 'destroy'])->name('pengajuan.destroy');

    // Tambahan fitur toggle status
    Route::post('/pengajuan/toggle-terpenuhi/{pengajuan}', [PengajuanBarangController::class, 'toggleTerpenuhi']);

    // Fitur export
    Route::get('pengajuan-export-excel', [PengajuanBarangController::class, 'exportExcel'])->name('pengajuan.exportExcel');
    Route::get('/pengajuan/export-pdf', [PengajuanBarangController::class, 'exportPdf'])->name('pengajuan.exportPdf');

});

    //role kasir
    Route::group(['middleware' => ['role:kasir']], function () {

    //kasir
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir', [KasirController::class, 'store'])->name('kasir.store');  // untuk simpan penjualan
    Route::get('/kasir/pembayaran/{id}', [KasirController::class, 'pembayaran'])->name('kasir.pembayaran');
    Route::post('/kasir/pembayaran/{id}', [KasirController::class, 'prosesBayar'])->name('kasir.prosesBayar');
    Route::get('/kasir/cetak-nota/{id}', [KasirController::class, 'cetakNota'])->name('kasir.cetakNota');

    });
    
    

  



