<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('home') }}">
            <div class="logo-img">
                <img height="30" src="{{ asset('img/jordan-logo.png') }}" class="header-brand-img" title="Jordan">
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
                @can('view-satuan', 'view-kategori', 'view-jenis', 'view-item')
                    <div class="nav-lavel">{{ __('Master Data') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'satuan' || $segment1 == 'item' || $segment1 == 'kategori' || $segment1 == 'jenis'? 'active open': '' }} has-sub">
                        <a href="#"><i class="ik ik-box"></i><span>{{ __('Item') }}</span></a>
                        <div class="submenu-content">
                            @can('view-item')
                                <a href="{{ route('item.index') }}"
                                    class="menu-item {{ $segment1 == 'item' ? 'active' : '' }}">
                                    Item
                                </a>
                            @endcan
                            @can('view-satuan')
                                <a href="{{ route('satuan.index') }}"
                                    class="menu-item {{ $segment1 == 'satuan' ? 'active' : '' }}">
                                    Satuan
                                </a>
                            @endcan
                            @can('view-jenis')
                                <a href="{{ route('jenis.index') }}"
                                    class="menu-item {{ $segment1 == 'jenis' ? 'active' : '' }}">
                                    Jenis
                                </a>
                            @endcan
                            @can('view-kategori')
                                <a href="{{ route('kategori.index') }}"
                                    class="menu-item {{ $segment1 == 'kategori' ? 'active' : '' }}">
                                    Kategori
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcan
                @can('view-karyawan')
                    <div class="nav-lavel">{{ __('Karyawan') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'jabatan' || $segment1 == 'karyawan' || $segment1 == 'departemen' || $segment1 == 'divisi'? 'active open': '' }} has-sub">
                        <a href="#"><i class="fa fa-users dropdown-icon"></i><span>{{ __('Karyawan') }}</span></a>
                        <div class="submenu-content">

                            @can('view-karyawan')
                                <a href="{{ route('karyawan.index') }}"
                                    class="menu-item {{ $segment1 == 'karyawan' ? 'active' : '' }} ? 'active' : '' }}">
                                    Karyawan
                                </a>
                            @endcan
                            @can('view-jabatan')
                                <a href="{{ route('jabatan.index') }}"
                                    class="menu-item {{ $segment1 == 'jabatan' ? 'active' : '' }}">
                                    Jabatan
                                </a>
                            @endcan
                            @can('view-departemen')
                                <a href="{{ route('departemen.index') }}"
                                    class="menu-item {{ $segment1 == 'departemen' ? 'active' : '' }}">
                                    Departemen
                                </a>
                            @endcan

                        </div>
                    </div>
                @endcan
                @can('view-user', 'view-roles')
                    <div class="nav-lavel">{{ __('User') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'user' || $segment1 == 'role' || $segment1 == 'task' ? 'active open' : '' }} has-sub">
                        <a href="#"><i class="ik ik-user dropdown-icon"></i><span>{{ __('Pengguna') }}</span></a>
                        <div class="submenu-content">
                            @can('view-user')
                                <a href="{{ route('user.index') }}"
                                    class="menu-item {{ $segment1 == 'user' ? 'active' : '' }}">
                                    Pengguna
                                </a>
                            @endcan
                            @can('view-roles')
                                <a href="{{ route('role.index') }}"
                                    class="menu-item {{ $segment1 == 'role' ? 'active' : '' }}">
                                    Hak Akses
                                </a>
                            @endcan
                            @role('superadmin')
                                <a href="{{ route('task.index') }}"
                                    class="menu-item {{ $segment1 == 'task' ? 'active' : '' }}">
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
