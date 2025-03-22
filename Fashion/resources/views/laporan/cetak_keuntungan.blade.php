<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuntungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2 class="text-center">Laporan Keuntungan</h2>
    <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Stok Awal</th>
                <th>Terjual</th>
                <th>Harga</th>
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
                    <td>Rp. {{ number_format($p->harga, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($p->keuntungan, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
