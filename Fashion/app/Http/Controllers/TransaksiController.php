<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use App\Models\Pelanggan;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    
    public function index()
    {
        $transaksi = Transaksi::with('pelanggan')
            ->orderBy('id', 'desc')
            ->paginate(10);

        $pelanggan = Pelanggan::all();  // Pastikan data pelanggan diambil

        return view('transaksi.index', compact('transaksi', 'pelanggan'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::all();
        $produk = Produk::all();
        $noFaktur = 'INV-' . date('Ymd') . rand(100, 999);

        return view('transaksi.create', compact('pelanggan', 'produk', 'noFaktur'));
    }

    /**
     * Simpan transaksi baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_faktur'    => 'required|unique:transaksi,no_faktur',
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'total'        => 'required|numeric',
            'bayar'        => 'required|numeric',
            'kembali'      => 'required|numeric',
            'detail'       => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::create([
                'no_faktur'    => $request->no_faktur,
                'pelanggan_id' => $request->pelanggan_id,
                'total'        => $request->total,
                'bayar'        => $request->bayar,
                'kembali'      => $request->kembali,
            ]);

            foreach ($request->detail as $det) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $det['produk_id'],
                    'qty'          => $det['qty'],
                    'harga'        => $det['harga'],
                    'subtotal'     => $det['subtotal'],
                ]);
            }

            DB::commit();
            return redirect()
                ->route('transaksi.index')
                ->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'msg' => 'Terjadi kesalahan saat menyimpan transaksi: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Tampilkan detail transaksi.
     */
    public function show($id)
    {
        $transaksi = Transaksi::with('detailTransaksi.produk', 'pelanggan')
            ->findOrFail($id);

        return view('transaksi.show', compact('transaksi'));
    }

    /**
     * Tampilkan form untuk mengedit transaksi.
     */
    public function edit($id)
    {
        $transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);
        $pelanggan = Pelanggan::all();
        $produk = Produk::all();

        return view('transaksi.edit', compact('transaksi', 'pelanggan', 'produk'));
    }

    /**
     * Update transaksi yang sudah ada.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'nullable|exists:pelanggan,id',
            'total'        => 'required|numeric',
            'bayar'        => 'required|numeric',
            'kembali'      => 'required|numeric',
            'detail'       => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $transaksi = Transaksi::findOrFail($id);

            $transaksi->update([
                'pelanggan_id' => $request->pelanggan_id,
                'total'        => $request->total,
                'bayar'        => $request->bayar,
                'kembali'      => $request->kembali,
            ]);

            DetailTransaksi::where('transaksi_id', $transaksi->id)->delete();

            foreach ($request->detail as $det) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $det['produk_id'],
                    'qty'          => $det['qty'],
                    'harga'        => $det['harga'],
                    'subtotal'     => $det['subtotal'],
                ]);
            }

            DB::commit();
            return redirect()
                ->route('transaksi.index')
                ->with('success', 'Transaksi berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors([
                'msg' => 'Terjadi kesalahan saat update transaksi: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Hapus transaksi dari database.
     */
    public function destroy($id)
    {
        try {
            Transaksi::destroy($id);
            return redirect()
                ->route('transaksi.index')
                ->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors([
                'msg' => 'Gagal menghapus: ' . $e->getMessage()
            ]);
        }
    }
}
