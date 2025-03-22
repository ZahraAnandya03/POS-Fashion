@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Pengajuan Barang</h2>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Pengajuan</button>
            <div class="btn-group">
                <a href="{{ route('pengajuan.exportExcel') }}" class="btn btn-primary me-2">Export Excel</a>
                <a href="{{ route('pengajuan.exportPdf') }}" class="btn btn-primary">Export PDF</a>
            </div>

        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif



        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Nama Pengaju</th>
                    <th>Nama Barang</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Qty</th>
                    <th>Terpenuhi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pengajuan as $item)
                    <tr>
                        <td>{{ $item->nama_pengaju }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->tanggal_pengajuan }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>
                            <input type="checkbox" class="terpenuhi-toggle" data-bs-toggle="toggle"
                                data-id="{{ $item->id }}" {{ $item->terpenuhi ? 'checked' : '' }}>
                            <span class="status-label ms-2">{{ $item->terpenuhi ? 'Terpenuhi' : 'Belum Terpenuhi' }}</span>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}">
                                <i class="fa fa-trash-alt"></i>
                            </button>

                            <button class="btn btn-primary btn-sm btn-edit" data-id="{{ $item->id }}"
                                data-pelanggan_id="{{ $item->pelanggan_id }}" data-nama_barang="{{ $item->nama_barang }}"
                                data-tanggal="{{ $item->tanggal_pengajuan }}" data-qty="{{ $item->qty }}"
                                data-bs-toggle="modal" data-bs-target="#modalEdit">
                                <i class="fas fa-edit"></i>
                            </button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('pengajuan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Pengaju</label>
                            <select name="pelanggan_id" class="form-control" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $pel)
                                    <option value="{{ $pel->id }}">{{ $pel->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengajuan</label>
                            <input type="date" name="tanggal_pengajuan" class="form-control" value="{{ now()->toDateString() }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="qty" class="form-control" required min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Pengajuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEdit" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="id" id="editId">

                        <div class="mb-3">
                            <label class="form-label">Nama Pengaju</label>
                            <select name="pelanggan_id" class="form-control" id="editNamaPengaju" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($pelanggan as $pel)
                                    <option value="{{ $pel->id }}">{{ $pel->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" name="nama_barang" class="form-control" id="editNamaBarang" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Pengajuan</label>
                            <input type="date" name="tanggal_pengajuan" class="form-control" id="editTanggal"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" name="qty" class="form-control" id="editQty" required
                                min="1">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @endsection

    {{-- SweetAlert dan Script Hapus --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    @push('scripts')
        @if(session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}'
                });
            </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#pengajuanTable').DataTable();
        });

        //tanggal
        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("tanggalPengajuan").value = today;
        });


        //edit
        document.addEventListener("DOMContentLoaded", function() {
            const editButtons = document.querySelectorAll('.btn-edit');

            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    // Ambil Data dari Button
                    let id = this.getAttribute('data-id');
                    let pelangganId = this.getAttribute('data-pelanggan_id');
                    let namaBarang = this.getAttribute('data-nama_barang');
                    let tanggal = this.getAttribute('data-tanggal');
                    let qty = this.getAttribute('data-qty');

                    // Isi Form dengan Data
                    document.getElementById('editId').value = id;
                    document.getElementById('editNamaBarang').value = namaBarang;
                    document.getElementById('editTanggal').value = tanggal;
                    document.getElementById('editQty').value = qty;

                    // Set Action Form
                    document.getElementById('formEdit').action = `/pengajuan/${id}`;
                    
                    // Pilih Nama Pengaju Sesuai Data
                    let selectPengaju = document.getElementById('editNamaPengaju');
                    for (let option of selectPengaju.options) {
                        if (option.value === pelangganId) {
                            option.selected = true;
                            break;
                        }
                    }
                });
            });
        });

        //delete
        document.addEventListener("DOMContentLoaded", function() {
            let deleteButtons = document.querySelectorAll('.btn-delete');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    let pengajuanId = this.getAttribute('data-id');

                    Swal.fire({
                        title: "Apakah Anda yakin?",
                        text: "Data ini akan dihapus secara permanen!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch("{{ url('pengajuan') }}/" + pengajuanId, {
                                    method: "POST",
                                    headers: {
                                        "X-CSRF-TOKEN": document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content'),
                                        "Content-Type": "application/json"
                                    },
                                    body: JSON.stringify({
                                        _method: "DELETE"
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    Swal.fire("Dihapus!",
                                            "Data telah berhasil dihapus.", "success")
                                        .then(() => {
                                            location.reload();
                                        });
                                })
                                .catch(error => {
                                    Swal.fire("Gagal!",
                                        "Terjadi kesalahan saat menghapus data.",
                                        "error");
                                });
                        }
                    });
                });
            });
        });

        //status
        document.addEventListener("DOMContentLoaded", function() {
            let elems = document.querySelectorAll('.terpenuhi-toggle');
            elems.forEach(function(html) {
                new Switchery(html, {
                    color: '#1E90FF',
                    secondaryColor: '#CCCCCC',
                    size: 'small'
                });
            });
        });

        //AJAX untuk Toggle Status Terpenuhi
        $(document).ready(function() {
            $('.terpenuhi-toggle').change(function() {
                let id = $(this).data('id');
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let statusLabel = $(this).next('.status-label');

                $.ajax({
                    url: '/pengajuan/toggle-terpenuhi/' + id,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        terpenuhi: isChecked
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Diperbarui!',
                            text: 'Status berhasil diperbarui menjadi ' + (isChecked ?
                                'Terpenuhi' : 'Belum Terpenuhi')
                        });
                        statusLabel.text(isChecked ? 'Terpenuhi' : 'Belum Terpenuhi');
                    },
                    error: function() {
                        Swal.fire('Error!', 'Gagal mengubah status.', 'error');
                    }
                });
            });
        });
    </script>
    @endpush
