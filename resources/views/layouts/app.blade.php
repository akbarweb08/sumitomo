<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Warehouse System')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
        }
        .bg-blue {
            background-color: #1A314B !important;
        }
        .navbar-nav .nav-link {
            color: white !important;
        }
        .navbar-nav .nav-link:hover {
            color: #ddd !important;
        }
        .dropdown-menu {
            margin-top: 0;
        }
        .navbar-brand img {
            width: 50px;
            margin-left: 15px;
            margin-top: -6px;
            margin-right: 15px;
        }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-blue navbar-dark">
        <a class="navbar-brand" href="{{ route('home') }}">
            <img class="image" src="{{ asset('image/logosumi.png') }}" alt="Logo">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><b>Home</b></a>
                </li>
                
                <!-- Master Data -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="masterDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b>Master Data</b>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="masterDataDropdown">
                        @if(session('role') == 'admin')
                        <li><a class="dropdown-item" href="{{ route('masterdata.index') }}">Invoice Data</a></li>
                        <li><a class="dropdown-item" href="{{ route('masterreceipt.index') }}">Receipt Data</a></li>
                        <li><a class="dropdown-item" href="{{ route('masteruser.index') }}">Master User</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.tasks') }}">Tugas Assigned (Admin)</a></li>
                        @else
                        <li><a class="dropdown-item" href="{{ route('masterdata.index') }}">Master Data</a></li>
                        <li><a class="dropdown-item" href="{{ route('tugas.index') }}">Daftar Tugas (Driver)</a></li>
                        @endif
                    </ul>
                </li>

                <!-- Pallet Data -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="palletDataDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b>Pallet Data</b>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="palletDataDropdown">
                        <li><a class="dropdown-item" href="{{ route('datalist.index') }}">Data List</a></li>
                        <li><a class="dropdown-item" href="{{ route('reporting.index') }}">Reporting</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('recordeddata.index') }}">Recorded Data</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('boxerror.index') }}">Box Error</a></li>
                        <li><a class="dropdown-item" href="{{ route('palleterror.index') }}">Pallet Error</a></li>
                    </ul>
                </li>

                <!-- Sketch -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="sketchDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b>Sketch</b>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="sketchDropdown">
                        <li><a class="dropdown-item" href="{{ route('sketch.show', ['lot' => '7']) }}">LOT 7</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('sketch.show', ['lot' => '206']) }}">LOT 206</a></li>
                        <li><a class="dropdown-item" href="{{ route('sketch.show', ['lot' => 'TURUNAN206']) }}">TURUNAN 206</a></li>
                        <li><a class="dropdown-item" href="{{ route('sketch.show', ['lot' => 'REPACK']) }}">REPACK</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('sketch.show', ['lot' => '244']) }}">LOT 244</a></li>
                        <li><a class="dropdown-item" href="{{ route('sketch.show', ['lot' => '245']) }}">LOT 245</a></li>
                    </ul>
                </li>

                <!-- Action (Only for sketch pages) -->
                @if(request()->is('sketch/*'))
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="actionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b>Action</b>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="actionDropdown">
                        <li><a class="dropdown-item" href="#" onclick="window.print()">Print Sketch</a></li>
                        <li><a class="dropdown-item" href="#">Export Excel</a></li>
                        @if(session('role') == 'admin')
                        <li><a class="dropdown-item" href="#">Comparable Excel Report</a></li>
                        @endif
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="recordData('{{ request()->route('lot') ?? request()->lot }}'); return false;">Record</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Color Mode</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Delete Unused Invoice</a></li>
                        @if(session('role') == 'admin')
                        <li><a class="dropdown-item" href="#">Check</a></li>
                        <li><a class="dropdown-item" href="#">Erase Check</a></li>
                        @endif
                    </ul>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link pt-1 pb-0" href="#" onclick="recordData('{{ request()->route('lot') ?? request()->lot }}'); return false;">
                        <div class="alert alert-success py-1 mb-0" style="height: 35px; font-size: 15px;"><b>Record</b></div>
                    </a>
                </li>
                @endif
            </ul>

            <ul class="navbar-nav ms-auto">
                <!-- User Settings -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <b>User ({{ session('name') }})</b>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="#">Account</a></li>
                        <li><a class="dropdown-item" href="#">Admin Note</a></li>
                        @if(session('role') == 'admin')
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Import from Grace Sketch</a></li>
                        <li><a class="dropdown-item" href="#">Import from Report</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#">Export from SBI</a></li>
                        <li><a class="dropdown-item" href="#">Export from Grace</a></li>
                        @endif
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="{{ route('logout') }}"><b>Logout</b></a>
                </li>
            </ul>
        </div>
    </nav>

    @yield('content')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @include('partials.record_script')
    @stack('scripts')
</body>
</html>
