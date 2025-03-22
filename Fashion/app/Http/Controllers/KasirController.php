<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\{Penjualan, Pelanggan, Produk, DetailPenjualan};
use Illuminate\Support\Facades\Log;

class KasirController extends Controller
{
    /**
     * Menampilkan halaman kasir dengan daftar produk dan pelanggan.
     */
    public function index()
    {
        $produk = Produk::all(); 
        $pelanggan = Pelanggan::all();

        // Mengambil daftar ukuran (size) yang tersedia dari produk
        $availableSizes = Produk::whereNotNull('size')
                                ->distinct()
                                ->pluck('size')
                                ->filter()
                                ->values();

        return view('kasir.index', compact('produk', 'pelanggan', 'availableSizes'));
    }

    /**
     * Menyimpan transaksi penjualan ke database.
     */
    public function store(Request $request)
    {
        Log::info('Memulai proses penyimpanan transaksi.', ['request' => $request->all()]);

        // Validasi input
        $request->validate([
            'tgl_faktur'   => 'required|date',
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'produk_id'    => 'required|array',
            'produk_id.*'  => 'required|exists:produk,id',
            'size'         => 'required|array',
            'size.*'       => 'required|string|max:50',
            'harga'        => 'required|array',
            'harga.*'      => 'required|numeric|min:0',
            'jumlah'       => 'required|array',
            'jumlah.*'     => 'required|integer|min:1',
            'total_bayar'  => 'required|numeric|min:0',
            'dibayar'      => 'nullable|numeric|min:0',
        ]);

        try {
            // Menyimpan transaksi dalam database dengan transaksi database untuk menghindari kesalahan data
            $penjualan = DB::transaction(function () use ($request) {
                // Membuat nomor faktur berdasarkan tanggal transaksi
                $tanggalFaktur = $request->tgl_faktur;
                $todayString   = date('Ymd', strtotime($tanggalFaktur));
                $countToday    = Penjualan::whereDate('tgl_faktur', $tanggalFaktur)->count() + 1;
                $no_faktur     = 'INV-' . $todayString . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);
                
                Log::info('Membuat nomor faktur.', ['no_faktur' => $no_faktur]);

                // Menyimpan data transaksi penjualan
                $penjualan = Penjualan::create([
                    'no_faktur'    => $no_faktur,
                    'tgl_faktur'   => $tanggalFaktur,
                    'pelanggan_id' => $request->pelanggan_id,
                    'total_bayar'  => $request->total_bayar,
                    'dibayar'      => 0, // Dibayar tetap 0, nanti di prosesBayar
                    'kembali'      => 0,
                ]);

                Log::info('Penjualan berhasil disimpan.', ['penjualan_id' => $penjualan->id]);

                // Menyimpan detail penjualan dan mengurangi stok produk
                foreach ($request->produk_id as $key => $produk_id) {
                    $produk = Produk::findOrFail($produk_id);
                    $jumlah = $request->jumlah[$key];

                    // Cek stok produk sebelum mengurangi
                    if ($produk->stok < $jumlah) {
                        Log::warning('Stok tidak mencukupi.', ['produk' => $produk->nama, 'stok' => $produk->stok, 'diminta' => $jumlah]);
                        throw new \Exception("Stok produk {$produk->nama} tidak mencukupi. Tersedia: {$produk->stok}");
                    }

                    // Menyimpan detail transaksi penjualan
                    DetailPenjualan::create([
                        'penjualan_id' => $penjualan->id,
                        'produk_id'    => $produk_id,
                        'size'         => $request->size[$key] ?? '-',
                        'harga_jual'   => $request->harga[$key],
                        'jumlah'       => $jumlah,
                        'sub_total'    => $request->harga[$key] * $jumlah,
                    ]);

                    // Mengurangi stok produk
                    $produk->decrement('stok', $jumlah);
                    Log::info('Detail penjualan berhasil disimpan.', ['produk' => $produk->nama, 'jumlah' => $jumlah]);
                }

                return $penjualan;
            });

            Log::info('Transaksi berhasil disimpan.', ['penjualan_id' => $penjualan->id]);

            // Redirect ke halaman pembayaran setelah transaksi disimpan
            return redirect()->route('kasir.pembayaran', $penjualan->id)
                 ->with('success', 'Transaksi berhasil disimpan. Lanjutkan pembayaran.');
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menyimpan transaksi.', ['error' => $e->getMessage()]);
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Menampilkan halaman pembayaran untuk transaksi tertentu.
     */
    public function pembayaran($id)
    {
        // Mengambil data penjualan beserta detailnya
        $penjualan = Penjualan::with(['detail.produk', 'pelanggan'])->findOrFail($id);
        return view('kasir.pembayaran', compact('penjualan'));
    }

    /**
     * Memproses pembayaran untuk transaksi yang telah dilakukan.
     */
    public function prosesBayar(Request $request, $id)
    {
        // Validasi input pembayaran
        $request->validate([
            'dibayar' => 'required|numeric|min:0',
        ]);

        try {
            $penjualan = Penjualan::findOrFail($id);

            // Menambahkan jumlah pembayaran
            $penjualan->dibayar += $request->dibayar;
            $penjualan->kembali = max(0, $penjualan->dibayar - $penjualan->total_bayar);
            $penjualan->save();

            // Redirect ke halaman cetak nota setelah pembayaran berhasil
            return redirect()->route('kasir.cetakNota', $penjualan->id)
                            ->with('success', 'Pembayaran berhasil diproses. Silakan cetak nota.');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }

    /**
     * Menampilkan halaman cetak nota untuk transaksi tertentu.
     */
    public function cetakNota($id)
    {
        // Mengambil data penjualan beserta detailnya
        $penjualan = Penjualan::with(['detail.produk', 'pelanggan'])->findOrFail($id);
        return view('kasir.nota', compact('penjualan'));
    }
}
