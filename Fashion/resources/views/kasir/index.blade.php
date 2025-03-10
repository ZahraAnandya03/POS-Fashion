@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Transaksi Kasir</h2>

    <!-- Notifikasi Sukses -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Form Transaksi -->
    <div class="card shadow-sm">
        <div class="card-body">
            <form id="formTransaksi" action="{{ route('kasir.store') }}" method="post">
                @csrf

                <!-- Pilih Pelanggan & Tanggal -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="pelanggan_id" class="form-label">Pelanggan</label>
                        <select name="pelanggan_id" id="pelanggan_id" class="form-select" required>
                            <option value="">Pilih Pelanggan</option>
                            @foreach($pelanggan as $pel)
                                <option value="{{ $pel->id }}">{{ $pel->nama }}</option>
                            @endforeach
                            <option value="umum">Pelanggan Umum</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="nama_pelanggan" class="form-label"> Pelanggan</label>
                        <input type="text" name="nama_pelanggan" id="nama_pelanggan" class="form-control" 
                            placeholder="Isi jika pelanggan tidak terdaftar" required>
                    </div>                                                          
                    <div class="col-md-6">
                        <label for="tgl_faktur" class="form-label">Tanggal Faktur</label>
                        <input type="date" name="tgl_faktur" id="tgl_faktur" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <!-- Daftar Produk -->
                <h5 class="mb-3">Daftar Produk</h5>
                <table class="table table-bordered" id="tableProduk">
                    <thead class="table-light">
                        <tr>
                            <th>Produk</th>
                            <th style="width: 15%">Size</th>
                            <th style="width: 15%">Harga</th>
                            <th style="width: 10%">Jumlah</th>
                            <th style="width: 15%">Subtotal</th>
                            <th style="width: 5%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Baris produk akan ditambahkan secara dinamis -->
                    </tbody>
                </table>
                <button type="button" class="btn btn-secondary mb-4" id="btnTambahProduk">
                    Tambah Produk
                </button>

                <!-- Total Bayar, Bayar, dan Kembalian -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="total_bayar" class="form-label">Total Bayar</label>
                        <input type="number" name="total_bayar" id="total_bayar" 
                               class="form-control" value="0" readonly required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="dibayar" class="form-label">Bayar (opsional)</label>
                        <input type="number" name="dibayar" id="dibayar" 
                               class="form-control" value="0" min="0">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="kembali" class="form-label">Kembalian</label>
                        <input type="number" id="kembali" 
                               class="form-control" value="0" readonly>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">
                        Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}'
        });
    @endif

    document.addEventListener('DOMContentLoaded', function() {
        const produkData = @json($produk);
        const availableSizes = @json($availableSizes);
        let counter = 0;

        // Tombol "Tambah Produk"
        document.getElementById('btnTambahProduk').addEventListener('click', function() {
            counter++;
            const tbody = document.querySelector('#tableProduk tbody');
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <select name="produk_id[]" class="form-select produk-select" 
                            data-index="${counter}" required>
                        <option value="">Pilih Produk</option>
                        ${
                            produkData.map(p => ` 
                                <option value="${p.id}" data-harga="${p.harga}">
                                    ${p.nama}
                                </option>
                            `).join('')
                        }
                    </select>
                </td>
                <td>
                    <select name="size[]" class="form-select size-select" 
                            data-index="${counter}" required>
                        <option value="">Pilih Size</option>
                        ${
                            availableSizes.map(s => `
                                <option value="${s}">${s}</option>
                            `).join('')
                        }
                    </select>
                </td>
                <td>
                    <input type="number" name="harga_jual[]" 
                           class="form-control harga-input" 
                           data-index="${counter}" step="0.01" 
                           readonly required>
                </td>
                <td>
                    <input type="number" name="jumlah[]" 
                           class="form-control jumlah-input" 
                           data-index="${counter}" value="1" 
                           min="1" required>
                </td>
                <td>
                    <input type="number" name="subtotal[]" 
                           class="form-control subtotal" 
                           data-index="${counter}" step="0.01" 
                           value="0" readonly required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm btnHapusProduk">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Event delegation: hapus baris, pilih produk, ubah jumlah
        document.querySelector('#tableProduk tbody').addEventListener('click', function(e) {
            if (e.target.classList.contains('btnHapusProduk') || e.target.closest('.btnHapusProduk')) {
                e.target.closest('tr').remove();
                hitungTotal();
            }
        });

        document.querySelector('#tableProduk tbody').addEventListener('change', function(e) {
            if (e.target.classList.contains('produk-select')) {
                const index = e.target.dataset.index;
                const selectedOption = e.target.options[e.target.selectedIndex];
                const harga = parseFloat(selectedOption.getAttribute('data-harga')) || 0;
                document.querySelector(`.harga-input[data-index="${index}"]`).value = harga.toFixed(2);
                hitungSubtotal(index);
            }
        });

        document.querySelector('#tableProduk tbody').addEventListener('input', function(e) {
            if (e.target.classList.contains('jumlah-input')) {
                const index = e.target.dataset.index;
                hitungSubtotal(index);
            }
        });

        // Fungsi untuk hitung subtotal
        function hitungSubtotal(index) {
            const harga = parseFloat(document.querySelector(`.harga-input[data-index="${index}"]`).value) || 0;
            const jumlah = parseFloat(document.querySelector(`.jumlah-input[data-index="${index}"]`).value) || 0;
            const subtotal = harga * jumlah;
            document.querySelector(`.subtotal[data-index="${index}"]`).value = subtotal.toFixed(2);
            hitungTotal();
        }

        // Fungsi untuk hitung total semua subtotal
        function hitungTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(sub => {
                total += parseFloat(sub.value) || 0;
            });
            document.getElementById('total_bayar').value = total.toFixed(2);
            hitungKembalian();
        }

        // Fungsi untuk hitung kembalian
        document.getElementById('dibayar').addEventListener('input', hitungKembalian);
        function hitungKembalian() {
            const total   = parseFloat(document.getElementById('total_bayar').value) || 0;
            const dibayar = parseFloat(document.getElementById('dibayar').value) || 0;
            const kembali = dibayar - total;
            document.getElementById('kembali').value = kembali >= 0 ? kembali.toFixed(2) : 0;
        }
    });

    document.getElementById('pelanggan_id').addEventListener('change', function() {
    let pelangganInput = document.getElementById('nama_pelanggan');

    if (this.value === 'umum') {
        pelangganInput.disabled = true;
        pelangganInput.required = false;
        pelangganInput.value = "Umum"; // Bisa diganti dengan ID tertentu
    } else {
        pelangganInput.disabled = false;
        pelangganInput.required = true;
        pelangganInput.value = '';
    }
});



</script>
@endpush
