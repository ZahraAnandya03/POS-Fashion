<?php

namespace App\Http\Controllers;

use App\Models\barang;
use App\Models\produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function index()
    {
        $barang = Barang::with('produk')->get(); // Mengambil barang beserta relasi produk
        $produk = Produk::all(); // Mengambil semua produk untuk dropdown di modal

        return view('barang.index', compact('barang', 'produk'));
    }

    public function create()
    {
        $produk = Produk::all();
        return view('barang.create', compact('produk'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_barang' => 'required|unique:barang|max:50',
            'produk_id' => 'required|exists:produk,id',
            'nama_barang' => 'required|max:100',
            'satuan' => 'required|max:10',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
        ]);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        Barang::create([
            'kode_barang' => $request->kode_barang,
            'produk_id' => $request->produk_id,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'user_id' => Auth::id(), // Gunakan Auth::id() untuk user yang sedang login
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan.');
    }


    public function edit(Barang $barang)
    {
        $produk = Produk::all();
        return view('barang.edit', compact('barang', 'produk'));
    }

    public function update(Request $request, Barang $barang)
    {
        $request->validate([
            'kode_barang' => 'required|max:50|unique:barang,kode_barang,' . $barang->id,
            'produk_id' => 'required|exists:produk,id',
            'nama_barang' => 'required|max:100',
            'satuan' => 'required|max:10',
            'harga_jual' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'ditarik' => 'boolean',
        ]);

        // Hanya update field yang diperbolehkan
        $barang->update([
            'kode_barang' => $request->kode_barang,
            'produk_id' => $request->produk_id,
            'nama_barang' => $request->nama_barang,
            'satuan' => $request->satuan,
            'harga_jual' => $request->harga_jual,
            'stok' => $request->stok,
            'ditarik' => $request->ditarik ?? 0,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diperbarui.');
    }

    public function destroy(Barang $barang)
    {
        $barang->delete();
        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus.');
    }
}
