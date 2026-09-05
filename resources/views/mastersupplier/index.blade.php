@extends('layouts.app')

@section('title', 'Master Data Supplier')

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('masterdata.index') }}">Master Data</a></li>
        <li class="breadcrumb-item active" aria-current="page">Supplier</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-info text-white me-2" onclick="toggleModal()">Add New</button>
                <a href="{{ route('masterdata.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle" id="table">
                        <thead class="table-dark">
                            <tr>
                                <th onclick="sortTable(0)" style="cursor:pointer;">No.</th>
                                <th onclick="sortTable(1)" style="cursor:pointer;">Kode Prefiks</th>
                                <th onclick="sortTable(2)" style="cursor:pointer;">Nama Perusahaan</th>
                                <th onclick="sortTable(3)" style="cursor:pointer;">Lot Place</th>
                                <th>Alamat</th>
                                <th>Penanggung Jawab</th>
                                <th>No Contact</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 1; @endphp
                            @foreach($suppliers as $row)
                                @php
                                    $bgColor = '';
                                    if($row->LotPlace == 'TURUNAN206') $bgColor = '#b8fcf7';
                                    else if($row->LotPlace == 'REPACK') $bgColor = '#d4c6c5';
                                    else if($row->LotPlace == 'GRACE') $bgColor = '#ffdbd9';
                                    else if($row->LotPlace == '242') $bgColor = '#fff2cf';
                                @endphp
                                <tr style="background-color: {{ $bgColor }}">
                                    <td>{{ $i }}</td>
                                    <td>{{ $row->prefix }}</td>
                                    <td>{{ $row->supplier }}</td>
                                    <td>{{ $row->LotPlace }}</td>
                                    <td>{{ $row->address }}</td>
                                    <td>{{ $row->pic_name }}</td>
                                    <td>{{ $row->contact }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td style="width: 180px;">
                                        <button type="button" class="btn btn-success btn-sm me-1" style="width: 70px;"
                                            data-id="{{ $row->id }}"
                                            data-prefix="{{ $row->prefix }}"
                                            data-supplier="{{ $row->supplier }}"
                                            data-lotplace="{{ $row->LotPlace }}"
                                            data-address="{{ $row->address }}"
                                            data-pic_name="{{ $row->pic_name }}"
                                            data-contact="{{ $row->contact }}"
                                            data-email="{{ $row->email }}"
                                            onclick="toggleEditModal(this)">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm" style="width: 70px;" onclick="deleteData('{{ $row->id }}')">Delete</button>
                                    </td>
                                </tr>
                                @php $i++; @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="masterdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="myFormId" onsubmit="event.preventDefault(); submitData();" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Data Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Prefiks</label>
                        <input type="text" name="prefix" class="form-control" id="inputPrefix" placeholder="Contoh: DA, DS, RA" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="SupplierName" class="form-control" id="inputSupplierName" placeholder="Masukkan Nama Perusahaan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Lot</label>
                        <select class="form-select" id="InputLotPlace" name="LotPlace" required>
                            @if(in_array(session('permit'), ['7', 'super']))
                                <option value="7">7</option>
                            @endif
                            @if(in_array(session('permit'), ['206', 'super']))
                                <option value="206">206</option>
                                <option value="TURUNAN206">TURUNAN206</option>
                                <option value="REPACK">REPACK</option>
                            @endif
                            @if(in_array(session('permit'), ['GRACE', 'super']))
                                <option value="GRACE">GRACE</option>
                                <option value="242">242</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" id="inputAddress" rows="2" placeholder="Masukkan Alamat"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penanggung Jawab</label>
                        <input type="text" name="pic_name" class="form-control" id="inputPicName" placeholder="Masukkan Nama Penanggung Jawab">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Contact</label>
                        <input type="text" name="contact" class="form-control" id="inputContact" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="inputEmail" placeholder="Contoh: supplier@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="myFormId1" onsubmit="event.preventDefault(); editData();" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Supplier</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="Id" id="editId">
                    <div class="mb-3">
                        <label class="form-label">Kode Prefiks</label>
                        <input type="text" name="prefix" class="form-control" id="editPrefix" placeholder="Contoh: DA, DS, RA" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan</label>
                        <input type="text" name="SupplierName" class="form-control" id="editSupplierName" placeholder="Masukkan Nama Perusahaan" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pilih Lot</label>
                        <select class="form-select" id="EditLotPlace" name="LotPlace" required>
                            @if(in_array(session('permit'), ['7', 'super']))
                                <option value="7">7</option>
                            @endif
                            @if(in_array(session('permit'), ['206', 'super']))
                                <option value="206">206</option>
                                <option value="TURUNAN206">TURUNAN206</option>
                                <option value="REPACK">REPACK</option>
                            @endif
                            @if(in_array(session('permit'), ['GRACE', 'super']))
                                <option value="GRACE">GRACE</option>
                                <option value="242">242</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="address" class="form-control" id="editAddress" rows="2" placeholder="Masukkan Alamat"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Penanggung Jawab</label>
                        <input type="text" name="pic_name" class="form-control" id="editPicName" placeholder="Masukkan Nama Penanggung Jawab">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Contact</label>
                        <input type="text" name="contact" class="form-control" id="editContact" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" id="editEmail" placeholder="Contoh: supplier@example.com">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
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
    let addModal = new bootstrap.Modal(document.getElementById('masterdata'));
    let editModal = new bootstrap.Modal(document.getElementById('editdata'));

    function toggleModal() {
        addModal.show();
    }

    function toggleEditModal(btn) {
        let $btn = $(btn);
        $("#editId").val($btn.data('id'));
        $("#editPrefix").val($btn.data('prefix'));
        $("#editSupplierName").val($btn.data('supplier'));
        $("#EditLotPlace").val($btn.data('lotplace'));
        $("#editAddress").val($btn.data('address'));
        $("#editPicName").val($btn.data('pic_name'));
        $("#editContact").val($btn.data('contact'));
        $("#editEmail").val($btn.data('email'));
        editModal.show();
    }

    function submitData() {
        let data = $('#myFormId').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route('mastersupplier.store') }}',
            data: data,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    location.reload();
                }
            }
        });
    }

    function editData() {
        let data = $('#myFormId1').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route('mastersupplier.update') }}',
            data: data,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    location.reload();
                }
            }
        });
    }

    function deleteData(id) {
        if(confirm('Are you sure you want to delete this supplier?')) {
            let form = document.getElementById('deleteForm');
            form.action = '/mastersupplier/' + id;
            form.submit();
        }
    }

    // Simple table sorting
    function sortTable(n) {
        var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
        table = document.getElementById("table");
        switching = true;
        dir = "asc";
        while (switching) {
            switching = false;
            rows = table.rows;
            for (i = 1; i < (rows.length - 1); i++) {
                shouldSwitch = false;
                x = rows[i].getElementsByTagName("TD")[n];
                y = rows[i + 1].getElementsByTagName("TD")[n];
                if (dir == "asc") {
                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                } else if (dir == "desc") {
                    if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                        shouldSwitch = true;
                        break;
                    }
                }
            }
            if (shouldSwitch) {
                rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                switching = true;
                switchcount++;
            } else {
                if (switchcount == 0 && dir == "asc") {
                    dir = "desc";
                    switching = true;
                }
            }
        }
    }
</script>
@endpush
