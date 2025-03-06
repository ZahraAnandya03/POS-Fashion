@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Transaksi</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Tombol untuk memunculkan modal -->
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate">
        Buat Transaksi Baru
    </button>

    <!-- Modal Create -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('transaksi.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Buat Transaksi Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- NO FAKTUR -->
                        <div class="mb-3">
                            <label for="no_faktur" class="form-label">No Faktur</label>
                            <input type="text" class="form-control" id="no_faktur" name="no_faktur" required>
                        </div>

                        <!-- PELANGGAN -->
                        <div class="mb-3">
                            <label for="pelanggan_id" class="form-label">Pelanggan</label>
                            <select name="pelanggan_id" id="pelanggan_id" class="form-select">
                                <option value="">-- Pilih Pelanggan --</option>
                                @foreach($pelanggan as $p)
                                    <option value="{{ $p->id }}">{{ $p->nama_pelanggan }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- TOTAL -->
                        <div class="mb-3">
                            <label for="total" class="form-label">Total</label>
                            <input type="number" step="0.01" class="form-control" id="total" name="total" required>
                        </div>

                        <!-- BAYAR -->
                        <div class="mb-3">
                            <label for="bayar" class="form-label">Bayar</label>
                            <input type="number" step="0.01" class="form-control" id="bayar" name="bayar" required>
                        </div>

                        <!-- KEMBALI -->
                        <div class="mb-3">
                            <label for="kembali" class="form-label">Kembali</label>
                            <input type="number" step="0.01" class="form-control" id="kembali" name="kembali" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- END Modal Create -->

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>No Faktur</th>
                <th>Pelanggan</th>
                <th>Total</th>
                <th>Bayar</th>
                <th>Kembali</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksi as $key => $row)
            <tr>
                <td>{{ $transaksi->firstItem() + $key }}</td>
                <td>{{ $row->no_faktur }}</td>
                <td>{{ $row->pelanggan->nama_pelanggan ?? '-' }}</td>
                <td>{{ number_format($row->total, 2) }}</td>
                <td>{{ number_format($row->bayar, 2) }}</td>
                <td>{{ number_format($row->kembali, 2) }}</td>
                <td>
                    <a href="{{ route('transaksi.show', $row->id) }}" class="btn btn-info btn-sm">Detail</a>
                    <a href="{{ route('transaksi.edit', $row->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('transaksi.destroy', $row->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Yakin hapus transaksi?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center">Belum ada data transaksi</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tampilkan pagination -->
    {{ $transaksi->links() }}
</div>
@endsection
