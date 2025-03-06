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
        // Ambil data penjualan per bulan yang ada transaksi
        $penjualanBulanan = Penjualan::select(
                DB::raw("DATE_FORMAT(tgl_faktur, '%M') as bulan"),
                DB::raw("SUM(total_bayar) as total")
            )
            ->groupBy('bulan')
            ->orderBy(DB::raw("MIN(tgl_faktur)"))
            ->get();

        // Mapping nama bulan dari bahasa Inggris ke bahasa Indonesia
        $mappingBulan = [
            'January'   => 'Januari',
            'February'  => 'Februari',
            'March'     => 'Maret',
            'April'     => 'April',
            'May'       => 'Mei',
            'June'      => 'Juni',
            'July'      => 'Juli',
            'August'    => 'Agustus',
            'September' => 'September',
            'October'   => 'Oktober',
            'November'  => 'November',
            'December'  => 'Desember',
        ];

        // Array lengkap untuk semua bulan (dalam bahasa Indonesia)
        $allBulan = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        // Ubah data query sehingga key-nya adalah nama bulan dalam bahasa Indonesia
        $dataPenjualan = [];
        foreach ($penjualanBulanan as $record) {
            $bulanInggris = $record->bulan;
            $bulanIndonesia = isset($mappingBulan[$bulanInggris]) ? $mappingBulan[$bulanInggris] : $bulanInggris;
            $dataPenjualan[$bulanIndonesia] = $record->total;
        }

        // Inisialisasi array untuk grafik
        $bulanPenjualan = [];
        $jumlahPenjualanBulanan = [];
        foreach ($allBulan as $bulan) {
            $bulanPenjualan[] = $bulan;
            $jumlahPenjualanBulanan[] = isset($dataPenjualan[$bulan]) ? $dataPenjualan[$bulan] : 0;
        }

        return view('dashboard', [
            'jumlahProduk'           => Produk::count(),
            'jumlahPenjualan'        => Penjualan::count(),
            'jumlahPelanggan'        => Pelanggan::count(),
            'jumlahPemasok'          => Pemasok::count(),
            'bulanPenjualan'         => $bulanPenjualan,
            'jumlahPenjualanBulanan' => $jumlahPenjualanBulanan,
        ]);
    }
}
