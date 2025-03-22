@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Keuntungan</h2>

    {{-- Form Filter Kategori --}}
    <form action="{{ route('laporan.keuntungan') }}" method="GET" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <select name="kategori" id="kategori" class="form-control" onchange="this.form.submit()">
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($kategoriList as $kategori)
                        <option value="{{ $kategori->id }}" {{ request('kategori') == $kategori->id ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-secondary">Search</button>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('laporan.keuntungan.cetak', ['kategori' => request('kategori')]) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Cetak 
                </a>
            </div>
        </div>
    </form>    

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Stok Awal</th>
                <th>Terjual</th>
                <th>Keuntungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($produk as $key => $p)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->stok_awal }}</td>
                    <td>{{ $p->terjual }}</td>
                    <td>Rp. {{ number_format($p->keuntungan, 2, ',', '.') }}</td>                
                </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection
