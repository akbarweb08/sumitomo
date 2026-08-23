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
            width: 100%;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .bg-blue {
            background-color: #1A314B !important;
        }
        
        /* Standalone Sidebar CSS */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background-color: #1A314B;
            color: #fff;
            transition: all 0.3s ease;
            z-index: 1050;
            overflow-y: auto;
        }
        .sidebar.closed {
            transform: translateX(-100%);
        }
        .main-content {
            margin-left: 250px;
            transition: all 0.3s ease;
            width: calc(100% - 250px);
            overflow-x: auto;
            min-height: 100vh;
            background-color: #f4f6f9;
        }
        .main-content.expanded {
            margin-left: 0;
            width: 100%;
        }
        .sidebar-header {
            padding: 15px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-menu {
            padding: 0;
            list-style: none;
            margin-top: 10px;
        }
        .sidebar-menu li a {
            color: #cfd8dc;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar-menu li a:hover {
            background-color: rgba(255,255,255,0.1);
            color: white;
        }
        .top-header {
            background-color: #fff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .toggle-btn {
            background: none;
            border: 1px solid #ddd;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 20px;
            cursor: pointer;
        }
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img class="image" src="{{ asset('image/logosumi.png') }}" style="width: 40px; margin-right: 10px;" alt="Logo">
            <b>Sumitomo WH</b>
            <button class="toggle-btn" onclick="toggleSidebar()" style="margin-left: auto; color: white; border: none; font-size: 24px;">&times;</button>
        </div>
    <ul class="sidebar-menu">
      <li><a href="{{ route('home') }}">Home</a></li>
      <li><a href="{{ route('masterdata.index') }}">Master Data</a></li>
      <li><a href="{{ route('masteruser.index') }}">Master User</a></li>
      <li><a href="{{ route('datalist.index') }}">Pallet Data</a></li>
      @if(session('role') == 'admin')
      <li><a href="{{ route('admin.tasks') }}">Tugas Assigned</a></li>
      @else
      <li><a href="{{ route('tugas.index') }}">Tugas</a></li>
      @endif
      <li>
        <a data-bs-toggle="collapse" href="#sketchCollapse" role="button" aria-expanded="false" aria-controls="sketchCollapse">
          Sketch <span style="float:right;">▼</span>
        </a>
        <div class="collapse show" id="sketchCollapse">
          <ul style="list-style:none; padding-left: 20px; margin-top: 10px;">
            <li><a href="{{ route('sketch.show', ['lot' => '7']) }}" @if(isset($lotNumber) && $lotNumber == '7') style="color:white; font-weight:bold;" @endif>LOT 7</a></li>
            <li><a href="{{ route('sketch.show', ['lot' => '206']) }}" @if(isset($lotNumber) && $lotNumber == '206') style="color:white; font-weight:bold;" @endif>LOT 206</a></li>
            <li><a href="{{ route('sketch.show', ['lot' => 'TURUNAN206']) }}" @if(isset($lotNumber) && $lotNumber == 'TURUNAN206') style="color:white; font-weight:bold;" @endif>TURUNAN 206</a></li>
            <li><a href="{{ route('sketch.show', ['lot' => 'REPACK']) }}" @if(isset($lotNumber) && $lotNumber == 'REPACK') style="color:white; font-weight:bold;" @endif>REPACK</a></li>
          </ul>
        </div>
      </li>
      @if(isset($lotNumber) && (!isset($isRecord) || !$isRecord))
      <li>
        <a data-bs-toggle="collapse" href="#actionCollapse" role="button" aria-expanded="false" aria-controls="actionCollapse">
          Action <span style="float:right;">▼</span>
        </a>
        <div class="collapse" id="actionCollapse">
          <ul style="list-style:none; padding-left: 20px; margin-top: 10px;">
            <li><a href="#" onclick="window.print()">Print Sketch</a></li>
            <li><a href="/exportreport.php?LotNumber={{ $lotNumber }}">Export Excel</a></li>
            <li><a href="#" onclick="showPerLotBatchQRModal()">Batch Print QR (Excel)</a></li>
            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
            <li><a href="#" onclick="recordData('{{ $lotNumber }}'); return false;">Record</a></li>
            @if(session('role') == 'admin')
            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
            <li><a href="/checkexist.php?LotNumber={{ $lotNumber }}">Check</a></li>
            @endif
          </ul>
        </div>
      </li>
      @endif
      <li><a href="{{ route('logout') }}" style="color: #ff6b6b;">Logout</a></li>
    </ul>
    </div>

    <!-- Main Content Wrapper -->
    <div class="main-content" id="mainContent">
        <!-- Top Header inside Main Content -->
        <div class="top-header">
            <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
            <div class="ms-auto">
                <span>Welcome, <b>{{ session('name') }}</b></span>
            </div>
        </div>
        
        <div class="content-wrapper p-3">
            @yield('content')
        </div>
    </div>

    @stack('modals')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const backdrop = document.getElementById('sidebarBackdrop');
            
            if (window.innerWidth <= 991) {
                sidebar.classList.toggle('open');
                backdrop.classList.toggle('show');
            } else {
                sidebar.classList.toggle('closed');
                mainContent.classList.toggle('expanded');
            }
        }
    </script>
    @include('partials.record_script')
    @stack('scripts')
</body>
</html>
