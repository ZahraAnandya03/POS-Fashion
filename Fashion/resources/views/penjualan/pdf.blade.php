<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Penjualan</h2>
    <p><strong>Periode:</strong> {{ $tanggal_awal }} - {{ $tanggal_akhir }}</p>

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
                    <td>{{ $p->pelanggan->nama ?? 'Pelanggan Biasa' }}</td>
                    <td>Rp. {{ number_format($p->total_bayar, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($p->dibayar, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($p->kembali, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
