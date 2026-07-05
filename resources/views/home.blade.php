@extends('layouts.app')

@section('title', 'Home - Sumitomo WH')

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 bg-primary text-white" style="background: linear-gradient(135deg, #1A314B, #2c3e50) !important; border-radius: 15px;">
                <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2 class="fw-bold mb-1">Selamat Datang, {{ session('name') }}!</h2>
                        <p class="mb-0 text-white-50"><i class="fas fa-building me-2"></i>PT. Sumitomo Wiring System Batam Indonesia</p>
                    </div>
                    <div class="mt-3 mt-md-0 text-end">
                        <span class="badge bg-light text-primary px-3 py-2 rounded-pill shadow-sm fs-6">
                            <i class="fas fa-user-tag me-1"></i> Role: {{ ucfirst(session('role')) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts & Notifications -->
    <div class="row mb-4">
        <div class="col-12">
            @if ($checkData2 > 0)
                <div class="alert alert-danger shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 12px; border-left: 5px solid #dc3545 !important;">
                    <i class="fas fa-exclamation-triangle fs-4 me-3 text-danger"></i>
                    <div>
                        <strong>Peringatan!</strong> Ada <b>{{ $checkData2 }}</b> Box Error. <a href="{{ route('boxerror.index') }}" class="alert-link text-danger text-decoration-underline">Segera Perbaiki.</a>
                    </div>
                </div>
            @elseif (session('role') == 'admin' && $checkData > 0)
                <div class="alert alert-warning shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 12px; border-left: 5px solid #ffc107 !important;">
                    <i class="fas fa-bell fs-4 me-3 text-warning"></i>
                    <div>
                        <strong>Notifikasi Admin:</strong> Ada <b>{{ $checkData }}</b> pesan yang belum dikonfirmasi. <a href="#" class="alert-link text-warning text-decoration-underline">Klik di sini untuk mengecek.</a>
                    </div>
                </div>
            @elseif (session('role') == 'user' && $checkData1 > 0)
                <div class="alert alert-info shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 12px; border-left: 5px solid #0dcaf0 !important;">
                    <i class="fas fa-envelope-open-text fs-4 me-3 text-info"></i>
                    <div>
                        <strong>Pesan Baru:</strong> Ada <b>{{ $checkData1 }}</b> pesan yang belum dikonfirmasi. <a href="#" class="alert-link text-info text-decoration-underline">Klik di sini.</a>
                    </div>
                </div>
            @else
                @if (request()->has('info'))
                    <div class="alert alert-success shadow-sm border-0 d-flex align-items-center" role="alert" style="border-radius: 12px; border-left: 5px solid #198754 !important;">
                        <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
                        <div>Automatic Data Recording Successful.</div>
                    </div>
                @else
                    <div class="alert alert-light shadow-sm border-0 d-flex align-items-center text-secondary" role="alert" style="border-radius: 12px;">
                        <i class="fas fa-info-circle fs-4 me-3"></i>
                        <div>Sistem berjalan normal. Cek pesan <a href="#" class="alert-link text-primary">di sini</a>.</div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Quick Access Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3 text-muted fw-bold"><i class="fas fa-bolt me-2 text-warning"></i>Akses Cepat Sketch</h5>
            <div class="d-flex flex-wrap gap-3">
                @if (session('permit') == '7' || session('role') == 'admin')
                    <a href="{{ route('sketch.show', ['lot' => '7']) }}" class="btn btn-primary shadow-sm" style="border-radius: 10px; padding: 12px 25px; background: linear-gradient(135deg, #3498db, #2980b9); border: none;">
                        <i class="fas fa-map-marked-alt me-2"></i> Buka Sketch LOT 7
                    </a>
                @endif
                @if (in_array(session('permit'), ['206', 'super']) || session('role') == 'admin')
                    <a href="{{ route('sketch.show', ['lot' => '206']) }}" class="btn btn-success shadow-sm" style="border-radius: 10px; padding: 12px 25px; background: linear-gradient(135deg, #2ecc71, #27ae60); border: none;">
                        <i class="fas fa-map-marked-alt me-2"></i> Buka Sketch LOT 206
                    </a>
                @endif
                @if (session('permit') == 'GRACE' || session('role') == 'admin')
                    <a href="{{ route('sketch.show', ['lot' => '243']) }}" class="btn btn-info text-white shadow-sm" style="border-radius: 10px; padding: 12px 25px; background: linear-gradient(135deg, #00c6ff, #0072ff); border: none;">
                        <i class="fas fa-map-marked-alt me-2"></i> Buka Sketch LOT 243
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row">
        <!-- Total Pallet Pie Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-chart-pie me-2 text-danger"></i>Distribusi Pallet</h5>
                </div>
                <div class="card-body">
                    <div style="height: 320px; position: relative;">
                        <canvas id="pieChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pallet On System Bar Chart -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header bg-white border-0 pt-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0 text-dark"><i class="fas fa-chart-bar me-2 text-success"></i>Aktivitas Pallet <small class="text-muted fw-normal fs-6">(Grace Excluded)</small></h5>
                </div>
                <div class="card-body">
                    <div style="height: 320px; position: relative;">
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
