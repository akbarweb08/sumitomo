@extends('layouts.app')

@section('title', 'Recorded Data - ' . date('Y-m-d'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Recorded Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <h2 class="text-center mb-0"><b>RECORD LIST</b></h2>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped text-center align-middle" style="width:100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>Lot Number</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($records as $row)
                                <tr>
                                    <td>L{{ $row->LotNumber }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::createFromFormat('!m', $row->MonthDate)->format('F') }}
                                    </td>
                                    <td>
                                        <a href="{{ route('recordeddata.show', ['date' => $row->MonthDate, 'lot' => $row->LotNumber]) }}" class="btn btn-info btn-sm" style="width: 100px;">
                                            Detail
                                        </a>
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
</script>
@endpush
