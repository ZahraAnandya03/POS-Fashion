<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // Menentukan jumlah data per halaman (default 10, misalnya)
        $entries = $request->filled('entries') ? (int)$request->entries : 10;

        // Mulai query produk
        $query = Produk::with('kategori');

        // Jika ada pencarian 'search'
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'LIKE', "%{$search}%");
        }

        // Ambil data dengan pagination
        $produk = $query->paginate($entries);

        // Pastikan agar pagination tidak merusak pencarian
        // dengan menambahkan ->appends() agar query string tetap terjaga
        $produk->appends($request->only('search', 'entries'));

        // Data lain untuk form
        $kategori = Kategori::all();

        return view('produk.index', compact('produk', 'kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|max:50',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:1',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'size'        => 'nullable|string|max:50', // validasi untuk size
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id', 'size']);
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
            'nama'        => 'required|max:50',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:1',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'size'        => 'nullable|string|max:50', // validasi untuk size
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id', 'size']);
        // Pastikan harga tetap float
        $data['harga'] = (float) str_replace('.', '', $request->harga);

        if ($request->hasFile('gambar')) {
            // Jika terdapat gambar lama, hapus file tersebut
            if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
                Storage::delete('public/' . $produk->gambar);
            }
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
