<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HelpDesk') }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skins/reverse.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/datatables-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/default-style.css') }}">
    <style>
        body {
            background: #fff;
            overflow-x: hidden;
        }
        .navbar {
            transition: all 0.3s ease;
            background-color: #242e4c; /* Warna solid tanpa transparansi */
            padding-top: 1.1rem;
            padding-bottom: 1.1rem;
        }
        .navbar.shrink:hover {
            transition: all 0.3s ease;
            background-color: rgba(0, 0, 0, 4); /* Warna solid tanpa transparansi */
            padding-top: 1.1rem;
            padding-bottom: 1.1rem;
        }
        .navbar.shrink {
            padding: 0.6rem 1rem;
            background-color: rgba(57, 66, 99, 1) /* Warna latar belakang saat menyusut */
        }
        .navbar .nav-link {
            margin: 0 8px; /* Memberikan jarak kanan dan kiri */
        }
        .navbar .nav-link:hover {
            background-color: #fff;
            color: #242e4c !important;
            border-radius: 6px;
        }
        .navbar .nav-item .active {
            color: white !important;
            background: rgba(0, 0, 0, 0.4); /* Warna latar belakang saat menyusut */
            border-radius: 6px;
        }
        .navbar .nav-item .active:hover {
            color: black !important;
            background: #fff; /* Warna latar belakang saat menyusut */
            border-radius: 6px; /* Opsional: membuat sudut tombol melengkung */
        }
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background-color: #1a1a2e; /* Warna lebih gelap untuk sidebar */
            z-index: 1050;
            overflow-y: auto;
            transition: all 0.3s ease;
            padding-top: 60px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Tambahkan bayangan */
        }
        .mobile-sidebar.show {
            left: 0;
        }

        .mobile-sidebar .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            color: black;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .mobile-sidebar .navbar-nav {
            padding: 0;
            margin: 0;
            list-style: none;
        }
        .mobile-sidebar .nav-item {
            margin: 10px 0;
        }

        .mobile-sidebar .nav-link {
            color: #fff; /* Warna putih untuk teks */
            font-size: 1.1rem;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .mobile-sidebar .nav-link:hover {
            color: white !important;
            background: rgba(0, 0, 0, 0.4); /* Warna latar belakang saat menyusut */
            border-radius: 6px;
        }
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            display: none;
        }
        .overlay.show {
            display: block;
        }
        .footer {
            background-color: #232d44;
            color: white;
            padding: 20px 0;
        }
        .footer .footer-left {
            font-size: 14px;
        }
        .footer .footer-right {
            font-size: 14px;
        }
        .banner-helpdesk {
            background: url('{{ asset('img/home-banner.jpg') }}') no-repeat center center;
            background-size: cover;
            height: 250px; /* Mengurangi ketinggian gambar */
            position: relative;
            animation: zoomInOut 30s infinite; /* Animasi zoom in dan zoom out */
        }
            @keyframes zoomInOut {
            0%, 100% {
                transform: scale(1); /* Awal dan akhir animasi */
            }
            50% {
                transform: scale(1.1); /* Zoom in */
            }
        }
        .fa-question {
            animation: zoomInOut 2s infinite; /* Animasi zoom in dan zoom out */
        }

        @keyframes zoomInOut {
            100%, 0% {
                transform: scale(1); /* Ukuran normal */
            }
            50% {
                transform: scale(1.2); /* Membesar */
            }
        }
    </style>
    @stack('css')
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="fa fa-headset me-2"></i><strong> HelpDesk</strong>
            </a>
            <button class="navbar-toggler" type="button" id="sidebarToggleBtn">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/pengetahuan') ? 'active' : '' }}" href="{{ url('/pengetahuan') }}">Pengetahuan Dasar</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('ticket*') ? 'active' : '' }}" href="#" id="ticketDropdown" role="button" data-toggle="dropdown">
                            Tiket
                        </a>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ url('/ticket/pending') }}">Pending</a>
                            <a class="dropdown-item" href="{{ url('/ticket/diproses') }}">Proses</a>
                            <a class="dropdown-item" href="{{ url('/ticket/disposisi') }}">Disposisi</a>
                            <a class="dropdown-item" href="{{ url('/ticket/selesai') }}">Selesai</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                        <i class="fa fa-user-circle-o me-2"></i> {{ Auth::user()->first_name }} {{ Auth::user()->last_name ?? '' }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Banner -->
    <div class="banner-helpdesk"></div>

    <!-- Mobile Sidebar -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <button class="close-btn" id="closeSidebarBtn">
            <i class="fa fa-times"></i>
        </button>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/') }}">Beranda</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/pengetahuan') }}">Pengetahuan Dasar</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/ticket/pending') }}">Ticket</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ url('/pengetahuan') }}">Pengetahuan Dasar</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="mobileUserDropdown" role="button" data-toggle="dropdown">
                    <i class="fa fa-user-circle-o me-2"></i> {{ Auth::user()->first_name }} {{ Auth::user()->last_name ?? '' }}
                </a>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Keluar</a>
                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
    <div class="overlay" id="overlay"></div>

    <!-- Main Content -->
    @yield('content')

<!-- Container for Additional Information -->
<div class="container-fluid mt-5 mb-3" style="background-color: #f7f8fa; padding: 20px 0;">
    <div class="container">
        <div class="row">
            <!-- Buku Panduan Section -->
            <div class="col-md-4">
                <h3 class="push-bit text-dark"><strong>Buku Panduan</strong></h3>
                <ul class="fa-ul ul-breath push">
                    <li>
                        <i class="fa fa-check fa-li text-success"></i> 
                        <a href="https://helpdesk.riau.go.id/artikel/7" class="text-dark font-weight-bold">Manual Book User</a>
                        <p class="text-muted"><small>2 tahun yang lalu by <strong>NOFIRA ISMAYANI, S.Kom</strong></small></p>
                    </li>
                </ul>
            </div>

            <!-- Jaringan Section -->
            <div class="col-md-4">
                <h3 class="push-bit text-dark"><strong>Jaringan</strong></h3>
                <ul class="fa-ul ul-breath push">
                    <li>
                        <i class="fa fa-check fa-li text-success"></i> 
                        <a href="https://helpdesk.riau.go.id/artikel/6" class="text-dark font-weight-bold">Merubah konfigurasi DNS pada DHCP Server di Router Mikrotik</a>
                        <p class="text-muted"><small>7 tahun yang lalu by <strong>TRI JULIAN INDRA, S.Kom</strong></small></p>
                    </li>
                    <li>
                        <i class="fa fa-check fa-li text-success"></i> 
                        <a href="https://helpdesk.riau.go.id/artikel/5" class="text-dark font-weight-bold">Pengaturan Notifikasi Helpdesk Ke Telegram</a>
                        <p class="text-muted"><small>7 tahun yang lalu by <strong>RIADY HANAFI, S.T</strong></small></p>
                    </li>
                </ul>
            </div>

            <!-- Sidebar Section -->
            <div class="col-md-4">
                <aside class="sidebar site-block">
                    <div class="sidebar-block">
                        <a href="https://helpdesk.riau.go.id/pengetahuan" class="text-decoration-none text-dark">
                            <h4 class="site-heading"><strong>Semua</strong> Kategori (5)</h4>
                        </a>
                        <ul class="list-unstyled ul-breath">
                            <li><a href="https://helpdesk.riau.go.id/pengetahuan/1" class="badge badge-secondary">Mantra (1)</a></li>
                            <li><a href="https://helpdesk.riau.go.id/pengetahuan/2" class="badge badge-secondary">Domain Dan Hosting (1)</a></li>
                            <li><a href="https://helpdesk.riau.go.id/pengetahuan/3" class="badge badge-secondary">Email Dan Drive (1)</a></li>
                            <li><a href="https://helpdesk.riau.go.id/pengetahuan/4" class="badge badge-secondary">Jaringan (3)</a></li>
                            <li><a href="https://helpdesk.riau.go.id/pengetahuan/5" class="badge badge-secondary">Buku Panduan (1)</a></li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
        

        <div class="row mt-4">
            <!-- Contact Information -->
            <div class="col-md-12 text-center">
                <h5 class="text-dark"><strong>Hubungi Kami</strong></h5>
                <p>
                    <i class="fa fa-phone text-success"></i> (0761)-45505 | 
                    <i class="fa fa-envelope text-success"></i> diskominfotik@riau.go.id
                </p>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <footer class="footer text-center">
        <div class="container">
            <div class="row">
                <div class="col-md-12 footer-center">
                    <p>&copy; 2025 Dinas Komunikasi, Informatika dan Statistik Provinsi Riau</p>
                </div>
            </div>
        </div>
    </footer>

    @yield('scripts')
    
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            $('#sidebarToggleBtn').click(function() {
                $('#mobileSidebar').addClass('show');
                $('#overlay').addClass('show');
            });
            $('#closeSidebarBtn, #overlay').click(function() {
                $('#mobileSidebar').removeClass('show');
                $('#overlay').removeClass('show');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $(window).on("scroll", function() {
                if ($(this).scrollTop() > 50) {
                    $(".navbar").addClass("shrink");
                } else {
                    $(".navbar").removeClass("shrink");
                }
            });
        });
    </script>
@stack('scripts')
</body>
</html>