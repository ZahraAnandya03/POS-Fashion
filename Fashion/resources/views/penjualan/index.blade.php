@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Data Penjualan</h2>

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
            </form>
        </div>
    
        <div class="col-auto">
        </div>
    </div>    

    <!-- Tabel Penjualan -->
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
                <th>Aksi</th>
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
                    <td>                      
                        <button class="btn btn-info btn-sm btn-detail" data-id="{{ $p->id }}">
                            <i class="fa fa-eye"></i>
                        </button>
                        <button class="btn btn-primary btn-sm btn-edit" 
                                data-id="{{ $p->id }}" 
                                data-pelanggan_id="{{ $p->pelanggan_id }}" 
                                data-tgl_faktur="{{ $p->tgl_faktur }}" 
                                data-total_bayar="{{ $p->total_bayar }}" 
                                data-dibayar="{{ $p->dibayar }}"
                                data-size="{{ $p->size }}">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $p->id }}">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </td>                    
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Create Penjualan -->
<div class="modal fade" id="modalCreate" tabindex="-1" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form action="{{ route('penjualan.store') }}" method="post">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="modalCreateLabel">Transaksi Penjualan Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <!-- Pilih Pelanggan -->
              <div class="mb-3">
                  <label for="pelanggan_id" class="form-label">Pelanggan:</label>
                  <select name="pelanggan_id" class="form-select" required>
                      <option value="">Pilih Pelanggan</option>
                      @foreach($pelanggan as $pel)
                          <option value="{{ $pel->id }}">{{ $pel->nama }}</option>
                      @endforeach
                  </select>
              </div>
              
              <!-- Tanggal Faktur -->
              <div class="mb-3">
                  <label for="tgl_faktur" class="form-label">Tanggal Faktur:</label>
                  <input type="date" name="tgl_faktur" class="form-control" value="{{ date('Y-m-d') }}" required>
              </div>
              
              <div class="row mb-3">
                  <div class="col-md-4">
                      <label for="produk_id" class="form-label">Produk:</label>
                      <select name="produk_id[]" class="form-select" required>
                          <option value="">Pilih Produk</option>
                          @foreach($produk as $pr)
                              <option value="{{ $pr->id }}">{{ $pr->nama }}</option>
                          @endforeach
                      </select>
                  </div>
                  <div class="col-md-4">
                      <label for="harga_jual" class="form-label">Harga Jual:</label>
                      <input type="number" name="harga_jual[]" class="form-control" step="0.01" required>
                  </div>
                  <div class="col-md-4">
                      <label for="jumlah" class="form-label">Jumlah:</label>
                      <input type="number" name="jumlah[]" class="form-control" required>
                  </div>
              </div>
              <!-- Field Size hanya di modal create -->
              <div class="mb-3">
                  <label for="size" class="form-label">Size:</label>
                  <select name="size" class="form-select" required>
                    <option value="">Pilih Size</option>
                    @foreach($availableSizes as $size)
                        <option value="{{ $size }}">{{ $size }}</option>
                    @endforeach
                </select>
              </div>
              <div class="mb-3">
                  <label for="total_bayar" class="form-label">Total Bayar:</label>
                  <input type="text" name="total_bayar" class="form-control" id="total_bayar" required>
              </div>
              <div class="mb-3">
                  <label for="dibayar" class="form-label">Bayar:</label>
                  <input type="text" name="dibayar" class="form-control" id="dibayar" placeholder="Nominal uang yang dibayar customer">
              </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            <button type="submit" class="btn btn-primary">Simpan Penjualan</button>
          </div>
        </form>
      </div>
    </div>
</div>    

<!-- Modal Edit Penjualan -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <form id="formEdit" method="post">
          @csrf
          @method('PUT')
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditLabel">Edit Penjualan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
              <input type="hidden" name="id" id="edit_id">
              <div class="mb-3">
                  <label for="edit_pelanggan_id" class="form-label">Pelanggan:</label>
                  <select name="pelanggan_id" id="edit_pelanggan_id" class="form-select" required>
                      <option value="">Pilih Pelanggan</option>
                      @foreach($pelanggan as $pel)
                          <option value="{{ $pel->id }}">{{ $pel->nama }}</option>
                      @endforeach
                  </select>
              </div>
              <div class="mb-3">
                  <label for="edit_tgl_faktur" class="form-label">Tanggal Faktur:</label>
                  <input type="date" name="tgl_faktur" id="edit_tgl_faktur" class="form-control" required>
              </div>
              <!-- Field Size di modal edit -->
              <div class="mb-3">
                  <label for="edit_size" class="form-label">Size:</label>
                  <input type="text" name="size" id="edit_size" class="form-control" placeholder="Contoh: S, M, L atau ukuran numerik">
              </div>
              <div class="mb-3">
                  <label for="edit_total_bayar" class="form-label">Total Bayar:</label>
                  <input type="text" name="total_bayar" class="form-control" id="edit_total_bayar" required>
              </div>
              <div class="mb-3">
                  <label for="edit_dibayar" class="form-label">Bayar:</label>
                  <input type="text" name="dibayar" class="form-control" id="edit_dibayar" required>
              </div>
          </div>
          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
              <button type="submit" class="btn btn-primary">Update Penjualan</button>
          </div>
        </form>
      </div>
    </div>
</div>

<!-- Modal Detail Penjualan -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-labelledby="modalDetailLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalDetailLabel">Detail Penjualan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modalDetailContent">
        <!-- Data detail penjualan akan dimuat di sini -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
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

    document.addEventListener('DOMContentLoaded', function() {
        const formatRibuan = (angka) => {
            return angka.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        };

        const bersihkanFormat = (angka) => {
            return angka.replace(/\./g, "").replace(",", ".");
        };

        const inputTotal = document.getElementById('total_bayar');
        const inputDibayar = document.getElementById('dibayar');

        const handleInput = (event) => {
            let angkaBersih = bersihkanFormat(event.target.value);
            if(!isNaN(angkaBersih) && angkaBersih !== "") {
                event.target.value = formatRibuan(angkaBersih);
            } else {
                event.target.value = "";
            }
        };

        if(inputTotal) {
            inputTotal.addEventListener('input', handleInput);
        }
        if(inputDibayar) {
            inputDibayar.addEventListener('input', handleInput);
        }

        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function() {
                if(inputTotal) {
                    inputTotal.value = bersihkanFormat(inputTotal.value);
                }
                if(inputDibayar) {
                    inputDibayar.value = bersihkanFormat(inputDibayar.value);
                }
            });
        });
    });

    // Modal Detail
    document.querySelectorAll('.btn-detail').forEach(button => {
        button.addEventListener('click', function() {
            let id = this.dataset.id;
            fetch(`/penjualan/${id}`)
                .then(response => response.json())
                .then(data => {
                    let modalContent = `
                        <h5>Detail Penjualan: ${data.no_faktur}</h5>
                        <p>Tanggal: ${data.tgl_faktur}</p>
                        <p>Pelanggan: ${data.pelanggan.nama}</p>
                        <p>Total Bayar: ${data.total_bayar}</p>
                        <p>Bayar: ${data.dibayar}</p>
                        <p>Kembali: ${data.kembali}</p>
                        <p>Size: ${data.size ? data.size : '-'}</p>
                        <h6>Detail Produk:</h6>
                        <ul>
                    `;
                    data.detail.forEach(detail => {
                        modalContent += `<li>${detail.produk.nama} - ${detail.jumlah} x ${detail.harga_jual} = ${detail.sub_total}</li>`;
                    });
                    modalContent += `</ul>`;
                    document.getElementById('modalDetailContent').innerHTML = modalContent;
                    new bootstrap.Modal(document.getElementById('modalDetail')).show();
                })
                .catch(error => {
                    console.error(error);
                    alert('Gagal mengambil data detail penjualan');
                });
        });
    });

    // Modal Edit
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const pelanggan_id = this.dataset.pelanggan_id;
            const tgl_faktur = this.dataset.tgl_faktur;
            const total_bayar = this.dataset.total_bayar;
            const dibayar = this.dataset.dibayar;
            const size = this.dataset.size;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_pelanggan_id').value = pelanggan_id;
            document.getElementById('edit_tgl_faktur').value = tgl_faktur;
            document.getElementById('edit_total_bayar').value = total_bayar;
            document.getElementById('edit_dibayar').value = dibayar;
            document.getElementById('edit_size').value = size ? size : '';

            document.getElementById('formEdit').action = `/penjualan/${id}`;
            new bootstrap.Modal(document.getElementById('modalEdit')).show();
        });
    });

    // SweetAlert untuk Hapus
    document.querySelectorAll('.btnDelete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;

            Swal.fire({
                title: 'Yakin Hapus?',
                text: "Data tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.action = `/penjualan/${id}`;
                    form.method = 'POST';
                    form.innerHTML = `
                        @csrf
                        @method('DELETE')
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        });
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
