@extends('layouts.app')

@section('title', 'Receipt Data - ' . date('Y-m-d'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Receipt Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-end align-items-center">
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#modal-add">Add New / Upload</button>
            </div>
            
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped text-center align-middle" style="width:100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Invoice Number</th>
                                <th>Pallet Number</th>
                                <th>Date Added</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach($receipts as $row)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $row->invoiceNumber }}</td>
                                    <td>{{ $row->palletNumber }}</td>
                                    <td>{{ $row->dateAdd }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="modal-add" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="{{ route('masterreceipt.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Insert Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-danger">Paste receipt data below (Tab separated: Invoice, Pallet). Note: this will override existing receipts.</label>
                        <textarea name="receiptData" class="form-control" style="height: 300px" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit New Data</button>
                </div>
            </div>
        </form>
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
            "bPaginate": false,
            ordering: false,
            initComplete: function() {
                this.api().columns([1, 2, 3]).every(function() {
                    let column = this;
                    let select = document.createElement('select');
                    select.classList.add('form-select', 'form-select-sm');
                    select.add(new Option('All', ''));
                    column.header().replaceChildren(select);

                    select.addEventListener('change', function() {
                        var val = $.fn.dataTable.util.escapeRegex(select.value);
                        column.search(val ? '^' + val + '$' : '', true, false).draw();
                    });

                    column.data().unique().sort().each(function(d, j) {
                        select.add(new Option(d));
                    });
                });
            }
        });
    });
</script>
@endpush
