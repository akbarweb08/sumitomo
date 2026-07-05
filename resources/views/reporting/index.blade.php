@extends('layouts.app')

@section('title', 'Reporting - ' . date('Y-m-d'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Reporting</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white">
                <h2 class="text-center mb-0"><b>REPORTING</b></h2>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped text-center align-middle" style="width:100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>Date In</th>
                                <th>Confirm In</th>
                                <th>Lot Number</th>
                                <th>Line</th>
                                <th>No Invoice</th>
                                <th>No Pallet</th>
                                <th>Date Out</th>
                                <th>Confirm By</th>
                                <th>Return To</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pallets as $row)
                                <tr>
                                    <td>{{ $row->DateIn }}</td>
                                    <td>{{ $row->ConfirmBy }}</td>
                                    <td>{{ $row->LotNumber }}</td>
                                    <td>L{{ $row->LotNumber }}-{{ $row->lineGroup }}</td>
                                    <td style="background-color: {{ $row->ColorHex }}; color: {{ $row->ColorText }}">
                                        {{ $row->InvoiceNumber }}
                                    </td>
                                    <td style="background-color: {{ $row->ColorHex }}; color: {{ $row->ColorText }}">
                                        {{ $row->Prefiks }}{{ $row->PalletNumber }}
                                    </td>
                                    <td>{{ $row->DateOut }}</td>
                                    <td>{{ $row->ConfirmOut }}</td>
                                    <td>{{ $row->returnTo }}</td>
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
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        var table = $('#example').DataTable({
            stateSave: false,
            "bDestroy": true,
            lengthChange: false,
            pageLength: 20,
            "aaSorting": [],
            dom: 'Bfrtip',
            buttons: [
                {
                    extend: 'print',
                    className: 'btn btn-primary btn-sm ms-2'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-danger btn-sm ms-2'
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success btn-sm ms-2'
                }
            ]
        });
    });
</script>
@endpush
