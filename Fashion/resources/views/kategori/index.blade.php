@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Daftar Kategori</h3>

    <!-- Tombol untuk membuka modal tambah kategori -->
    <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
        Tambah Kategori
    </button>

    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session("success") }}'
            });
        </script>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kategori as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>
                        <button class="btn btn-primary btn-sm btn-edit" 
                            data-id="{{ $item->id }}" 
                            data-nama="{{ $item->nama }}" 
                            data-bs-toggle="modal" data-bs-target="#modalEditKategori">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="confirmDelete({{ $item->id }})" class="btn btn-danger btn-sm">
                            <i class="fa fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="modalTambahKategori" tabindex="-1" aria-labelledby="modalTambahKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKategoriLabel">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('kategori.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Kategori</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="modalEditKategori" tabindex="-1" aria-labelledby="modalEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditKategoriLabel">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formEditKategori" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama Kategori</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    // Modal Edit
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.getAttribute('data-id');
            let nama = this.getAttribute('data-nama');

            document.getElementById('editNama').value = nama;
            document.getElementById('formEditKategori').setAttribute('action', `/kategori/${id}`);
        });
    });
});

// Fungsi hapus dengan konfirmasi SweetAlert
function confirmDelete(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ini akan dihapus dan tidak bisa dikembalikan!",
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
            form.action = `/kategori/${id}`;
            
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
@endsection
