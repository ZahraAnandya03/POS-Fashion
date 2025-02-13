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
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="{{ asset('template/css/styles.css') }}" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.html">POS Fashion</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item text-danger bg-light" href="#">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                                Dashboard
                            </a>
                            <a class="nav-link" href="{{ route('kategori.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-bookmark"></i></div>
                                Kategori
                            </a>
                            <a class="nav-link" href="{{ route('produk.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tshirt"></i></div>
                                Produk
                            </a>
                            <a class="nav-link" href="suppliers.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                                Pemasok
                            </a>
                            <a class="nav-link" href="customers.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Pelanggan
                            </a>
                            <a class="nav-link" href="transactions.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-cash-register"></i></div>
                                Transaksi
                            </a>
                            <a class="nav-link" href="reports.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                                Laporan
                            </a>
                            <a class="nav-link" href="settings.html">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                Pengaturan
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Logged in as:</div>
                        Admin
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Dashboard</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <d<div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Produk</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">150</span>
                                        <div class="small text-white"><i class="fas fa-box"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Transaksi</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">320</span>
                                        <div class="small text-white"><i class="fas fa-receipt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Pelanggan</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">500</span>
                                        <div class="small text-white"><i class="fas fa-users"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Pemasok</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">45</span>
                                        <div class="small text-white"><i class="fas fa-truck"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </main>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="{{ asset('template/js/scripts.js') }}"></script>
    </body>
</html>
