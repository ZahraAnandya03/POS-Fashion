@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Produk</h2>

    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Produk</button>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($produk as $key => $p)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->kategori->nama ?? '-' }}</td>
                <td>Rp {{ number_format($p->harga, 2, ',', '.') }}</td>
                <td>{{ $p->stok }}</td>
                <td>
                    @if($p->gambar)
                        <img src="{{ asset('storage/' . $p->gambar) }}" width="50">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <button class="btn btn-primary btn-sm btn-edit" 
                            data-id="{{ $p->id }}" 
                            data-nama="{{ $p->nama }}"
                            data-kategori="{{ $p->kategori->id ?? '' }}"
                            data-harga="{{ $p->harga }}"
                            data-stok="{{ $p->stok }}"
                            data-gambar="{{ $p->gambar ? asset('storage/' . $p->gambar) : '' }}"
                            data-bs-toggle="modal" data-bs-target="#modalEdit">
                            <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="confirmDelete({{ $p->id }})" class="btn btn-danger btn-sm">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahLabel">Tambah Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formTambah" action="{{ route('produk.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select name="kategori_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>                    
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" class="form-control" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" class="form-control" name="stok" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" class="form-control" name="gambar">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEdit" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label">Nama Produk</label>
                            <input type="text" id="edit_nama" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kategori</label>
                            <select id="edit_kategori" name="kategori_id" class="form-control" required>
                                @foreach($kategori as $k)
                                    <option value="{{ $k->id }}">{{ $k->nama }}</option>
                                @endforeach
                            </select>
                        </div>                    
                        <div class="mb-3">
                            <label class="form-label">Harga</label>
                            <input type="number" id="edit_harga" name="harga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stok</label>
                            <input type="number" id="edit_stok" name="stok" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Gambar</label>
                            <input type="file" class="form-control" name="gambar">
                            <img id="edit_gambar" src="" width="100" style="display: none; margin-top: 10px;">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}'
            });
        </script>
    @endif
    
        <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".btn-edit").forEach(button => {
                button.addEventListener("click", function () {
                    document.getElementById("edit_nama").value = this.dataset.nama;
                    document.getElementById("edit_kategori").value = this.dataset.kategori;
                    document.getElementById("edit_harga").value = this.dataset.harga;
                    document.getElementById("edit_stok").value = this.dataset.stok;

                    let gambar = this.dataset.gambar;
                    if (gambar) {
                        document.getElementById("edit_gambar").src = gambar;
                        document.getElementById("edit_gambar").style.display = "block";
                    } else {
                        document.getElementById("edit_gambar").style.display = "none";
                    }
                    
                    let formEdit = document.getElementById("formEdit");
                    formEdit.setAttribute("action", `/produk/${this.dataset.id}`);
                });
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data produk ini akan dihapus dan tidak bisa dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/produk/${id}`;

                    let csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);

                    let methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        </script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush