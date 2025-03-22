@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Daftar Pemasok</h2>
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#tambahPemasokModal">Tambah Pemasok</button>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Id</th>
                <th>Nama</th>
                <th>Nomor Telepon</th>
                <th>Email</th>
                <th>Alamat</th>
                <th>Nama Kontak</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemasok as $p)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $p->nama_pemasok }}</td>
                <td>{{ $p->nomor_telepon }}</td>
                <td>{{ $p->email }}</td>
                <td>{{ $p->alamat }}</td>
                <td>{{ $p->nama_kontak }}</td>
                <td>{{ $p->catatan }}</td>
                <td>
                    <!-- Tombol Edit -->
                    <button class="btn btn-primary btn-sm btn-edit" 
                        data-bs-toggle="modal" 
                        data-bs-target="#editPemasokModal" 
                        data-id="{{ $p->id }}" 
                        data-nama="{{ $p->nama_pemasok }}"
                        data-telepon="{{ $p->nomor_telepon }}"
                        data-email="{{ $p->email }}"
                        data-alamat="{{ $p->alamat }}"
                        data-kontak="{{ $p->nama_kontak }}"
                        data-catatan="{{ $p->catatan }}">
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

<!-- Modal Tambah Pemasok -->
<div class="modal fade" id="tambahPemasokModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Tambah Pemasok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('pemasok.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama Pemasok</label>
                        <input type="text" name="nama_pemasok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kontak</label>
                        <input type="text" name="nama_kontak" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Pemasok -->
<div class="modal fade" id="editPemasokModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Pemasok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_id">
                    <div class="mb-3">
                        <label class="form-label">Nama Pemasok</label>
                        <input type="text" id="edit_nama" name="nama_pemasok" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" id="edit_telepon" name="nomor_telepon" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" id="edit_email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea id="edit_alamat" name="alamat" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kontak</label>
                        <input type="text" id="edit_kontak" name="nama_kontak" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea id="edit_catatan" name="catatan" class="form-control"></textarea>
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
            document.getElementById("editForm").setAttribute("action", "/pemasok/" + this.dataset.id);
            document.getElementById("edit_nama").value = this.dataset.nama;
            document.getElementById("edit_telepon").value = this.dataset.telepon;
            document.getElementById("edit_email").value = this.dataset.email;
            document.getElementById("edit_alamat").value = this.dataset.alamat;
            document.getElementById("edit_kontak").value = this.dataset.kontak;
            document.getElementById("edit_catatan").value = this.dataset.catatan;
                });
            });
        });

        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data pemasok ini akan dihapus dan tidak bisa dikembalikan!",
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
                    form.action = `/pemasok/${id}`;

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

