@extends('layouts.app')

@section('content')
    <h2>Tambah Produk</h2>
    <form action="{{ route('produk.store') }}" method="POST">
        @csrf
        <label>Nama Produk:</label>
        <input type="text" name="nama_produk" required>
        <button type="submit">Simpan</button>
    </form>
@endsection

