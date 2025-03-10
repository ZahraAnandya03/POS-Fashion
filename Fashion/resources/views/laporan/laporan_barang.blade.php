@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Data Barang</h2>
    
    {{-- Form Filter Kategori --}}
    <form action="{{ route('laporan.laporan_barang') }}" method="GET" class="mb-3">
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
                <a href="{{ route('laporan.laporan_barang') }}" class="btn btn-secondary">Search</a>
            </div>
            <div class="col-md-4 text-end">
                <a href="{{ route('laporan.barang.cetak', ['kategori' => request('kategori')]) }}" class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Cetak
                </a>
            </div>
        </div>
    </form>

    <div class="card mb-4">
        <div class="card-body">
            <table class="table table-bordered" id="dataTable">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Pemasok</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($produk as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->kategori->nama ?? 'Tidak Ada' }}</td>
                        <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ $item->stok }}</td>
                        <td>{{ $item->pemasok->nama_pemasok ?? 'Tidak Ada' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });
</script>
@endsection
