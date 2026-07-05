@extends('layouts.app')

@section('title', 'Pallet Error - ' . date('Y-m-d'))

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pallet Error</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-end align-items-center">
                <a href="#" class="btn btn-info text-white me-2" style="width: 130px">Clear Check</a>
                <a href="#" class="btn btn-info text-white me-2" style="width: 130px">Re-Check</a>
                <a href="#" class="btn btn-info text-white" style="width: 130px">Check All</a>
            </div>
            
            <div class="card-body">
                @if($errorCount > 0)
                    <div class="alert alert-warning" role="alert" style="text-align: right">
                        Ada <b>{{ $errorCount }}</b> Pallet Error.
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Lot Number</th>
                                <th>Line Group</th>
                                <th>Invoice</th>
                                <th>Pallet</th>
                                <th style="width: 200px;">Error Message</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pallets as $row)
                                <tr>
                                    <td>{{ $row->LotNumber }}</td>
                                    <td>{{ $row->lineGroup }}</td>
                                    <td>{{ $row->InvoiceNumber }}</td>
                                    <td>{{ $row->PalletNumber }}</td>
                                    <td style="text-align: left; background-color: #FFF3CD;">
                                        {{ $row->errMsg }}
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
