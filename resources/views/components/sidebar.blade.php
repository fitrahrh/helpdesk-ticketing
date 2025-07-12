
@auth
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
        <a href="">HELPDESK</a>
        </div>

        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
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

            <li class="{{ Request::is('admin/history*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/history') }}"><i class="fas fa-stopwatch"></i><span>Riwayat Tiket</span></a>
            </li>

            <!-- Kelola Ticket -->
            <li class="menu-header">Ticket</li>
            <li class="{{ Request::is('admin/baru*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/baru') }}"><i class="fas fa-envelope"></i><span>Baru</span></a>
            </li>
            <li class="{{ Request::is('admin/diproses*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/diproses') }}"><i class="fas fa-spinner"></i><span>Diproses</span></a>
            </li>
            <li class="{{ Request::is('admin/disposisi*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/disposisi') }}"><i class="fas fa-exchange-alt"></i><span>Disposisi</span></a>
            </li>
            <li class="{{ Request::is('admin/selesai*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ url('admin/selesai') }}"><i class="fas fa-check"></i><span>Selesai</span></a>
            </li>
            
            <!-- Laporan -->
            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('') ? 'active' : '' }}">
                <a class="nav-link" href="#"><i class="fas fa-file"></i><span>Laporan</span></a>
        </ul>
    </aside>
</div>
@endauth