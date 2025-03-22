<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Kategori;
use App\Models\Pelanggan;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan barang berdasarkan kategori.
     */
    public function laporanBarang(Request $request)
    {
        // Mengambil kategori yang dipilih dari request
        $kategori_id = $request->input('kategori'); 

        // Mengambil daftar semua kategori
        $kategoriList = Kategori::all(); 

        // Query untuk mengambil data produk beserta relasi kategori dan pemasok
        $query = Produk::with(['kategori', 'pemasok']);

        // Jika kategori dipilih, filter produk berdasarkan kategori
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }

        // Mengambil data produk yang sudah difilter
        $produk = $query->get();

        // Menampilkan halaman laporan barang dengan data yang telah diproses
        return view('laporan.laporan_barang', compact('produk', 'kategoriList', 'kategori_id'));
    }

    /**
     * Mencetak laporan barang berdasarkan kategori dalam format PDF.
     */
    public function cetakLaporan(Request $request)
    {
        // Mengambil kategori yang dipilih dari request
        $kategori_id = $request->input('kategori');

        // Query untuk mengambil data produk beserta relasi kategori dan pemasok
        $query = Produk::with(['kategori', 'pemasok']);

        // Jika kategori dipilih, filter produk berdasarkan kategori
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }

        // Mengambil data produk yang sudah difilter
        $produk = $query->get();

        // Membuat file PDF berdasarkan tampilan laporan barang
        $pdf = Pdf::loadView('laporan.cetak_barang', compact('produk'))->setPaper('A4', 'landscape');

        // Menampilkan file PDF di browser
        return $pdf->stream('laporan-barang.pdf');
    }

    /**
     * Menampilkan laporan keuntungan berdasarkan kategori.
     */
    public function laporanKeuntungan(Request $request)
    {   
        // Mengambil kategori yang dipilih dari request
        $kategori_id = $request->kategori;

        // Query untuk menghitung keuntungan berdasarkan jumlah produk terjual
        $produk = Produk::leftJoin('detail_penjualan', 'produk.id', '=', 'detail_penjualan.produk_id')
            ->select(
                'produk.id',
                'produk.nama',
                'produk.stok as stok_awal',
                'produk.harga_beli',
                'produk.harga as harga_jual',
                DB::raw('COALESCE(SUM(detail_penjualan.jumlah), 0) as terjual'),
                DB::raw('COALESCE(SUM(detail_penjualan.jumlah), 0) * (produk.harga - produk.harga_beli) as keuntungan')
            )
            ->when($kategori_id, function ($query) use ($kategori_id) {
                return $query->where('produk.kategori_id', $kategori_id);
            })
            ->groupBy('produk.id', 'produk.nama', 'produk.stok', 'produk.harga', 'produk.harga_beli')
            ->get();

        // Mengambil daftar kategori untuk filter
        $kategoriList = Kategori::all();

        // Menampilkan halaman laporan keuntungan dengan data yang telah diproses
        return view('laporan.keuntungan', compact('produk', 'kategoriList', 'kategori_id'));
    }

    /**
     * Mencetak laporan keuntungan berdasarkan kategori dalam format PDF.
     */
    public function cetakLaporanKeuntungan(Request $request)
    {
        // Mengambil kategori yang dipilih dari request
        $kategori_id = $request->input('kategori');

        // Query untuk menghitung keuntungan berdasarkan jumlah produk terjual
        $produk = Produk::leftJoin('detail_penjualan', 'produk.id', '=', 'detail_penjualan.produk_id')
            ->select(
                'produk.id',
                'produk.nama',
                'produk.stok as stok_awal',
                'produk.harga',
                DB::raw('COALESCE(SUM(detail_penjualan.jumlah), 0) as terjual'),
                DB::raw('(COALESCE(SUM(detail_penjualan.jumlah), 0) * produk.harga) as keuntungan')
            )
            ->when($kategori_id, function ($query) use ($kategori_id) {
                return $query->where('produk.kategori_id', $kategori_id);
            })
            ->groupBy('produk.id', 'produk.nama', 'produk.stok', 'produk.harga')
            ->get();

        // Membuat file PDF berdasarkan tampilan laporan keuntungan
        $pdf = Pdf::loadView('laporan.cetak_keuntungan', compact('produk'))->setPaper('A4', 'landscape');

        // Menampilkan file PDF di browser
        return $pdf->stream('laporan-keuntungan.pdf');
    }
}
