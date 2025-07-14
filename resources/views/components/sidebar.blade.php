
@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="/">HELPDESK</a>
        </div>

        <!-- Main Menu -->
        <ul class="sidebar-menu">
            <li class="menu-header">Main Menu</li>
            <li class="{{ Request::is('home') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('home') }}"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>
        <li class="dropdown {{ Request::is('admin/users*') || Request::is('admin/roles*') || Request::is('admin/skpd*') || Request::is('admin/bidang*') || Request::is('admin/jabatan*') || Request::is('admin/kategori*') ? 'active' : '' }}">
            <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i> <span>Data Master</span></a>
            <ul class="dropdown-menu">
                <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/admin/users') }}">User</a>
                </li>
                <li class="{{ Request::is('admin/roles*') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('admin/roles') }}">Role</a>
                </li>
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
            <li class="{{ Request::is('admin/penanggungjawab*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/penanggungjawab') }}"><i class="fas fa-user-plus"></i><span>Penanggungjawab</span></a>
            </li>

            <!-- Kelola Ticket -->
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

            <!-- History -->
            <li class="menu-header">History</li>
            <li class="{{ Request::is('admin/history*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/history') }}"><i class="fas fa-stopwatch"></i><span>Riwayat Tiket</span></a>
            </li>
            
            <!-- Laporan -->
            <li class="menu-header">Laporan</li>
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
        </ul>
    </aside>
</div>
@endauth