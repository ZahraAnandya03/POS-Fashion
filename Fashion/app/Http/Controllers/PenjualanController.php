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
    public function index(Request $request)
    {
        $query = Penjualan::with(['detail.produk', 'pelanggan']);

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = $request->tanggal_awal;
            $tanggalAkhir = $request->tanggal_akhir;
            $query->whereBetween('tgl_faktur', [$tanggalAwal, $tanggalAkhir]);
        }

        // Ambil data penjualan
        $penjualan = $query->get();

        // Data pendukung untuk modal create
        $pelanggan = Pelanggan::all();
        $produk    = Produk::all();

        // Ambil size unik dari produk yang tersedia
        $availableSizes = Produk::whereNotNull('size')
                                ->distinct()
                                ->pluck('size')
                                ->filter()
                                ->values();

        return view('penjualan.index', compact('penjualan', 'pelanggan', 'produk', 'availableSizes'));
    }

    public function cetakPdf(Request $request)
    {
        $query = Penjualan::query();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $query->whereBetween('tgl_faktur', [$request->tanggal_awal, $request->tanggal_akhir]);
        }

        $penjualan = $query->get();

        $pdf = Pdf::loadView('penjualan.laporan_pdf', compact('penjualan'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_penjualan.pdf');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'tgl_faktur'   => 'required|date',
            'total_bayar'  => 'required|numeric|min:0',
            'dibayar'      => 'nullable|numeric|min:0',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'size'         => 'required|string|max:50',

            'produk_id'    => 'required|array',
            'produk_id.*'  => 'required|exists:produk,id',
            'harga_jual'   => 'required|array',
            'harga_jual.*' => 'required|numeric|min:0',
            'jumlah'       => 'required|array',
            'jumlah.*'     => 'required|integer|min:1',
        ]);

        try {
            $penjualan = DB::transaction(function () use ($request) {
                $tanggalFaktur = $request->tgl_faktur ?? date('Y-m-d');
                $todayString = date('Ymd', strtotime($tanggalFaktur));
                $countToday = Penjualan::whereDate('tgl_faktur', $tanggalFaktur)->count() + 1;
                $no_faktur = 'INV-' . $todayString . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

                $dibayar = $request->dibayar ?? 0;
                // Jika dibayar belum cukup, kembali = 0
                // Jika dibayar >= total_bayar, hitung kembalian
                $kembali = ($dibayar >= $request->total_bayar)
                    ? $dibayar - $request->total_bayar
                    : 0;

                // Simpan data penjualan
                $penjualan = Penjualan::create([
                    'no_faktur'    => $no_faktur,
                    'tgl_faktur'   => $tanggalFaktur,
                    'total_bayar'  => $request->total_bayar,
                    'dibayar'      => $dibayar,
                    'kembali'      => $kembali,
                    'pelanggan_id' => $request->pelanggan_id,
                    'size'         => $request->size,
                ]);

                // Simpan detail penjualan dan update stok produk
                foreach ($request->produk_id as $key => $produk_id) {
                    $harga_jual = $request->harga_jual[$key];
                    $jumlah     = $request->jumlah[$key];
                    $sub_total  = $harga_jual * $jumlah;

                    $produk = Produk::findOrFail($produk_id);
                    if ($produk->stok < $jumlah) {
                        throw new \Exception(
                            "Stok produk {$produk->nama} tidak mencukupi. Tersedia: {$produk->stok}"
                        );
                    }

                    // Simpan detail
                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'produk_id'    => $produk_id,
                        'harga_jual'   => $harga_jual,
                        'jumlah'       => $jumlah,
                        'sub_total'    => $sub_total,
                    ]);

                    // Kurangi stok produk
                    $produk->stok -= $jumlah;
                    $produk->save();
                }

                return $penjualan;
            });

            // Redirect ke halaman pembayaran/nota
            return redirect()
                ->route('kasir.pembayaran', ['id' => $penjualan->id])
                ->with('success', 'Transaksi berhasil disimpan. Lanjutkan pembayaran.');
        } catch (\Exception $e) {
            // Batalkan transaksi dan tampilkan pesan error
            return redirect()->back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Update data penjualan (misal via modal edit).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'tgl_faktur'   => 'required|date',
            'total_bayar'  => 'required|numeric',
            'dibayar'      => 'nullable|numeric',
            'pelanggan_id' => 'required|exists:pelanggan,id',
            'size'         => 'nullable|string|max:50',
        ]);

        $penjualan = Penjualan::findOrFail($id);

        $penjualan->pelanggan_id = $request->pelanggan_id;
        $penjualan->tgl_faktur   = $request->tgl_faktur;
        $penjualan->total_bayar  = $request->total_bayar;
        $penjualan->dibayar      = $request->dibayar;
        $penjualan->size         = $request->size;
        $penjualan->kembali      = ($request->dibayar ?? 0) - $request->total_bayar;
        $penjualan->save();

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Data penjualan berhasil diupdate');
    }

    /**
     * Hapus penjualan dan detailnya.
     */
    public function destroy($id)
    {
        DB::transaction(function() use ($id) {
            DetailPenjualan::where('penjualan_id', $id)->delete();
            Penjualan::destroy($id);
        });

        return redirect()
            ->route('penjualan.index')
            ->with('success', 'Penjualan berhasil dihapus.');
    }

    public function show($id)
    {
        $penjualan = Penjualan::with(['detail.produk', 'pelanggan'])->findOrFail($id);
        return response()->json($penjualan);
    }

}
