@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pembayaran & Nota</h2>

    <div class="card mb-3">
        <div class="card-header">
            Nota Penjualan - {{ $penjualan->no_faktur }}
        </div>
        <div class="card-body">
            <p><strong>Tanggal:</strong> {{ $penjualan->tgl_faktur }}</p>
            <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>
            <p><strong>Total Bayar:</strong> Rp. {{ number_format($penjualan->total_bayar, 2, ',', '.') }}</p>
            <p><strong>Bayar:</strong> Rp. {{ number_format($penjualan->dibayar, 2, ',', '.') }}</p>
            <p><strong>Kembali:</strong> Rp. {{ number_format($penjualan->kembali, 2, ',', '.') }}</p>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Detail Produk</div>
        <ul class="list-group list-group-flush">
            @foreach($penjualan->detail as $detail)
                <li class="list-group-item">
                    {{ $detail->produk->nama }} 
                    - {{ $detail->jumlah }} x 
                    Rp. {{ number_format($detail->harga_jual, 2, ',', '.') }}
                    = Rp. {{ number_format($detail->sub_total, 2, ',', '.') }}
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Form Pembayaran jika belum lunas -->
    @if($penjualan->dibayar < $penjualan->total_bayar)
    <div class="card mb-3">
        <div class="card-header">Proses Pembayaran</div>
        <div class="card-body">
            <form action="{{ route('kasir.prosesBayar', $penjualan->id) }}" method="post">
                @csrf
                <div class="mb-3">
                    <label for="dibayar" class="form-label">Nominal Bayar</label>
                    <input type="number" name="dibayar" class="form-control" step="0.01" required>
                </div>
                <button type="submit" class="btn btn-success">Bayar</button>
            </form>
        </div>
    </div>
    @endif

    <button class="btn btn-primary" onclick="cetakNota()">Cetak Nota</button>
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}'
        });
    @endif

function cetakNota() {
    let url = "{{ route('kasir.cetakNota', $penjualan->id) }}";
    window.open(url, '_blank');
}
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
