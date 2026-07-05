@extends('layouts.app')

@section('title', 'Home - Sumitomo WH')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body py-5 text-center">
                    <h1 class="display-5">Selamat Datang {{ session('name') }}</h1>
                    <p class="lead text-muted"><b>PT. Sumitomo Wiring System Batam Indonesia</b></p>
                    
                    <div class="row justify-content-center mt-4">
                        <div class="col-md-8">
                            @if ($checkData2 > 0)
                                <div class="alert alert-danger" role="alert">
                                    Ada <b>{{ $checkData2 }}</b> Box Error. <a href="#" class="alert-link">Segera Perbaiki.</a>
                                </div>
                            @elseif (session('role') == 'admin' && $checkData > 0)
                                <div class="alert alert-primary" role="alert">
                                    Ada <b>{{ $checkData }}</b> pesan yang belum dikonfirmasi. <a href="#" class="alert-link">Klik disini.</a>
                                </div>
                            @elseif (session('role') == 'user' && $checkData1 > 0)
                                <div class="alert alert-primary" role="alert">
                                    Ada <b>{{ $checkData1 }}</b> pesan yang belum dikonfirmasi. <a href="#" class="alert-link">Klik disini.</a>
                                </div>
                            @else
                                @if (request()->has('info'))
                                    <div class="alert alert-success" role="alert">Automatic Data Recording Successful.</div>
                                @else
                                    <div class="alert alert-primary" role="alert">
                                        Cek pesan <a href="#" class="alert-link">disini.</a>
                                    </div>
                                @endif
                            @endif

                            <div class="mt-4">
                                @if (session('permit') == '7')
                                    <a href="#" class="btn btn-primary btn-lg"><i class="fas fa-external-link-alt"></i> Buka Sketch LOT 7</a>
                                @endif
                                @if (in_array(session('permit'), ['206', 'super']))
                                    <a href="#" class="btn btn-primary btn-lg"><i class="fas fa-external-link-alt"></i> Buka Sketch LOT 206</a>
                                @endif
                                @if (session('permit') == 'GRACE')
                                    <a href="#" class="btn btn-primary btn-lg"><i class="fas fa-external-link-alt"></i> Buka Sketch LOT 243</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Total Pallet Pie Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Total Pallet</h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pallet On System Bar Chart -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Pallet On System <b>(Grace Excluded)</b></h5>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="barChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Prepare Data
        const pieLabels = {!! json_encode(array_column($arrayPie, 0)) !!};
        const pieData = {!! json_encode(array_column($arrayPie, 1)) !!};
        
        const barLabels = {!! json_encode(array_column($arrayBar, 0)) !!};
        const barTotalOut = {!! json_encode(array_column($arrayBar, 1)) !!};
        const barTotalIn = {!! json_encode(array_column($arrayBar, 2)) !!};

        // Render Pie Chart
        const ctxPie = document.getElementById('pieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc', '#8EF5D9', '#D798EE', '#d2d6de'],
                }]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right',
                    }
                }
            }
        });

        // Render Bar Chart
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: barLabels,
                datasets: [
                    {
                        label: 'Total Pallet Out',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        data: barTotalOut
                    },
                    {
                        label: 'Total Pallet In',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        data: barTotalIn
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                responsive: true
            }
        });
    });
</script>
@endpush
