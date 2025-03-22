<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Pemasok;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Menampilkan daftar produk dengan fitur pencarian dan filter kategori.
     */
    public function index(Request $request)
    {
        // Menentukan jumlah data per halaman (default: 10)
        $entries = $request->input('entries', 10);

        // Query dasar untuk mengambil data produk beserta relasi kategori dan pemasok
        $query = Produk::with(['kategori', 'pemasok']);

        // Filter pencarian berdasarkan nama produk
        if ($request->filled('search')) {
            $query->where('nama', 'LIKE', "%{$request->search}%");
        }

        // Filter berdasarkan kategori jika ada input kategori_filter
        if ($request->filled('kategori_filter')) {
            $query->where('kategori_id', $request->kategori_filter);
        }

        // Ambil data produk dengan pagination
        $produk = $query->paginate($entries)->appends($request->only('search', 'entries', 'kategori_filter'));

        // Mengambil daftar kategori dan pemasok untuk keperluan filter
        $kategori = Kategori::orderBy('nama', 'asc')->get();
        $pemasok = Pemasok::orderBy('nama_pemasok', 'asc')->get();

        // Menampilkan halaman daftar produk
        return view('produk.index', compact('produk', 'kategori', 'pemasok'));
    }

    /**
     * Menyimpan produk baru ke dalam database.
     */
    public function store(Request $request)
    {
        // Validasi input
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

        // Ambil input yang diperlukan
        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id', 'pemasok_id', 'size']);
        $data['harga'] = floatval($request->harga);

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('gambar', 'public');
        }
        
        // Simpan data produk ke database
        Produk::create($data);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan');
    }

    /**
     * Memperbarui data produk yang sudah ada di database.
     */
    public function update(Request $request, $id)
    {
        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Validasi input
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

        // Ambil input yang diperlukan
        $data = $request->only(['nama', 'deskripsi', 'stok', 'kategori_id', 'pemasok_id', 'size']);
        $data['harga'] = floatval($request->harga);

        // Update gambar jika ada file baru yang diunggah
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) { 
                Storage::delete('public/' . $produk->gambar);
            }
        
            // Simpan gambar baru ke folder gambar
            $namaFile = time() . '_' . $request->file('gambar')->getClientOriginalName();
            $data['gambar'] = $request->file('gambar')->storeAs('gambar', $namaFile, 'public');
        }        

        // Update data produk di database
        $produk->update($data);

        // Redirect kembali dengan pesan sukses
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui');
    }

    /**
     * Menghapus produk dari database.
     */
    public function destroy($id)
    {
        // Cari produk berdasarkan ID
        $produk = Produk::findOrFail($id);

        // Hapus gambar dari storage jika ada
        if ($produk->gambar && Storage::exists('public/' . $produk->gambar)) {
            Storage::delete('public/' . $produk->gambar);
        }

        // Hapus data produk dari database
        $produk->delete();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
