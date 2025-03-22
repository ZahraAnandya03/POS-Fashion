<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Produk;
use App\Models\Pemasok;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        // Query dasar
        $query = Pembelian::with('pemasok', 'detail.produk')->latest();

        // Filter berdasarkan tanggal jika diisi
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $query->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir]);
        }

        // Ambil data pembelian yang sudah difilter
        $pembelian = $query->get();

        // Data lainnya
        $pemasok = Pemasok::all();
        $produk = Produk::all();

        return view('pembelian.index', compact('pembelian', 'pemasok', 'produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pemasok_id' => 'required|exists:pemasok,id',
            'tanggal' => 'required|date',
            'produk_id' => 'required|array',
            'produk_id.*' => 'exists:produk,id',
            'harga_beli' => 'required|array',
            'harga_beli.*' => 'numeric|min:1',
            'harga_jual' => 'required|array',
            'harga_jual.*' => 'numeric|min:1',
            'jumlah' => 'required|array',
            'jumlah.*' => 'integer|min:1'
        ]);

        DB::transaction(function () use ($request) {
            $pembelian = Pembelian::create([
                'pemasok_id' => $request->pemasok_id,
                'tanggal' => $request->tanggal,
                'total_harga' => 0
            ]);

            $totalHarga = 0;

            foreach ($request->produk_id as $index => $produkId) {
                $jumlah = $request->jumlah[$index];
                $hargaBeli = $request->harga_beli[$index];
                $hargaJual = $request->harga_jual[$index];
                $subtotal = $hargaBeli * $jumlah;

                DetailPembelian::create([
                    'pembelian_id' => $pembelian->id,
                    'produk_id' => $produkId,
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'subtotal' => $subtotal
                ]);

                // Update stok dan harga jual di produk
                $produk = Produk::find($produkId);
                if ($produk) {
                    $produk->increment('stok', $jumlah);

                    if ($hargaJual > $produk->harga_jual) {
                        $produk->update(['harga_jual' => $hargaJual]);
                    }
                }

                $totalHarga += $subtotal;
            }

            // Update total harga pembelian
            $pembelian->update(['total_harga' => $totalHarga]);
        });

        return redirect()->route('pembelian.index')->with('success', 'Transaksi pembelian berhasil!');
    }
}
