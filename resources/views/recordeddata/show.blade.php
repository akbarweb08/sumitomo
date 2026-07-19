@extends('layouts.app')

@section('title', 'Recorded Data Detail - ' . date('Y-m-d'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('recordeddata.index') }}">Recorded Data</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detail Lot {{ $lot }}</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <h2 class="text-center mb-0"><b>RECORD LIST {{ $lot }}</b></h2>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped text-center align-middle" style="width:100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>Lot Number</th>
                                <th>Date</th>
                                <th>Save Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $row)
                                <tr>
                                    <td>{{ $row->LotNumber }}</td>
                                    <td>{{ $row->Date }}</td>
                                    <td>{{ $row->exactDate }}</td>
                                    <td>
                                        <a href="{{ route('recordeddata.sketch', ['lot' => $lot, 'date' => $row->Date]) }}" class="btn btn-info btn-sm" style="width: 100px;">
                                            View
                                        </a>
                                        @if(session('role') == 'admin')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord('{{ $row->LotNumber }}', '{{ $row->Date }}')" style="width: 100px;">
                                                Delete
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            stateSave: false,
            "bDestroy": true,
            lengthChange: false,
            pageLength: 15,
            "aaSorting": []
        });
    });

    function deleteRecord(lot, date) {
        if (confirm("Are you sure you want to delete this record?")) {
            $.ajax({
                type: "POST",
                url: '{{ route('recordeddata.destroy') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE',
                    LotNumber: lot,
                    Date: date
                },
                success: function(response) {
                    if (response.status === 'error') {
                        Swal.fire('Oops...', response.message, 'error');
                    } else {
                        location.reload();
                    }
                }
            });
        }
    }
</script>
@endpush
