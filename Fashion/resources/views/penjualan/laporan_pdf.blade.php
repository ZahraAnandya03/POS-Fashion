<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Laporan Penjualan</h2>
    <table>
        <thead>
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
                    <td>{{ $p->pelanggan->nama ?? '-' }}</td>
                    <td>Rp. {{ number_format($p->total_bayar, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($p->dibayar, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($p->kembali, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>