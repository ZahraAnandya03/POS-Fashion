@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Laporan Penjualan</h2>

    <div class="row mb-3 align-items-end">
        <div class="col">
            <form method="GET" action="{{ route('penjualan.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                    <input type="date" name="tanggal_awal" id="tanggal_awal" 
                        class="form-control" value="{{ request('tanggal_awal') }}">
                </div>
                <div class="col-auto">
                    <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                    <input type="date" name="tanggal_akhir" id="tanggal_akhir" 
                        class="form-control" value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary">
                        Filter
                    </button>
                </div>
            </div>        
            </form>
            <div class="col text-end">
                <a href="{{ route('penjualan.pdf', ['tanggal_awal' => request('tanggal_awal'), 'tanggal_akhir' => request('tanggal_akhir')]) }}" 
                    class="btn btn-primary">
                    <i class="fas fa-file-pdf"></i> Cetak
                </a>
                
            </div>
        </div>
    </div>
    

    <!-- Tabel Penjualan -->
    <div id="laporanPenjualan">
        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>No.</th>
                    <th>Invoice</th>
                    <th>Tanggal</th>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Bayar</th>
                    <th>Kembali</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan as $key => $p)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $p->no_faktur }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tgl_faktur)->format('d M Y') }}</td>
                        <td>{{ $p->pelanggan->nama ?? 'Pelanggan Biasa' }}</td>
                        <td>Rp. {{ number_format($p->total_bayar, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($p->dibayar, 2, ',', '.') }}</td>
                        <td>Rp. {{ number_format($p->kembali, 2, ',', '.') }}</td>                
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
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

    function printLaporan() {
        let originalTitle = document.title;
        document.title = "Laporan Penjualan";
        
        let printContent = document.getElementById("laporanPenjualan").outerHTML;
        let printWindow = window.open("", "_blank");
        printWindow.document.write(`
            <html>
                <head>
                    <title>Laporan Penjualan</title>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid black; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        @media print {
                            button { display: none; }
                        }
                    </style>
                </head>
                <body>
                    <h2 style="text-align: center;">Laporan Penjualan</h2>
                    ${printContent}
                </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
        printWindow.onafterprint = function() {
            document.title = originalTitle;
            printWindow.close();
        };
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
