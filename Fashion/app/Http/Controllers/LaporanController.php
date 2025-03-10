<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
use App\Models\Kategori;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function laporanBarang(Request $request)
    {
        $kategori_id = $request->input('kategori'); 
        $kategoriList = Kategori::all(); 

        $query = Produk::with(['kategori', 'pemasok']);
        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }

        $produk = $query->get();
        return view('laporan.laporan_barang', compact('produk', 'kategoriList', 'kategori_id'));
    }


    public function cetakLaporan(Request $request)
    {
        $kategori_id = $request->input('kategori');

        $query = Produk::with(['kategori', 'pemasok']);

        if ($kategori_id) {
            $query->where('kategori_id', $kategori_id);
        }

        $produk = $query->get();
        $pdf = Pdf::loadView('laporan.cetak_barang', compact('produk'))->setPaper('A4', 'landscape');

        return $pdf->stream('laporan-barang.pdf');
    }

}
