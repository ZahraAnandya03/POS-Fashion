@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Produk</h2>

    <div class="row mb-3 align-items-end">
        <div class="col">
            <form method="GET" action="{{ route('produk.index') }}" class="row g-3">
                <div class="col-auto">
                    <label for="kategori_filter" class="form-label">Kategori</label>
                    <select name="kategori_filter" id="kategori_filter" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}" {{ request('kategori_filter') == $k->id ? 'selected' : '' }}>
                                {{ $k->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <label for="search" class="form-label">Cari Produk</label>
                    <input type="text" name="search" id="search" class="form-control" 
                           placeholder="Nama produk..." value="{{ request('search') }}">
                </div>
                <div class="col-auto">
                    <label for="entries" class="form-label">Show Entries</label>
                    <select name="entries" id="entries" class="form-select" onchange="this.form.submit()">
                        @php
                            $options = [5, 10, 25, 50, 100];
                        @endphp
                        @foreach($options as $opt)
                            <option value="{{ $opt }}" 
                                {{ (request('entries') == $opt) ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-secondary">Search</button>
                </div>
            </form>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Produk</button>
        </div>
    </div>
    
    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Pemasok</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Size</th>
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
                <td>{{ $p->pemasok->nama_pemasok?? '-' }}</td>
                <td>Rp {{ number_format($p->harga, 2, ',', '.') }}</td>
                <td>{{ $p->stok }}</td>
                <td>{{ $p->size ?? '-' }}</td>
                <td class="text-center">
                    @if($p->gambar)
                        <img src="{{ asset('storage/' . $p->gambar) }}" width="130" class="d-block mx-auto">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <button class="btn btn-primary btn-sm btn-edit" 
                            data-id="{{ $p->id }}" 
                            data-nama="{{ $p->nama }}"
                            data-kategori="{{ $p->kategori->id ?? '' }}"
                            data-pemasok="{{ $p->pemasok->id ?? '' }}"
                            data-harga="{{ $p->harga }}"
                            data-stok="{{ $p->stok }}"
                            data-size="{{ $p->size }}"
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
                        <label class="form-label">Pemasok</label>
                        <select id="edit_pemasok" name="pemasok_id" class="form-control" required>
                            @foreach($pemasok as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pemasok }}</option>
                            @endforeach
                        </select>
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
                        <label class="form-label">Size</label>
                        <input type="text" class="form-control" name="size" placeholder="Contoh: S, M, L atau ukuran numerik">
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
                    <input type="hidden" id="edit_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Produk</label>
                        <input type="text" id="edit_nama" class="form-control" name="nama" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pemasok</label>
                        <select id="edit_pemasok" name="pemasok_id" class="form-control" required>
                            @foreach($pemasok as $p)
                                <option value="{{ $p->id }}">{{ $p->nama_pemasok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select id="edit_kategori" name="kategori_id" class="form-control" required>
                            @foreach($kategori as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="number" id="edit_harga" class="form-control" name="harga" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Stok</label>
                        <input type="number" id="edit_stok" class="form-control" name="stok" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Size</label>
                        <input type="text" id="edit_size" class="form-control" name="size">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file" id="edit_gambar" class="form-control" name="gambar">
                        <img id="preview_gambar" src="" width="130" class="d-block mt-2">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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
        //edit
        document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".btn-edit").forEach(button => {
            button.addEventListener("click", function() {
                document.getElementById("edit_id").value = this.dataset.id;
                document.getElementById("edit_nama").value = this.dataset.nama;
                document.getElementById("edit_kategori").value = this.dataset.kategori;
                document.getElementById("edit_pemasok").value = this.dataset.pemasok;
                document.getElementById("edit_harga").value = this.dataset.harga;
                document.getElementById("edit_stok").value = this.dataset.stok;
                document.getElementById("edit_size").value = this.dataset.size;
                
                let imgPreview = document.getElementById("preview_gambar");
                if (this.dataset.gambar) {
                    imgPreview.src = this.dataset.gambar;
                    imgPreview.style.display = "block";
                } else {
                    imgPreview.style.display = "none";
                }

                document.getElementById("formEdit").action = "/produk/" + this.dataset.id;
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
