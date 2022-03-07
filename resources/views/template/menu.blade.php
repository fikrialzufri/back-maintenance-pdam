<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('home') }}">
            <div class="logo-img">
                <img height="30" src="{{ asset('img/logo_white.png') }}" class="header-brand-img" title="RADMIN">
            </div>
        </a>
        <div class="sidebar-action"><i class="ik ik-arrow-left-circle"></i></div>
        <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
    </div>

    @php
        $segment1 = request()->segment(1);
        $segment2 = request()->segment(2);
    @endphp

    <div class="sidebar-content">
        <div class="nav-container">
            <nav id="main-menu-navigation" class="navigation-main">
                <div class="nav-item {{ $segment1 == 'home' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="ik ik-bar-chart-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
                @can('view-satuan', 'view-kategori', 'view-jenis', 'view-ukuran', 'view-kategori-harga-jual')
                    <div class="nav-lavel">{{ __('Master Data') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'satuan' || $segment1 == 'navigation' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-box"></i><span>{{ __('Item') }}</span></a>
                        <div class="submenu-content">
                            @can('view-satuan')
                                <a href="{{ route('satuan.index') }}"
                                    class="menu-item {{ Request::is('satuan') ? 'active' : '' }}">
                                    Satuan
                                </a>
                            @endcan
                            @can('view-kategori')
                                <a href="{{ route('kategori.index') }}"
                                    class="menu-item {{ Request::is('kategori') ? 'active' : '' }}">
                                    Kategori
                                </a>
                            @endcan
                            @can('view-jenis')
                                <a href="{{ route('jenis.index') }}"
                                    class="menu-item {{ Request::is('jenis') ? 'active' : '' }}">
                                    Jenis
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcan
                @can('view-user', 'view-roles')
                    <div class="nav-lavel">{{ __('User') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'user' || $segment1 == 'navigation' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user dropdown-icon"></i><span>{{ __('Pengguna') }}</span></a>
                        <div class="submenu-content">
                            @can('view-user')
                                <a href="{{ route('user.index') }}"
                                    class="menu-item {{ Request::is('user') ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            @endcan
                            @can('view-roles')
                                <a href="{{ route('role.index') }}"
                                    class="menu-item {{ Request::is('roles') ? 'active' : '' }}">
                                    Hak Akses
                                </a>
                            @endcan
                            @role('superadmin')
                                <a href="{{ route('task.index') }}"
                                    class="menu-item {{ Request::is('task') ? 'active' : '' }}">
                                    Task
                                </a>
                            @endrole
                        </div>
                    </div>
                @endcan
            </nav>
        </div>
    </div>
</div>
