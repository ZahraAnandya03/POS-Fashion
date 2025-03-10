<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $entries = $request->input('entries', 10);
        $query = Produk::with(['kategori', 'pemasok']);

        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        if ($request->filled('kategori_filter')) {
            $query->where('kategori_id', $request->kategori_filter);
        }

        $produk = $query->paginate($entries)->appends($request->only('search', 'entries', 'kategori_filter'));

        // Pastikan nama kolom benar di dalam tabel
        $kategori = Kategori::orderBy('nama', 'asc')->get();
        $pemasok = Pemasok::orderBy('nama_pemasok', 'asc')->get(); // Ganti 'nama_pemasok' dengan nama kolom yang benar

        return view('produk.index', compact('produk', 'kategori', 'pemasok'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'        => 'required|max:50',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'pemasok_id'  => 'required|exists:pemasok,id',
            'size'        => 'nullable|string|max:50',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id', 'pemasok_id', 'size']);
        $data['harga'] = floatval($request->harga);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }
    
    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);
    
        $request->validate([
            'nama'        => 'required|max:50',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer|min:0',
            'kategori_id' => 'required|exists:kategori,id',
            'pemasok_id'  => 'required|exists:pemasok,id',
            'size'        => 'nullable|string|max:50',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id', 'pemasok_id', 'size']);
        $data['harga'] = floatval($request->harga);
    
        // Update gambar jika ada file yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
                Storage::delete('public/' . $produk->gambar);
            }
    
            // Simpan gambar baru dengan nama unik
            $namaFile = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $data['gambar'] = $request->file('gambar')->storeAs('produk', $namaFile, 'public');
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
