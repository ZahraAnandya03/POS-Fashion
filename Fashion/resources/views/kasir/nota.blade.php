<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota - {{ $penjualan->no_faktur }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 14px; 
            text-align: center; 
            margin: 0;
        }
        .nota-container { 
            width: 80mm; 
            margin: 0 auto; 
            padding: 10px;
            border: 1px solid #000;
        }
        .nota-header, .nota-footer { 
            text-align: center; 
            font-weight: bold;
        }
        .nota-body { 
            margin: 10px 0; 
            text-align: left;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        table th, table td { 
            padding: 5px; 
            text-align: left; 
        }
        table th { 
            border-bottom: 1px solid #000; 
            text-align: center;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <div class="nota-container">
        <div class="nota-header">
            <h3>Nota Penjualan</h3>
            <p>No. Faktur: {{ $penjualan->no_faktur }}</p>
        </div>
        
        <div class="nota-body">
            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($penjualan->tgl_faktur)->format('d-m-Y H:i') }}</p>
            <p><strong>Pelanggan:</strong> {{ $penjualan->pelanggan->nama ?? '-' }}</p>

            <table>
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th class="text-center">Size</th>
                        <th class="text-center">Qty</th>
                        <th class="text-right">Harga</th>
                        <th class="text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($penjualan->detail as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama }}</td>
                        <td class="text-center">{{ $detail->produk->size ?? '-' }}</td>
                        <td class="text-center">{{ $detail->jumlah }}</td>
                        <td class="text-right">Rp {{ number_format($detail->harga_jual, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format($detail->sub_total, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <table>
                <tr>
                    <td><strong>Total:</strong></td>
                    <td class="text-right">Rp {{ number_format($penjualan->total_bayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Bayar:</strong></td>
                    <td class="text-right">Rp {{ number_format($penjualan->dibayar, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Kembali:</strong></td>
                    <td class="text-right">Rp {{ number_format($penjualan->kembali, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        
        <div class="nota-footer">
            <p>Terima kasih telah berbelanja.</p>
        </div>
    </div>
</body>
</html>
