<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index()
    {
        $data['produk'] = Produk::all();
        $data['kategori'] = Kategori::all();
        return view('produk.index')->with($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id']);
        $data['harga'] = (float) str_replace('.', '', $request->harga);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }


    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama' => 'required|max:50',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:1',
            'stok' => 'required|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'kategori_id' => 'required|exists:kategori,id', // Pastikan kategori valid
        ]);

        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id']);

        // Pastikan harga tetap float
        $data['harga'] = (float) str_replace('.', '', $request->harga);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
            Storage::delete('public/' . $produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }

}
