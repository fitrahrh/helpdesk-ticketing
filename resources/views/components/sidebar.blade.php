
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
            <li class="dropdown {{ Request::is('admin/users*') || Request::is('admin/roles*') || Request::is('skpd*') ? 'active' : '' }}">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-database"></i> <span>Data Master</span></a>
                <ul class="dropdown-menu">
                    <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('/admin/users') }}">Users</a>
                    </li>
                    <li class="{{ Request::is('admin/roles*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('admin/roles') }}">Roles</a>
                    </li>
                    <li class="{{ Request::is('skpd*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url('skpd') }}">SKPD</a>
                    </li>            
                </ul>
            </li>

            <!-- Kelola Ticket -->
            <li class="menu-header">Ticket</li>
            <li class="{{ Request::is('') ? 'active' : '' }}">
                <a class="nav-link" href="#"><i class="fas fa-user"></i><span>Disposisi</span></a>
                <a class="nav-link" href="#"><i class="fas fa-envelope"></i><span>Open</span></a>
                <a class="nav-link" href="#"><i class="fas fa-spinner"></i><span>Proses</span></a>
                <a class="nav-link" href="#"><i class="fas fa-check"></i><span>Selesai</span></a>
            </li>
            <!-- Laporan -->
            <li class="menu-header">Laporan</li>
            <li class="{{ Request::is('') ? 'active' : '' }}">
                <a class="nav-link" href="#"><i class="fas fa-file"></i><span>Laporan</span></a>
        </ul>
    </aside>
</div>
@endauth