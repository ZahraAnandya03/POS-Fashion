<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Pemasok;
use App\Models\Penjualan;

class DashboardController extends Controller
{
    public function index()
{
    // Ambil data penjualan per hari yang ada transaksi
    $penjualanHarian = Penjualan::select(
        DB::raw("DATE(tgl_faktur) as tanggal"),
        DB::raw("SUM(total_bayar) as total")
    )
    ->groupBy('tanggal')
    ->orderBy('tanggal')
    ->get();

    // Inisialisasi array lengkap untuk semua hari dalam bulan berjalan
    $periode = [];
    $totalTransaksi = [];
    $startDate = now()->startOfMonth();
    $endDate = now()->endOfMonth();

    while ($startDate <= $endDate) {
        $periode[$startDate->format('d M Y')] = 0;
        $totalTransaksi[$startDate->format('d M Y')] = 0;
        $startDate->addDay();
    }

    // Isi array dengan data dari database
    foreach ($penjualanHarian as $record) {
        $tanggalFormat = date('d M Y', strtotime($record->tanggal));
        $periode[$tanggalFormat] = $record->total;
        $totalTransaksi[$tanggalFormat] = $record->count();
    }

    // dd($totalTransaksi);

    // Konversi ke array untuk JavaScript
    $hariPenjualan = array_keys($periode);
    $jumlahPenjualanHarian = array_values($periode);
    $totalTransaksis = array_values($totalTransaksi);

    return view('dashboard', [
        'jumlahProduk'           => Produk::count(),
        'jumlahPenjualan'        => Penjualan::count(),
        'jumlahPelanggan'        => Pelanggan::count(),
        'jumlahPemasok'          => Pemasok::count(),
        'hariPenjualan'         => $hariPenjualan,
        'jumlahPenjualanHarian' => $jumlahPenjualanHarian,
        'totalTransaksi' => $totalTransaksis,
        ]);
    }

} 