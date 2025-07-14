<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Title -->
    <title>HelpDesk</title>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.googleapis.com/" />
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Select2 CSS  -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">


    <!-- Custom CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/components.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/skins/reverse.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/datatables-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('library/datatables/media/css/jquery.dataTables.min.css') }}">
    <!-- Additional CSS (if any) -->
    <style>
    /* Custom circular badge style for sidebar ticket counts */
    .ticket-circle {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        width: 24px;
        height: 24px;
        min-width: 24px;
        border-radius: 50%;
        font-size: 12px;
        font-weight: 600;
        margin-left: auto;
        color: white;
        padding: 0;
        line-height: 1;
    }

    /* Badge colors */
    .badge-baru {
        background-color: #ffc107; /* Golden yellow */
    }

    .badge-diproses {
        background-color: #17a2b8; /* Info blue */
    }

    .badge-disposisi {
        background-color: #fc544b; /* Danger red */
    }

    .badge-selesai {
        background-color: #47c363; /* Success green */
    }

    /* Add space between icon/text and the badge */
    .sidebar-menu .nav-link {
        display: flex;
        align-items: center;
    }

    .sidebar-menu .nav-link span:first-of-type {
        flex-grow: 1;
    }

    /* Override the default style for ticket-circle spans */
    .main-sidebar .sidebar-menu li a span.ticket-circle {
        width: 24px !important; /* Force the width */
        min-width: 24px !important;
        margin-top: 0 !important; /* Reset margin */
        margin-left: auto;
        flex-shrink: 0; /* Prevent shrinking */
    }

    /* Make sure regular text spans take available width but not badge spans */
    .main-sidebar .sidebar-menu li a span:not(.ticket-circle) {
        width: auto;
        flex-grow: 1;
    }

    /* Improve display of the link container */
    .main-sidebar .sidebar-menu li a {
        display: flex;
        align-items: center;
        padding-right: 15px;
    }
    </style>

    @stack('css')

</head>
<body>
    <div id="app">
        <div class="main-wrapper">
            <!-- Header -->
            @include('components.header')

            <!-- Sidebar -->
            @include('components.sidebar')

            <!-- Main Content -->
            <div class="main-content">
                @yield('content')
            </div>

            <!-- Footer -->
            @include('components.footer')
        </div>
    </div>

    <!-- jQuery and Popper.js (required for Bootstrap 4) -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

    <!-- Bootstrap JS (Bootstrap 4) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Custom JS Libraries -->
    <script src="{{ asset('library/jquery.nicescroll/dist/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('library/moment/min/moment.min.js') }}"></script>

    <!-- Template JS Files -->
    <script src="{{ asset('js/stisla.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
    <script src="{{ asset('js/custom.js') }}"></script>

    <script src="{{ asset('library/datatables/media/js/jquery.dataTables.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Additional JS (if any) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script>
        // Custom toastr configuration to use FontAwesome icons
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "closeHtml": '<button><i class="fas fa-times"></i></button>'
        };
        
        // Override toastr success method to use FontAwesome icons
        const originalSuccessToastr = toastr.success;
        toastr.success = function(message, title, options) {
            message = '' + message;
            return originalSuccessToastr.call(this, message, title, options);
        };
        
        // Override toastr error method
        const originalErrorToastr = toastr.error;
        toastr.error = function(message, title, options) {
            message = '' + message;
            return originalErrorToastr.call(this, message, title, options);
        };
    </script>
    <script>
        @if(session('status'))
            toastr.success("{{ session('status') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif

        @if($errors->any())
            toastr.error("{{ $errors->first() }}");
        @endif
    </script>
    @stack('scripts')
</body>
</html>
