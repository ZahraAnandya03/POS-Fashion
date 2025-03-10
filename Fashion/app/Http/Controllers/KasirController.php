<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Penjualan;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\DetailPenjualan;

class KasirController extends Controller
{
    /**
     * Menampilkan halaman kasir (form transaksi).
     */
    public function index()
    {
        $produk = Produk::all(); 
        $pelanggan = Pelanggan::all();
        $availableSizes = Produk::whereNotNull('size')
                                ->distinct()
                                ->pluck('size')
                                ->filter()
                                ->values();

        return view('kasir.index', compact('produk', 'pelanggan', 'availableSizes'));
    }

    /**
     * Menyimpan penjualan ke database, lalu redirect ke pembayaran.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_faktur'   => 'required|date',
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'nama_pelanggan' => 'nullable|string|max:255|required_if:pelanggan_id,null',

            'produk_id'    => 'required|array',
            'produk_id.*'  => 'required|exists:produk,id',
            'size'         => 'required|array',
            'size.*'       => 'required|string|max:50',
            'harga_jual'   => 'required|array',
            'harga_jual.*' => 'required|numeric|min:0',
            'jumlah'       => 'required|array',
            'jumlah.*'     => 'required|integer|min:1',

            'total_bayar'  => 'required|numeric|min:0',
            'dibayar'      => 'nullable|numeric|min:0',
        ]);

        try {
            $penjualan = DB::transaction(function () use ($request) {
                // Jika pelanggan "umum", buat pelanggan baru dan gunakan ID-nya
                if (empty($request->pelanggan_id)) {
                    $pelanggan = Pelanggan::create([
                        'nama' => $request->nama_pelanggan,
                    ]);
                    $pelanggan_id = $pelanggan->id;
                } else {
                    $pelanggan_id = $request->pelanggan_id;
                }

                // Generate nomor faktur
                $tanggalFaktur = $request->tgl_faktur;
                $todayString   = date('Ymd', strtotime($tanggalFaktur));
                $countToday    = Penjualan::whereDate('tgl_faktur', $tanggalFaktur)->count() + 1;
                $no_faktur     = 'INV-' . $todayString . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

                // Hitung kembalian
                $dibayar = $request->dibayar ?? 0;
                $kembali = ($dibayar >= $request->total_bayar) ? ($dibayar - $request->total_bayar) : 0;

                // Simpan data penjualan
                $penjualan = Penjualan::create([
                    'no_faktur'    => $no_faktur,
                    'tgl_faktur'   => $tanggalFaktur,
                    'pelanggan_id' => $pelanggan_id,
                    'total_bayar'  => $request->total_bayar,
                    'dibayar'      => $dibayar,
                    'kembali'      => $kembali,
                ]);

                // Simpan detail penjualan
                foreach ($request->produk_id as $key => $produk_id) {
                    $harga_jual = $request->harga_jual[$key];
                    $jumlah     = $request->jumlah[$key];
                    $size       = $request->size[$key] ?? '-';
                    $sub_total  = $harga_jual * $jumlah;

                    $produk = Produk::findOrFail($produk_id);
                    if ($produk->stok < $jumlah) {
                        throw new \Exception("Stok produk {$produk->nama} tidak mencukupi. Tersedia: {$produk->stok}");
                    }

                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'produk_id'    => $produk_id,
                        'size'         => $size,
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

            return redirect()->route('kasir.pembayaran', $penjualan->id)
                            ->with('success', 'Transaksi berhasil disimpan. Lanjutkan pembayaran.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }




    /**
     * Halaman pembayaran, menampilkan detail penjualan.
     */
    public function pembayaran($id)
    {
        $penjualan = Penjualan::with(['detail.produk', 'pelanggan'])->findOrFail($id);
        return view('kasir.pembayaran', compact('penjualan'));
    }

    /**
     * Proses pembayaran tambahan (jika belum lunas).
     */
    public function prosesBayar(Request $request, $id)
    {
        $request->validate([
            'dibayar' => 'required|numeric|min:0',
        ]);

        $penjualan = Penjualan::findOrFail($id);
        $penjualan->dibayar += $request->dibayar;

        if ($penjualan->dibayar >= $penjualan->total_bayar) {
            $penjualan->kembali = $penjualan->dibayar - $penjualan->total_bayar;
        } else {
            $penjualan->kembali = 0;
        }
        $penjualan->save();

        return redirect()->route('kasir.pembayaran', $penjualan->id)
                         ->with('success', 'Pembayaran berhasil diproses.');
    }

    /**
     * Cetak nota penjualan.
     */
    public function cetakNota($id)
    {
        $penjualan = Penjualan::with(['detail.produk', 'pelanggan'])->findOrFail($id);
        return view('kasir.nota', compact('penjualan'));
    }
}
