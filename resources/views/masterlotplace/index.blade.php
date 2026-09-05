@extends('layouts.app')

@section('title', 'Master Data Lot Place')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('masterdata.index') }}">Master Data</a></li>
        <li class="breadcrumb-item active" aria-current="page">Lot Place</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Master Data Lot Place</h5>
                <div>
                    <button type="button" class="btn btn-info text-white me-2" onclick="toggleModal()">Add New</button>
                    <a href="{{ route('masterdata.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle" id="table">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Lot Number</th>
                                <th>Alamat Gedung Utama</th>
                                <th>No Contact</th>
                                <th>Penanggung Jawab Perusahaan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lotPlaces as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $row->lot_number }}</strong></td>
                                    <td>{{ $row->alamat_gedung_utama }}</td>
                                    <td>{{ $row->no_contact }}</td>
                                    <td>{{ $row->nama_penanggung_jawab_perusahaan }}</td>
                                    <td style="width: 180px;">
                                        <button type="button" class="btn btn-success btn-sm me-1" style="width: 70px;"
                                            data-id="{{ $row->id }}"
                                            data-lot_number="{{ $row->lot_number }}"
                                            data-alamat="{{ $row->alamat_gedung_utama }}"
                                            data-contact="{{ $row->no_contact }}"
                                            data-pic="{{ $row->nama_penanggung_jawab_perusahaan }}"
                                            onclick="toggleEditModal(this)">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm" style="width: 70px;" onclick="deleteData('{{ $row->id }}')">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Belum ada data lot place.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modalAdd" tabindex="-1" aria-labelledby="modalAddLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formAdd" onsubmit="event.preventDefault(); submitAdd();" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddLabel">Input Data Lot Place</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lot Number <span class="text-danger">*</span></label>
                        <input type="text" name="lot_number" class="form-control" placeholder="Contoh: 206, 7, GRACE" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Gedung Utama</label>
                        <textarea name="alamat_gedung_utama" class="form-control" rows="2" placeholder="Masukkan Alamat Gedung Utama"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Contact</label>
                        <input type="text" name="no_contact" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penanggung Jawab Perusahaan</label>
                        <input type="text" name="nama_penanggung_jawab_perusahaan" class="form-control" placeholder="Masukkan Nama PIC Perusahaan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEdit" onsubmit="event.preventDefault(); submitEdit();" autocomplete="off">
            @csrf
            <input type="hidden" name="id" id="editId">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLabel">Edit Data Lot Place</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lot Number <span class="text-danger">*</span></label>
                        <input type="text" name="lot_number" id="editLotNumber" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Gedung Utama</label>
                        <textarea name="alamat_gedung_utama" id="editAlamat" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Contact</label>
                        <input type="text" name="no_contact" id="editContact" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penanggung Jawab Perusahaan</label>
                        <input type="text" name="nama_penanggung_jawab_perusahaan" id="editPic" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    let addModal = new bootstrap.Modal(document.getElementById('modalAdd'));
    let editModal = new bootstrap.Modal(document.getElementById('modalEdit'));

    function toggleModal() {
        document.getElementById('formAdd').reset();
        addModal.show();
    }

    function toggleEditModal(btn) {
        let $btn = $(btn);
        $("#editId").val($btn.data('id'));
        $("#editLotNumber").val($btn.data('lot_number'));
        $("#editAlamat").val($btn.data('alamat'));
        $("#editContact").val($btn.data('contact'));
        $("#editPic").val($btn.data('pic'));
        editModal.show();
    }

    function submitAdd() {
        let data = $('#formAdd').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route('masterlotplace.store') }}',
            data: data,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Gagal menambahkan data: ' + (xhr.responseJSON?.message || 'Error'), 'error');
            }
        });
    }

    function submitEdit() {
        let data = $('#formEdit').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route('masterlotplace.update') }}',
            data: data,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    Swal.fire('Berhasil', response.message, 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr) {
                Swal.fire('Error', 'Gagal mengubah data: ' + (xhr.responseJSON?.message || 'Error'), 'error');
            }
        });
    }

    function deleteData(id) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data lot place ini akan dihapus secara permanen.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.getElementById('deleteForm');
                form.action = '/masterlotplace/' + id;
                form.submit();
            }
        });
    }
</script>
@endpush
