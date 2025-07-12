
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'HelpDesk') }}</title>
    
    <!-- CSS Libraries -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
    
    <!-- Custom Styles -->
    <style>
        /* Base Styles */
        body { 
            background: #f7f8fa; 
        }
        
        /* Navbar Styles */
        .navbar {
            height: 62px;
            padding-top: 0.6rem;
            padding-bottom: 0.6rem;
            background-color: #232d44;
        }
        
        .navbar-bg {
            margin: 0;
            height: 62px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #232d44;
            z-index: 889;
        }
        
        .nav-bg {
            background: #232d44;
        }
        
        .nav-item .nav-link {
            padding: 0.5rem 1rem;
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        .nav-item .nav-link:hover {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-item .active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 4px;
        }
        
        .dropdown-menu {
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
        }
        
        .dropdown-item:active, .dropdown-item:focus, .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #212529;
        }
        
        .dropdown-item.active {
            background-color: #232d44;
            color: #fff;
        }

        /* Mobile Navigation Styles */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background-color: #232d44;
                padding: 15px;
                border-radius: 0 0 5px 5px;
                margin-top: 0.5rem;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-toggler {
                border-color: rgba(255, 255, 255, 0.5);
            }
            
            .navbar-toggler-icon {
                background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(255, 255, 255, 0.8)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
            }
        }

        .mobile-sidebar .collapse {
            background-color: rgba(0, 0, 0, 0.15);
            overflow: visible;
        }

        /* Mobile Sidebar Navigation */
        .mobile-sidebar {
            position: fixed;
            top: 0;
            left: -280px;
            width: 280px;
            height: 100%;
            background-color: #232d44;
            z-index: 1050;
            overflow-y: auto;
            transition: all 0.3s ease;
            box-shadow: 3px 0 10px rgba(0, 0, 0, 0.2);
            padding-top: 60px;
            max-height: 100vh; /* Ensure it doesn't exceed viewport height */
        }

        .mobile-sidebar .collapse ul.navbar-nav {
            max-height: none;
            overflow: visible;
        }

        /* Make dropdown items more visible against background */
        .mobile-sidebar .dropdown-item {
            padding: 12px 20px 12px 35px;
            color: rgba(255, 255, 255, 0.9) !important; /* Increased contrast */
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        

        .mobile-sidebar.show {
            left: 0;
        }
        
        .mobile-sidebar .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            color: #fff;
            font-size: 24px;
            border: none;
            background: transparent;
        }
        
        .mobile-sidebar .navbar-nav {
            flex-direction: column;
            width: 100%;
        }
        
        .mobile-sidebar .nav-item {
            width: 100%;
        }
        
        .mobile-sidebar .nav-link {
            padding: 15px 20px;
            color: #fff !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .mobile-sidebar .dropdown-menu {
            position: static !important;
            width: 100%;
            background-color: rgba(255, 255, 255, 0.05);
            border: none;
            border-radius: 0;
            margin-top: 0;
            padding: 0;
            box-shadow: none;
        }
        
        .mobile-sidebar .dropdown-item {
            padding: 12px 20px 12px 35px;
            color: rgba(255, 255, 255, 0.8) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .mobile-sidebar .dropdown-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
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
        
        @media (min-width: 992px) {
            .mobile-sidebar, .overlay {
                display: none !important;
            }
        }

        /* Banner and Content Styles */
        .helpdesk-banner {
            margin-top: 62px;
            height: 220px;
            background: #e6f0fa url('{{ asset('img/home-banner.jpg') }}') center center/cover no-repeat;
            position: relative;
        }
        
        .helpdesk-banner .overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(255,255,255,0.2);
        }
        
        .center-action {
            min-height: 250px;
        }
        
        /* Footer Styles */
        .footer {
            background-color: #232d44;
            color: white;
            padding: 20px 0;
            margin-top: 30px;
        }

        /* Utility Classes */
        .badge {
            font-size: 13px;
            margin: 2px;
        }
        
        .fs-18 { 
            font-size: 18px; 
        }
        
        body.overflow-hidden {
            overflow: hidden;
        }

        /* Animation Styles */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease forwards;
        }

        .fade-in-delay {
            animation: fadeIn 1s ease 0.3s forwards;
            opacity: 0;
        }
        
        .rounded-circle {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .rounded-circle:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        }

        .rounded-circle:active {
            transform: scale(0.95);
            opacity: 0.8;
        }
    </style>
</head>
<body>
    <!-- Desktop Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark nav-bg shadow-sm fixed-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="/">
                <i class="fa fa-headset me-2"></i>
                <span class="fw-bold">HelpDesk</span>
            </a>

            <!-- Mobile Navbar Toggler -->
            <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggleBtn">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links (for desktop) -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->is('ticket*') ? 'active' : '' }}" href="#" id="ticketDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Tiket
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="ticketDropdown">
                            <li><a class="dropdown-item" href="{{ url('/ticket/pending') }}"><i class="fa fa-clock-o me-2"></i> Menunggu</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/diproses') }}"><i class="fa fa-spinner me-2"></i> Diproses</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/disposisi') }}"><i class="fa fa-exchange me-2"></i> Disposisi</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/selesai') }}"><i class="fa fa-check me-2"></i> Selesai</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/create') }}"><i class="fa fa-plus me-2"></i> Buat Tiket Baru</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-circle-o me-2"></i>
                            {{ Auth::user()->first_name ?? 'Pengguna' }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fa fa-user me-2"></i> Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fa fa-sign-out me-2"></i> Keluar
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Mobile Sidebar Navigation -->
    <div class="mobile-sidebar" id="mobileSidebar">
        <button class="close-btn" id="closeSidebarBtn">
            <i class="fa fa-times"></i>
        </button>
        <div class="pt-2 pb-2">
            <div class="d-flex align-items-center p-3 mb-3">
                <i class="fa fa-headset me-2 text-white"></i>
                <span class="fw-bold text-white">HelpDesk</span>
            </div>
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                        <i class="fa fa-home me-2"></i> Beranda
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->is('ticket*') ? 'active' : '' }}" href="#ticketCollapse" data-bs-toggle="collapse">
                        <i class="fa fa-ticket me-2"></i> Tiket <i class="fa fa-chevron-down float-end mt-1"></i>
                    </a>
                    <div class="collapse" id="ticketCollapse">
                        <ul class="navbar-nav">
                            <li><a class="dropdown-item" href="{{ url('/ticket/pending') }}"><i class="fa fa-clock-o me-2"></i> Menunggu</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/diproses') }}"><i class="fa fa-spinner me-2"></i> Diproses</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/disposisi') }}"><i class="fa fa-exchange me-2"></i> Disposisi</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/selesai') }}"><i class="fa fa-check me-2"></i> Selesai</a></li>
                            <li><a class="dropdown-item" href="{{ url('/ticket/create') }}"><i class="fa fa-plus me-2"></i> Buat Tiket Baru</a></li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                        <i class="fa fa-user me-2"></i> Profil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out me-2"></i> Keluar
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Overlay for Mobile Sidebar -->
    <div class="overlay" id="overlay"></div>

    <!-- Banner -->
    <div class="helpdesk-banner position-relative">
        <div class="overlay"></div>
    </div>

    <!-- Main Content -->
    @yield('banner-content')
    
    <div class="container-fluid p-0">
        <!-- Ticket Status Section -->
        <div class="container mt-4">
            <!-- Content will be rendered by child templates -->
        </div>

        <br>
        <br>

        <!-- Contact Info -->
        <div class="contact-info bg-light py-2 border-top">
            <div class="container text-center">
                <p class="mb-0">
                    <i class="fa fa-phone me-1"></i> (0761)-45505 | 
                    <i class="fa fa-envelope-o ms-2 me-1"></i> diskominfotik@riau.go.id
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center mt-0">
        <div class="container">
            Copyright © {{ date('Y') }} Dinas Komunikasi, Informatika dan Statistik Provinsi Riau
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
        // Set CSRF token for all AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $(document).ready(function() {
            // Initialize Select2
            $('.select2').select2();
            
            // Initialize Summernote
            $('.summernote').summernote({
                height: 200,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Mobile Sidebar Controls
            $("#sidebarToggleBtn").click(function(e) {
                e.preventDefault();
                $("#mobileSidebar").addClass("show");
                $("#overlay").addClass("show");
                $("body").addClass("overflow-hidden");
            });
            
            $("#closeSidebarBtn, #overlay").click(function() {
                $("#mobileSidebar").removeClass("show");
                $("#overlay").removeClass("show");
                $("body").removeClass("overflow-hidden");
            });
            
            $("#mobileSidebar .nav-link:not([data-bs-toggle])").click(function() {
                if ($(window).width() < 992) {
                    $("#mobileSidebar").removeClass("show");
                    $("#overlay").removeClass("show");
                    $("body").removeClass("overflow-hidden");
                }
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>