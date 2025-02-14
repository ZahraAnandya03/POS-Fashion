@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pelanggan</h1>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPelangganModal">Tambah Pelanggan</button>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pelanggan as $p)
            <tr>
                <td>{{ $p->kode_pelanggan }}</td>
                <td>{{ $p->nama }}</td>
                <td>{{ $p->alamat }}</td>
                <td>{{ $p->no_telp }}</td>
                <td>{{ $p->email }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-primary btn-sm btn-edit" 
                    data-bs-toggle="modal" 
                    data-bs-target="#editPelangganModal" 
                    data-id="{{ $p->id }}" 
                    data-kode="{{ $p->kode_pelanggan }}"
                    data-nama="{{ $p->nama }}"
                    data-alamat="{{ $p->alamat }}"
                    data-telepon="{{ $p->no_telp }}"
                    data-email="{{ $p->email }}">
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

<!-- Modal Tambah Pelanggan -->
<div class="modal fade" id="tambahPelangganModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pelanggan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggan</label>
                        <input type="text" name="kode_pelanggan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pelanggan -->
<div class="modal fade" id="editPelangganModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggan</label>
                        <input type="text" id="edit_kode_pelanggan" name="kode_pelanggan" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" id="edit_nama" name="nama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea id="edit_alamat" name="alamat" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" id="edit_no_telp" name="no_telp" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
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
            button.addEventListener("click", function() {
                let id = this.dataset.id;
                document.getElementById("editForm").setAttribute("action", "/pelanggan/" + id);
                document.getElementById("edit_kode_pelanggan").value = this.dataset.kode;
                document.getElementById("edit_nama").value = this.dataset.nama;
                document.getElementById("edit_alamat").value = this.dataset.alamat;
                document.getElementById("edit_no_telp").value = this.dataset.telepon;
                document.getElementById("edit_email").value = this.dataset.email;
                });
            });
        });
        
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pelanggan ini akan dihapus dan tidak bisa dikembalikan!",
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
                    form.action = `/pelanggan/${id}`;

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
