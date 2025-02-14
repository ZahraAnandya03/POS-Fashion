<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Pemasok;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'jumlahProduk' => Produk::count(),
            // 'jumlahTransaksi' => Transaksi::count(),
            'jumlahPelanggan' => Pelanggan::count(),
            'jumlahPemasok' => Pemasok::count(),
        ]);
    }
}

