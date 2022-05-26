<div class="app-sidebar colored">
    <div class="sidebar-header">
        <a class="header-brand" href="{{ route('home') }}">
            <div class="logo-img">
                <img height="30" src="{{ asset('img/logopdam.png') }}" class="header-brand-img" title="PDAM Samarinda">
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
                <div class="nav-item {{ $segment1 == '' ? 'active' : '' }}">
                    <a href="{{ route('home') }}">
                        <i class="ik ik-bar-chart-2"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </div>
                @canany('view-satuan', 'view-kategori', 'view-jenis', 'view-item')
                    <div class="nav-lavel">{{ __('Master Data') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'satuan' || $segment1 == 'jenis-aduan' || $segment1 == 'item' || $segment1 == 'kategori' || $segment1 == 'jenis' ? 'active open' : '' }} has-sub">
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
                            @can('view-jenis-aduan')
                                <a href="{{ route('jenis_aduan.index') }}"
                                    class="menu-item {{ $segment1 == 'jenis-aduan' ? 'active' : '' }}">
                                    Jenis Aduan
                                </a>
                            @endcan
                        </div>
                    </div>
                @endcan
                @can('view-aduan')
                    <div class="nav-lavel">{{ __('Pengaduan') }} </div>
                    <div class="nav-item {{ $segment1 == 'aduan' ? 'active' : '' }}">
                        <a href="{{ route('aduan.index') }}">
                            <i class="ik ik-voicemail"></i>
                            <span>{{ __('Aduan') }}</span>
                        </a>
                    </div>
                @endcan
                @can('view-penunjukan-pekerjaan')
                    <div class="nav-item {{ $segment1 == 'penunjukan-pekerjaan' ? 'active' : '' }}">
                        <a href="{{ route('penunjukan_pekerjaan.index') }}">
                            <i class="ik ik-voicemail"></i>
                            <span>{{ __('Pekerjaan') }}</span>
                        </a>
                    </div>
                @endcan
                {{-- @can('view-pelaksanaan-pekerjaan')
                    <div class="nav-item {{ $segment1 == 'pelaksanaan-pekerjaan' ? 'active' : '' }}">
                        <a href="{{ route('pelaksanaan-pekerjaan.index') }}">
                            <i class="ik ik-voicemail"></i>
                            <span>{{ __('Pelaksanaan Pekerjaan') }}</span>
                        </a>
                    </div>
                @endcan --}}
                @can('view-tagihan')
                    <div class="nav-item {{ $segment1 == 'tagihan' ? 'active' : '' }}">
                        <a href="{{ route('tagihan.index') }}">
                            <i class="ik ik-voicemail"></i>
                            <span>{{ __('Tagihan') }}</span>
                        </a>
                    </div>
                @endcan
                @canany(['view-karyawan', 'view-jabatan', 'view-divisi', 'view-wilayah', 'view-departemen'])
                    <div class="nav-lavel">{{ __('Karyawan') }} </div>
                    <div
                        class="nav-item {{ $segment1 == 'jabatan' || $segment1 == 'karyawan' || $segment1 == 'divisi' || $segment1 == 'wilayah' || $segment1 == 'departemen' || $segment1 == 'divisi' ? 'active open' : '' }} has-sub">
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
                            @can('view-divisi')
                                <a href="{{ route('divisi.index') }}"
                                    class="menu-item {{ $segment1 == 'divisi' ? 'active' : '' }}">
                                    Divisi
                                </a>
                            @endcan
                            @can('view-wilayah')
                                <a href="{{ route('wilayah.index') }}"
                                    class="menu-item {{ $segment1 == 'wilayah' ? 'active' : '' }}">
                                    Wilayah
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
                @can('view-rekanan')
                    <div class="nav-item  {{ $segment1 == 'rekanan' ? 'active' : '' }}">
                        <a href="{{ route('rekanan.index') }}">
                            <i class="ik ik-briefcase dropdown-icon"></i>
                            <span>Rekanan</span>
                        </a>
                    </div>
                @endcan
                {{-- @can('view-setting')
                    <div class="nav-item  {{ $segment1 == 'setting' ? 'active' : '' }}">
                        <a href="{{ route('setting.index') }}">
                            <i class="fa fa-cog dropdown-icon"></i>
                            <span>Setting</span>
                        </a>
                    </div>
                @endcan --}}
                @canany(['view-user', 'view-roles'])
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
