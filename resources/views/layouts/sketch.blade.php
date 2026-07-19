<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Warehouse Sketch')</title>
  <link rel="icon" type="image/png" href="{{ asset('image/login.png') }}" />
  @if(!isset($lotNumber) || $lotNumber != '7')
  <link rel="stylesheet" href="{{ asset('css/206.css') }}">
  @endif
  <link rel="stylesheet" href="{{ asset('css/bs.min.css') }}">
  <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
  <script src="{{ asset('js/jquery-3.5.1.js') }}"></script>
  <script src="{{ asset('js/sweetalert2@11.js') }}"></script>
  <script src="{{ asset('js/bs.min.js') }}"></script>
  <script src="{{ asset('js/popper.min.js') }}"></script>
  <script src="{{ asset('js/bootstrap.min.js') }}"></script>

  <style>
    .fullbody {
      zoom: 63%;
    }

    #formName {
      display: none;
    }

    @media print {
      @page {
        size: A4 landscape;
        min-zoom: 70%;
        max-zoom: 70%;
      }
      #formName {
        display: block;
      }
    }

    body {
      font-family: Arial;
      width: 100%;
    }

    .topnav {
      overflow: hidden;
      background-color: #333;
      float: right;
    }

    .topnav a {
      float: left;
      display: block;
      color: #f2f2f2;
      text-align: center;
      padding: 14px 16px;
      text-decoration: none;
    }

    .navbar {
      background-color: #1A314B;
      width: 100%;
    }

    .topnav .icon {
      display: none;
    }

    .topnav a:hover,
    .dropdown:hover .dropbtn {
      background-color: #555;
      color: white;
    }

    .dropdown-content a:hover {
      background-color: #ddd;
      color: black;
    }

    .dropdown:hover .dropdown-content {
      display: block;
    }

    @media screen and (max-width: 1500px) {
      .topnav a:not(:first-child),
      .dropdown .dropbtn {
        display: none;
      }
      .topnav a.icon {
        float: right;
        display: block;
      }
      @page {
        size: A4 landscape;
      }
      @page {
        size: 100mm 200mm landscape;
      }
      @page {
        size: 4in 6in landscape;
      }
    }

    @media screen and (max-width: 1000px) {
      .topnav.responsive {
        position: relative;
      }
      .topnav.responsive .icon {
        position: absolute;
        right: 0;
        top: 0;
      }
      .topnav.responsive a {
        float: none;
        display: block;
        text-align: left;
      }
      .topnav.responsive .dropdown {
        float: none;
      }
      .topnav.responsive .dropdown-content {
        position: relative;
      }
      .topnav.responsive .dropdown .dropbtn {
        display: block;
        width: 100%;
        text-align: left;
      }
      .flex-container {
        display: flex;
        justify-content: center;
        background-color: DodgerBlue;
      }
      .flex-container>div {
        background-color: #f1f1f1;
        width: 100px;
        margin: 10px;
        text-align: center;
        line-height: 75px;
        font-size: 30px;
      }
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
            <li><a href="{{ route('sketch.show', ['lot' => '242']) }}" @if(isset($lotNumber) && $lotNumber == '242') style="color:white; font-weight:bold;" @endif>LOT 242</a></li>
            <li><a href="{{ route('sketch.show', ['lot' => '243']) }}" @if(isset($lotNumber) && $lotNumber == '243') style="color:white; font-weight:bold;" @endif>LOT 243</a></li>
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
            @if(session('role') == 'admin')
            <li><a href="/exportcompare.php?LotNumber={{ $lotNumber }}">Comparable Excel Report</a></li>
            @endif
            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
            <li><a href="#" onclick="recordData('{{ $lotNumber }}'); return false;">Record</a></li>
            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
            <li><a href="{{ route('sketch.show', ['lot' => $lotNumber]) }}?color=1&mode=line">Color Mode</a></li>
            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.1);"></li>
            <li><a href="/actioninvoice.php?lot={{ $lotNumber }}">Delete Unused Invoice</a></li>
            @if(session('role') == 'admin')
            <li><a href="/checkexist.php?LotNumber={{ $lotNumber }}">Check</a></li>
            <li><a href="/checkexist.php?LotNumber={{ $lotNumber }}&erase=yes">Erase Check</a></li>
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
    
    <div class="fullbody" @if(isset($isRecord) && $isRecord) style="pointer-events: none;" @endif>
        @yield('content')
    </div>
  </div> <!-- End Main Content Wrapper -->

  @stack('modals')

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
  <!-- Socket.IO Client -->
  <script src="http://localhost:3000/socket.io/socket.io.js"></script>
  <script>
      if (typeof io !== 'undefined') {
          const socket = io('http://localhost:3000');
          // Fallback user ID to session or auth if available
          const userId = "{{ auth()->id() ?? session('user_id') ?? 1 }}"; 
          socket.on('driver-channel.' + userId, (data) => {
              if(data.event === 'driver.assigned') {
                  Swal.fire({
                      title: 'Tugas Baru!',
                      text: data.data.task.note,
                      icon: 'info',
                      showCancelButton: true,
                      confirmButtonText: 'Lihat Tugas',
                      cancelButtonText: 'Tutup'
                  }).then((result) => {
                      if(result.isConfirmed) {
                          window.location.href = "{{ route('tugas.index') }}";
                      }
                  });
              }
          });
      }
  </script>
  @include('partials.record_script')
  @stack('scripts')
</body>
</html>
