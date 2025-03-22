<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\DetailPenjualan;
use Barryvdh\DomPDF\Facade\Pdf;

class PenjualanController extends Controller
{
    /**
     * Menampilkan daftar transaksi penjualan.
     * Dapat difilter berdasarkan rentang tanggal.
     */
    public function index(Request $request)
    {
        // Query dasar untuk mengambil data penjualan beserta detail produk dan pelanggan
        $query = Penjualan::with(['detail.produk', 'pelanggan']);

        // Filter berdasarkan rentang tanggal jika input diberikan
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $query->whereBetween('tgl_faktur', [$tanggalAwal, $tanggalAkhir]);
        }

        // Mengambil data penjualan yang telah difilter dan mengurutkan berdasarkan tanggal terbaru
        $penjualan = $query->orderBy('tgl_faktur', 'desc')->get();

        // Mengambil daftar pelanggan dan produk untuk keperluan form
        $pelanggan = Pelanggan::all();
        $produk    = Produk::all();

        // Mengambil ukuran unik dari produk yang tersedia
        $availableSizes = Produk::whereNotNull('size')
                                ->distinct()
                                ->pluck('size')
                                ->filter()
                                ->values();

        // Menampilkan halaman daftar penjualan
        return view('penjualan.index', compact('penjualan', 'pelanggan', 'produk', 'availableSizes'));
    }

    /**
     * Mengekspor laporan penjualan ke dalam format PDF.
     * Dapat difilter berdasarkan rentang tanggal.
     */
    public function exportPdf(Request $request)
    {
        // Mengambil input rentang tanggal dari request
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');

        // Query dasar untuk mengambil data penjualan
        $query = Penjualan::query();

        // Jika rentang tanggal diisi, filter berdasarkan rentang tersebut
        if ($tanggal_awal && $tanggal_akhir) {
            $query->whereBetween('tgl_faktur', [$tanggal_awal, $tanggal_akhir]);
        }

        // Mengambil data penjualan yang telah difilter dan diurutkan berdasarkan tanggal terbaru
        $penjualan = $query->orderBy('tgl_faktur', 'desc')->get();

        // Jika tidak ada data, munculkan pesan error dan kembali ke halaman sebelumnya
        if ($penjualan->isEmpty()) {
            return back()->with('error', 'Tidak ada data penjualan dalam rentang tanggal yang dipilih.');
        }

        // Membuat file PDF berdasarkan data yang diambil
        $pdf = Pdf::loadView('penjualan.pdf', compact('penjualan', 'tanggal_awal', 'tanggal_akhir'))
            ->setPaper('a4', 'landscape');

        // Menampilkan file PDF di browser
        return $pdf->stream('laporan-penjualan.pdf');
    }

    /**
     * Menampilkan detail transaksi penjualan berdasarkan ID.
     */
    public function show($id)
    {
        // Mengambil data penjualan berdasarkan ID beserta relasi detail produk dan pelanggan
        $penjualan = Penjualan::with(['detail.produk', 'pelanggan'])->findOrFail($id);

        // Mengembalikan data dalam format JSON untuk ditampilkan di frontend
        return response()->json($penjualan);
    }
}
