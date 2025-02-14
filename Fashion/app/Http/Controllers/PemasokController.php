<?php

namespace App\Http\Controllers;

use App\Models\Pemasok;
use Illuminate\Http\Request;

class PemasokController extends Controller
{
    public function index()
    {
        $pemasok = Pemasok::all();
        return view('pemasok.index', compact('pemasok'));
    }

    public function create()
    {
        return view('pemasok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'nama_kontak' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        Pemasok::create($request->all());

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil ditambahkan.');
    }

    public function edit(Pemasok $pemasok)
    {
        return view('pemasok.edit', compact('pemasok'));
    }

    public function update(Request $request, Pemasok $pemasok)
    {
        $request->validate([
            'nama_pemasok' => 'required|string|max:255',
            'nomor_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'alamat' => 'nullable|string',
            'nama_kontak' => 'nullable|string|max:255',
            'catatan' => 'nullable|string',
        ]);

        $pemasok->update($request->all());

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil diperbarui.');
    }

    public function destroy(Pemasok $pemasok)
    {
        $pemasok->delete();

        return redirect()->route('pemasok.index')->with('success', 'Pemasok berhasil dihapus.');
    }
}

