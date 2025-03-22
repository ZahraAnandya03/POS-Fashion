<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard - SB Admin</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

     <!-- CSS Switchery -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/switchery@0.8.2/switchery.min.css">

    <!-- JS Switchery & jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/switchery@0.8.2/switchery.min.js"></script>

    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>


    <style>
        #layoutSidenav_content {
            padding: 20px;
            background-color: #f8f9fa; /* Warna latar belakang */
        }
    </style>
</head>
<body class="sb-nav-fixed">

    <!-- Navbar -->
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <a class="navbar-brand ps-3" href="#">POS Fashion</a>
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
        
        <!-- Search Bar -->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search..." aria-label="Search..." aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <!-- User Menu -->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Activity Log</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item text-danger bg-light" href="#">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Layout Sidenav -->
    <div id="layoutSidenav">

        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Menu</div>
                        @if (Auth::user()->role === 'admin')
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                            Dashboard
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin')
                        <a class="nav-link" href="{{ route('kategori.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-bookmark"></i></div>
                            Kategori
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'kasir')
                        <a class="nav-link" href="{{ route('produk.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tshirt"></i></div>
                            Produk
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin')
                        <a class="nav-link" href="{{ route('pemasok.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                            Pemasok
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin')
                        <a class="nav-link" href="{{ route('pembelian.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-cash-register"></i></div>
                            Pembelian
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin')
                        <a class="nav-link" href="{{ route('pelanggan.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                            Pelanggan
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin')
                        <a class="nav-link" href="{{ route('pengajuan.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                            Pengajuan Barang
                        </a>
                        @endif
                        @if (Auth::user()->role === 'kasir')
                        <a class="nav-link" href="{{ route('kasir.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-cash-register"></i></div>
                            Kasir
                        </a>
                        @endif
                        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'kasir')
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLaporan" aria-expanded="false" aria-controls="collapseLaporan">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                            Laporan
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLaporan" aria-labelledby="headingLaporan" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="{{ route('penjualan.index') }}">
                                    <i class="fas fa-chart-line me-2"></i> Laporan Penjualan
                                </a>
                                <a class="nav-link" href="{{ route('laporan.laporan_barang') }}">
                                    <i class="fas fa-box-open me-2"></i> Laporan Data Barang
                                </a>
                                <a class="nav-link" href="{{ route('laporan.keuntungan') }}">
                                    <i class="fas fa-box-open me-2"></i> Laporan Keuntungan 
                                </a>
                            </nav>
                        </div>  
                        @endif 
                        @if (Auth::user()->role === 'admin')                                       
                        <a class="nav-link" href="{{ route('user.index') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                            User
                        </a>
                        @endif 
                        @if (Auth::user()->role === 'admin') 
                        <a class="nav-link" href="#pengaturan">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            Pengaturan
                        </a>
                        @endif
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Logged in as:</div>
                    Admin
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main class="container-fluid p-4">
                @yield('content')
            </main>
        </div>

    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('sb-admin/js/scripts.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')

</body>
</html>
