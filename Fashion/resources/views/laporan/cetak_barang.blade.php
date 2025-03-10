<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Barang</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #ddd; }
        h2 { text-align: center; }
    </style>
</head>
<body>
    <h2>Laporan Data Barang</h2>
    <table>
        <thead>
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
</body>
</html>
