<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export PDF Pengajuan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Data Pengajuan Barang</h2>
    <table>
        <thead>
            <tr>
                <th>Nama Pengaju</th>
                <th>Nama Barang</th>
                <th>Tanggal Pengajuan</th>
                <th>Qty</th>
                <th>Terpenuhi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pengajuan as $item)
                <tr>
                    <td>{{ $item->nama_pengaju }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->tanggal_pengajuan }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->terpenuhi ? 'Terpenuhi' : 'Belum Terpenuhi' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
