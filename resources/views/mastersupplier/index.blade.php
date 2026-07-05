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
                                <th onclick="sortTable(1)" style="cursor:pointer;">Supplier Name</th>
                                <th onclick="sortTable(2)" style="cursor:pointer;">Lot Place</th>
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
                                    <td>{{ $row->supplier }}</td>
                                    <td>{{ $row->LotPlace }}</td>
                                    <td style="width: 250px;">
                                        <button type="button" class="btn btn-success btn-sm me-2" style="width: 80px;"
                                            data-colors="{{ $row->id }};{{ $row->supplier }};{{ $row->LotPlace }};"
                                            onclick="toggleEditModal(this)">Edit</button>
                                        <button type="button" class="btn btn-danger btn-sm" style="width: 80px;" onclick="deleteData('{{ $row->id }}')">Delete</button>
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
                    <h5 class="modal-title">Input Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" name="SupplierName" class="form-control" id="inputSupplierName" placeholder="Enter Supplier Name" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label>Pilih Lot</label>
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
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" name="SupplierName" class="form-control" id="editSupplierName" placeholder="Enter Supplier Name" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label>Pilih Lot</label>
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
                    <input type="hidden" name="Id" id="editId">
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
        let data = $(btn).attr("data-colors").split(';');
        $("#editId").val(data[0]);
        $("#editSupplierName").val(data[1]);
        $("#EditLotPlace").val(data[2]);
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
