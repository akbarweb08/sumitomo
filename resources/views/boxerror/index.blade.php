@extends('layouts.app')

@section('title', 'Box Error - ' . date('Y-m-d'))

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Box Error</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-end align-items-center">
                <button onclick="fixAllBox()" type="button" class="btn btn-info text-white">Fix All</button>
            </div>
            
            <div class="card-body">
                @if($errorCount > 0)
                    <div class="alert alert-danger" role="alert" style="text-align: right">
                        Ada <b>{{ $errorCount }}</b> Box Error. <b>Segera Perbaiki.</b>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Lot Number</th>
                                <th>Box Number</th>
                                <th>Line Group</th>
                                <th>Pallet Order</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($boxes as $row)
                                <tr>
                                    <td>{{ $row->LotNumber }}</td>
                                    <td>{{ $row->BoxNumber }}</td>
                                    <td>{{ $row->lineGroup }}</td>
                                    <td>{{ $row->palletGroup }}</td>
                                    <td>
                                        <button type="button" class="btn btn-primary btn-sm" onclick="fixBox('{{ $row->Id }}', '{{ $row->LotNumber }}', '{{ $row->lineGroup }}', '{{ $row->BoxNumber }}', '{{ $row->palletGroup }}')" style="width: 70px;">
                                            Fix
                                        </button>
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
<script>
    function fixAllBox() {
        if(confirm("Fix all boxes?")) {
            $.ajax({
                type: "POST",
                url: '{{ route('boxerror.fix') }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: "fixall"
                },
                success: function(response) {
                    if (response.status === 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: response.message,
                            timer: 2000,
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    }

    function fixBox(id, lot, line, box, pallet) {
        $.ajax({
            type: "POST",
            url: '{{ route('boxerror.fix') }}',
            data: {
                _token: '{{ csrf_token() }}',
                type: "fix",
                IdPallet: id,
                LotNumber: lot,
                lineGroup: line,
                BoxNumber: box,
                palletGroup: pallet
            },
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                        timer: 2000,
                    });
                } else {
                    location.reload();
                }
            }
        });
    }
</script>
@endpush
