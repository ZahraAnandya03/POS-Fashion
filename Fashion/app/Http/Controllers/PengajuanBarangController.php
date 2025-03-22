<?php

namespace App\Http\Controllers;

use App\Exports\PengajuanBarangExport;
use App\Models\PengajuanBarang;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class PengajuanBarangController extends Controller
{

    public function index()
    {
        $pengajuan = PengajuanBarang::with('pelanggan')->latest()->get();
        $pelanggan = Pelanggan::all(); 
        return view('pengajuan.index', compact('pengajuan', 'pelanggan')); 
    }

    // Menyimpan data pengajuan barang baru ke database
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'nama_barang' => 'required|string|max:255',
            'tanggal_pengajuan' => 'nullable|date',
            'qty' => 'required|integer|min:1',
        ]);

        // Mengambil nama pelanggan berdasarkan pelanggan_id
        $pelanggan = Pelanggan::find($request->pelanggan_id);
        $namaPengaju = $pelanggan ? $pelanggan->nama : 'Tidak Diketahui';

        // Menyimpan data ke tabel pengajuan_barang
        PengajuanBarang::create([
            'pelanggan_id' => $request->pelanggan_id,
            'nama_pengaju' => $namaPengaju,
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan ?? now()->toDateString(), // Gunakan tanggal sekarang jika tidak ada input
            'qty' => $request->qty,
            'status' => 'disetujui', // Status default adalah "disetujui"
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', 'Data berhasil ditambahkan.');
    }

    // Mengupdate data pengajuan barang yang sudah ada
    public function update(Request $request, PengajuanBarang $pengajuan)
    {
        // Validasi data input
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'nama_barang' => 'required|string|max:255',
            'tanggal_pengajuan' => 'required|date',
            'qty' => 'required|integer|min:1',
            'status' => 'nullable|in:disetujui,ditolak', // Status hanya bisa disetujui atau ditolak
        ]);

        // Jika pelanggan_id diubah, ambil nama pelanggan baru
        $namaPengaju = $pengajuan->nama_pengaju;
        if ($request->pelanggan_id && $request->pelanggan_id != $pengajuan->pelanggan_id) {
            $pelanggan = Pelanggan::find($request->pelanggan_id);
            $namaPengaju = $pelanggan ? $pelanggan->nama : 'Tidak Diketahui';
        }

        // Update data pengajuan barang
        $pengajuan->update([
            'pelanggan_id' => $request->pelanggan_id,
            'nama_pengaju' => $namaPengaju,
            'nama_barang' => $request->nama_barang,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'qty' => $request->qty,
            'status' => $request->status,
        ]);

        // Redirect ke halaman index dengan pesan sukses
        return redirect()->route('pengajuan.index')->with('success', 'Data berhasil diperbarui.');
    }

    // Menghapus data pengajuan barang
    public function destroy(PengajuanBarang $pengajuan)
    {
        // Hapus data dari database
        $pengajuan->delete();
        
        // Berikan respons JSON sebagai feedback ke front-end
        return response()->json(['success' => true, 'message' => 'Data berhasil dihapus.']);
    }

    // Mengubah status "terpenuhi" (toggle antara 0 dan 1)
    public function toggleTerpenuhi(Request $request, PengajuanBarang $pengajuan)
    {
        if (!$pengajuan) {
            // Berikan respons error jika data tidak ditemukan
            return response()->json(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
        }
    
        // Toggle status "terpenuhi" (jika 1 menjadi 0, jika 0 menjadi 1)
        $pengajuan->update([
            'terpenuhi' => !$pengajuan->terpenuhi
        ]);
    
        // Berikan respons JSON dengan status terbaru
        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui.',
            'terpenuhi' => $pengajuan->terpenuhi
        ]);
    }

    // Mengekspor data pengajuan barang ke format Excel
    public function exportExcel()
    {
        return Excel::download(new PengajuanBarangExport, 'pengajuan-barang.xlsx');
    }

    // Mengekspor data pengajuan barang ke format PDF
    public function exportPdf()
    {
        // Ambil semua data pengajuan untuk ditampilkan di PDF
        $pengajuan = PengajuanBarang::all();
        $pdf = Pdf::loadView('pengajuan.export_pdf', compact('pengajuan'));
        return $pdf->stream('pengajuan.pdf');
    }
}
