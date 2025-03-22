<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    /**
     * Menampilkan daftar pelanggan dengan opsi pencarian.
     */
    public function index(Request $request)
    {
        // Membuat query dasar untuk mengambil data pelanggan
        $query = Pelanggan::query();

        // Jika terdapat input pencarian, filter berdasarkan nama atau kode pelanggan
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama', 'LIKE', "%{$search}%")
                  ->orWhere('kode_pelanggan', 'LIKE', "%{$search}%");
        }

        // Mengambil data pelanggan yang sesuai dengan filter
        $pelanggan = $query->get();

        // Menampilkan halaman daftar pelanggan
        return view('pelanggan.index', compact('pelanggan'));
    }

    /**
     * Menampilkan halaman tambah pelanggan.
     */
    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Menyimpan data pelanggan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'kode_pelanggan' => 'required|unique:pelanggan,kode_pelanggan|max:50',
            'nama'           => 'required|max:100',
            'alamat'         => 'required|max:200',
            'no_telp'        => 'required|max:50',
            'email'          => 'nullable|email|max:50',
        ]);

        // Menyimpan data pelanggan baru ke dalam database
        Pelanggan::create($request->all());

        // Redirect ke halaman daftar pelanggan dengan pesan sukses
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    /**
     * Menampilkan halaman edit pelanggan.
     */
    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Memperbarui data pelanggan yang sudah ada di database.
     */
    public function update(Request $request, Pelanggan $pelanggan)
    {
        // Validasi input yang diperbarui
        $request->validate([
            'kode_pelanggan' => 'required|max:50|unique:pelanggan,kode_pelanggan,' . $pelanggan->id,
            'nama'           => 'required|max:100',
            'alamat'         => 'required|max:200',
            'no_telp'        => 'required|max:50',
            'email'          => 'nullable|email|max:50',
        ]);

        // Memperbarui data pelanggan di database
        $pelanggan->update($request->all());

        // Redirect ke halaman daftar pelanggan dengan pesan sukses
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui');
    }

    /**
     * Menghapus data pelanggan dari database.
     */
    public function destroy(Pelanggan $pelanggan)
    {
        // Menghapus pelanggan berdasarkan ID
        $pelanggan->delete();

        // Redirect ke halaman daftar pelanggan dengan pesan sukses
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
