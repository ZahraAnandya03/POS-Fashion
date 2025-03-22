@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Transaksi Pembelian</h2>
    <form action="{{ route('pembelian.store') }}" method="POST">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6">
                <label class="form-label">Pemasok</label>
                <select name="pemasok_id" class="form-select">
                    @foreach($pemasok as $p)
                        <option value="{{ $p->id }}">{{ $p->nama_pemasok }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
            </div>
        </div>

        <h5>Daftar Produk</h5>
        <table class="table table-bordered mt-1">
            <thead class="table-light">
                <tr>
                    <th>Produk</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="daftar_produk"></tbody>
        </table>

        <button type="button" class="btn btn-secondary" id="tambahProduk">Tambah Produk</button>
        
        <div class="mt-3">
            <label class="form-label">Total Bayar</label>
            <input type="text" id="total_harga" class="form-control" name="total_harga" readonly>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}'
        });
    @endif
    
    document.addEventListener('DOMContentLoaded', function () {
    let daftarProduk = document.getElementById('daftar_produk');
    let tambahProdukBtn = document.getElementById('tambahProduk');
    let totalHargaInput = document.getElementById('total_harga');

    let produkCount = 0; // Untuk mengelola indeks array dalam form

    tambahProdukBtn.addEventListener('click', function () {
        produkCount++;
        let row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <select class="form-select produk" name="produk_id[]">
                    <option value="">Pilih Produk</option>
                    @foreach($produk as $pr)
                        <option value="{{ $pr->id }}" data-harga="{{ $pr->harga_beli }}" data-jual="{{ $pr->harga_jual }}">{{ $pr->nama }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" class="form-control harga_beli" name="harga_beli[]"></td>
            <td><input type="number" class="form-control harga_jual" name="harga_jual[]"></td>
            <td><input type="number" class="form-control jumlah" name="jumlah[]" value="1" min="1"></td>
            <td><input type="number" class="form-control subtotal" readonly></td>
            <td><button type="button" class="btn btn-danger btn-hapus">Hapus</button></td>
        `;

        daftarProduk.appendChild(row);
        updateSubtotal(row);
    });

    daftarProduk.addEventListener('change', function (event) {
        let target = event.target;
        let row = target.closest('tr');
        if (target.classList.contains('produk')) {
            let selectedOption = target.options[target.selectedIndex];
            let hargaBeli = selectedOption.dataset.harga || 0;
            let hargaJual = selectedOption.dataset.jual || 0;
            row.querySelector('.harga_beli').value = hargaBeli;
            row.querySelector('.harga_jual').value = hargaJual;
            updateSubtotal(row);
        }
    });

    daftarProduk.addEventListener('input', function (event) {
    let row = event.target.closest('tr');
        if (event.target.classList.contains('harga_beli') || event.target.classList.contains('jumlah')) {
            updateSubtotal(row);
        }
    });


    daftarProduk.addEventListener('click', function (event) {
        if (event.target.classList.contains('btn-hapus')) {
            event.target.closest('tr').remove();
            updateTotalHarga();
        }
    });

    function updateSubtotal(row) {
        let hargaBeli = parseFloat(row.querySelector('.harga_beli').value) || 0;
        let jumlah = parseInt(row.querySelector('.jumlah').value) || 1;
        let subtotal = hargaBeli * jumlah;
        row.querySelector('.subtotal').value = subtotal;
        updateTotalHarga();
    }

    function updateTotalHarga() {
        let total = 0;
        document.querySelectorAll('.subtotal').forEach(function (input) {
            total += parseFloat(input.value) || 0;
        });
        totalHargaInput.value = total;
    }
});

</script>
@endsection
