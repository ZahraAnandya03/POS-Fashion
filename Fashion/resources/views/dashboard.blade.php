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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/switchery/switchery.css">
        <script src="https://cdn.jsdelivr.net/npm/switchery/switchery.min.js"></script>

    </head>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.html">POS Fashion</a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#">
                <i class="fas fa-bars"></i>
            </button>
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#">Settings</a></li>
                        <li><a class="dropdown-item" href="#">Activity Log</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger bg-light">
                                    Logout
                                </button>
                            </form>
                        </li>                        
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
                            @if (Auth::user()->role === 'admin' || Auth::user()->role === 'kasir')
                            <a class="nav-link" href="{{ route('produk.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-tshirt"></i></div>
                                Produk
                            </a>
                            @endif
                            <a class="nav-link" href="{{ route('pemasok.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-truck"></i></div>
                                Pemasok
                            </a>
                            <a class="nav-link" href="{{ route('pembelian.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-cash-register"></i></div>
                                Pembelian
                            </a>
                            <a class="nav-link" href="{{ route('pelanggan.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Pelanggan
                            </a>
                            <a class="nav-link" href="{{ route('pengajuan.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-clipboard-list"></i></div>
                                Pengajuan Barang
                            </a>
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
                            @endif
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
                                                        
                            <a class="nav-link" href="{{ route('user.index') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div>
                                User
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

                        <!-- Bagian Card Data -->
                        <div class="row">
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Produk</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">{{ $jumlahProduk }}</span>
                                        <div class="small text-white"><i class="fas fa-box"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Transaksi</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">{{ $jumlahPenjualan }}</span>
                                        <div class="small text-white"><i class="fas fa-receipt"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Pelanggan</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">{{ $jumlahPelanggan }}</span>
                                        <div class="small text-white"><i class="fas fa-users"></i></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Pemasok</div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <span class="small text-white">{{ $jumlahPemasok }}</span>
                                        <div class="small text-white"><i class="fas fa-truck"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bagian Chart Penjualan Perbulan -->
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Grafik Penjualan Perhari
                                    </div>
                                    <div class="card-body">
                                        <canvas id="penjualanChart"></canvas>
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
        <script>
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            @endif
        </script>
        <div id="penjualanChart" style="width: 600px; height: 600px;"></div>
        {{-- <canvas id="penjualanChart"></canvas> --}}
        <script>
           document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('penjualanChart').getContext('2d');
    
    const labels = {!! json_encode($hariPenjualan) !!};
    const dataPenjualan = {!! json_encode($jumlahPenjualanHarian) !!};
    const dataPembelian = {!! json_encode($totalTransaksi) !!}; // Data pembelian terpisah

    // Cari nilai maksimum dari kedua dataset
    const maxValue = Math.max(...dataPenjualan, ...dataPembelian);
    let stepSize = 5000; 
    new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [
            {
                label: 'Total Penjualan',
                data: dataPenjualan,
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y1' // Sumbu Y pertama
            },
            {
                label: 'Total Transaksi',
                data: dataPembelian,
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                yAxisID: 'y2' // Sumbu Y kedua
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                ticks: {
                    maxRotation: 45,
                    minRotation: 0
                }
            },
            y1: {
                type: 'linear',
                position: 'left',
                beginAtZero: true,
                ticks: {
                    stepSize: stepSize,
                    suggestedMax: maxValue + stepSize,
                    callback: function(value) {
                        return new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            },
            y2: {
                type: 'linear',
                position: 'right',
                beginAtZero: true,
                grid: {
                    drawOnChartArea: false // Mencegah grid Y2 bertumpuk dengan Y1
                },
                ticks: {
                    stepSize: stepSize,
                    suggestedMax: maxValue + stepSize,
                    callback: function(value) {
                        return new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) label += ': ';
                        if (context.parsed.y !== null) {
                            label += new Intl.NumberFormat('id-ID').format(context.parsed.y);
                        }
                        return label;
                    }
                }
            }
        }
    }
});
});


    </script>        
</body>
</html> 