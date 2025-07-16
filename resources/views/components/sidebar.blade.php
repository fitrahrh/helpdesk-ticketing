
@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="/">HELPDESK</a>
        </div>

            <!-- Main Menu -->
            <ul class="sidebar-menu">
            @if(Auth::check() && Auth::user()->hasPermission('dashboard'))
            <li class="menu-header">Main Menu</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
            @endif

            <li class="dropdown {{ Request::is('admin/users*') || Request::is('admin/roles*') || Request::is('admin/penanggungjawab*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-user"></i> <span>Pengguna & Akses</span></a>
                <ul class="dropdown-menu">
                    <!-- User -->
                    @if(Auth::check() && Auth::user()->hasPermission('kelola_user'))
                    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/admin/users') }}">Kelola User</a>
                    </li>
                    @endif  

                    <!-- Roles & Hak Akses -->
                    @if(Auth::check() && Auth::user()->hasPermission('roles_dan_hak_akses'))
                    <li class="{{ Request::is('admin/roles*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/roles') }}">Role dan Hak Akses</a>
                    </li>
                    @endif  
                    <!-- Kelola Penanggungjawab -->
                    @if(Auth::check() && Auth::user()->hasPermission('kelola_penanggungjawab'))
                    <li class="{{ Request::is('admin/penanggungjawab*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/penanggungjawab') }}">Penanggung Jawab</a>
                    </li>
                    @endif  
                </ul>
            </li>

            @if(Auth::check() && Auth::user()->hasPermission('data_master'))
            <li class="dropdown {{ Request::is('admin/skpd*') || Request::is('admin/bidang*') || Request::is('admin/jabatan*') || Request::is('admin/kategori*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i> <span>Data Master</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/skpd*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/skpd') }}">SKPD</a>
                    </li>
                    <li class="{{ Request::is('admin/bidang*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/bidang') }}">Bidang</a>
                    </li>
                    <li class="{{ Request::is('admin/jabatan*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/jabatan') }}">Jabatan</a>
                    </li>
                    <li class="{{ Request::is('admin/kategori*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/kategori') }}">Kategori</a>
                    </li>               
                </ul>
            </li>
            @endif

            <!-- Kelola Ticket -->
            @if(Auth::check() && Auth::user()->hasPermission('kelola_menu_tiket'))
            <li class="menu-header">Ticket</li>
            <li class="{{ Request::is('admin/baru*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/baru') }}">
                    <i class="fas fa-envelope"></i>
                    <span>Baru</span>
                    <span class="ticket-circle badge-baru">{{ $ticketCounts['baru'] ?? 0 }}</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/diproses*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/diproses') }}">
                    <i class="fas fa-spinner"></i>
                    <span>Diproses</span>
                    <span class="ticket-circle badge-diproses">{{ $ticketCounts['diproses'] ?? 0 }}</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/disposisi*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/disposisi') }}">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Disposisi</span>
                    <span class="ticket-circle badge-disposisi">{{ $ticketCounts['disposisi'] ?? 0 }}</span>
                </a>
            </li>
            <li class="{{ Request::is('admin/selesai*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/selesai') }}">
                    <i class="fas fa-check"></i>
                    <span>Selesai</span>
                    <span class="ticket-circle badge-selesai">{{ $ticketCounts['selesai'] ?? 0 }}</span>
                </a>
            </li>
            @endif
            <!-- History -->

            @if(Auth::check() && Auth::user()->hasPermission('riwayat_tiket'))
            <li class="menu-header">History</li>
            <li class="{{ Request::is('admin/history*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/history') }}"><i class="fas fa-history"></i><span>Riwayat Tiket</span></a>
            </li>
            @endif

            <!-- Laporan -->
            @if(Auth::check() && Auth::user()->hasPermission('laporan'))
            <li class="menu-header">Report</li>
            <li class="dropdown {{ Request::is('admin/harian*') || Request::is('admin/bulanan*') || Request::is('admin/berjangka*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fas fa-file"></i> <span>Laporan</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/harian*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/admin/harian') }}">Harian</a>
                    </li>
                    <li class="{{ Request::is('admin/bulanan*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/bulanan') }}">Bulanan</a>
                    </li>
                    <li class="{{ Request::is('admin/berjangka*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/berjangka') }}">Berjangka</a>
                    </li>          
                </ul>
            </li>
            @endif
        </ul>
    </aside>
</div>
@endauth