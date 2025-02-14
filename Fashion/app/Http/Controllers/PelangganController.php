<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $pelanggan = Pelanggan::all();
        return view('pelanggan.index', compact('pelanggan'));
    }

    public function create()
    {
        return view('pelanggan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_pelanggan' => 'required|unique:pelanggan,kode_pelanggan|max:50',
            'nama' => 'required|max:100',
            'alamat' => 'required|max:200',
            'no_telp' => 'required|max:50',
            'email' => 'nullable|email|max:50',
        ]);

        Pelanggan::create($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil ditambahkan');
    }

    public function edit(Pelanggan $pelanggan)
    {
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, Pelanggan $pelanggan)
    {
        $request->validate([
            'kode_pelanggan' => 'required|max:50|unique:pelanggan,kode_pelanggan,' . $pelanggan->id,
            'nama' => 'required|max:100',
            'alamat' => 'required|max:200',
            'no_telp' => 'required|max:50',
            'email' => 'nullable|email|max:50',
        ]);

        $pelanggan->update($request->all());
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil diperbarui');
    }

    public function destroy(Pelanggan $pelanggan)
    {
        $pelanggan->delete();
        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dihapus');
    }
}
