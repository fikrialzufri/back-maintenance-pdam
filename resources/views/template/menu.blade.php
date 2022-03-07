<aside class="main-sidebar sidebar-collapse sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
        <img src="{{ asset('img/logo.png') }}" width="30%" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Dashboard</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <!-- <img src="{{ asset('template/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image"> -->
            </div>
            <div class="info">
                <a href="{{ route('user.ubah') }}" class="d-block">{{ ucfirst(Auth::user()->name) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class  with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ route('home') }}" class="nav-link {{ Request::is('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @can('view-satuan', 'view-kategori', 'view-jenis', 'view-ukuran', 'view-kategori-harga-jual')
                    <li
                        class="nav-item has-treeview
                    {{ Request::segment(1) === 'satuan' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'kategori' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'jenis' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'ukuran' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'kategori_harga_jual' ? 'menu-open' : '' }}
                    ">
                        <a href="#"
                            class="nav-link
                        {{ Request::segment(1) === 'satuan' ? 'active' : '' }}
                        {{ Request::segment(1) === 'kategori' ? 'active' : '' }}
                        {{ Request::segment(1) === 'jenis' ? 'active' : '' }}
                        {{ Request::segment(1) === 'ukuran' ? 'active' : '' }}
                        {{ Request::segment(1) === 'kategori_harga_jual' ? 'active' : '' }}
                        ">
                            <i class=" nav-icon fa fa-archive"></i>
                            <p>
                                Master Data
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('view-satuan')
                                <li class="nav-item">
                                    <a href="{{ route('satuan.index') }}"
                                        class="nav-link {{ Request::is('satuan') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-archive"></i>

                                        <p>
                                            Satuan
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('view-kategori')
                                <li class="nav-item">
                                    <a href="{{ route('kategori.index') }}"
                                        class="nav-link {{ Request::is('kategori') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-cubes"></i>
                                        <p>
                                            Kategori
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('view-jenis')
                                <li class="nav-item">
                                    <a href="{{ route('jenis.index') }}"
                                        class="nav-link {{ Request::is('jenis') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-cube"></i>
                                        <p>
                                            Jenis
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('view-merek')
                                <li class="nav-item">
                                    <a href="{{ route('merek.index') }}"
                                        class="nav-link {{ Request::is('merek') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-inbox"></i>
                                        <p>
                                            Merek
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('view-ukuran')
                                <li class="nav-item">
                                    <a href="{{ route('ukuran.index') }}"
                                        class="nav-link {{ Request::is('ukuran') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-pencil-alt"></i>
                                        <p>
                                            Ukuran
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('view-kategori-harga-jual')
                                <li class="nav-item">
                                    <a href="{{ route('kategori_harga_jual.index') }}"
                                        class="nav-link {{ Request::is('kategori_harga_jual') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-book"></i>
                                        <p>
                                            Kategori Harga Jual
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan
                @can('view-produk')
                    <li class="nav-item">
                        <a href="{{ route('produk.index') }}"
                            class="nav-link {{ Request::is('produk') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-briefcase"></i>
                            <p>
                                Produk
                            </p>
                        </a>
                    </li>
                @endcan

                @can('view-pusat', 'view-cabang', 'view-toko', 'view-gudang', 'view-supplier')
                    <li
                        class="nav-item has-treeview
                    {{ Request::segment(1) === 'pusat' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'cabang' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'toko' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'gudang' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'supplier' ? 'menu-open' : '' }}
                    ">
                        <a href="#"
                            class="nav-link
                        {{ Request::segment(1) === 'pusat' ? 'active' : '' }} {{ Request::segment(1) === 'cabang' ? 'active' : '' }} {{ Request::segment(1) === 'pusat' ? 'active' : '' }}
                            {{ Request::segment(1) === 'toko' ? 'active' : '' }}
                            {{ Request::segment(1) === 'supplier' ? 'active' : '' }}
                            {{ Request::segment(1) === 'gudang' ? 'active' : '' }} ">
                            <i class="    nav-icon fa fa-building"></i>
                            <p>
                                Perusahaan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('view-pusat')
                                <li class="nav-item">
                                    <a href="{{ route('pusat.index') }}"
                                        class="nav-link {{ Request::is('pusat') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-university"></i>
                                        <p>
                                            Pusat
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('view-cabang')
                                <li class="nav-item">
                                    <a href="{{ route('cabang.index') }}"
                                        class="nav-link {{ Request::is('cabang') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-shopping-bag"></i>
                                        <p>
                                            Cabang
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('view-gudang')
                                <li class="nav-item">
                                    <a href="{{ route('gudang.index') }}"
                                        class="nav-link {{ Request::is('gudang') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-building"></i>
                                        <p>
                                            Gudang
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('view-toko')
                                <li class="nav-item">
                                    <a href="{{ route('toko.index') }}"
                                        class="nav-link {{ Request::is('toko') ? 'active' : '' }}">
                                        <i class="nav-icon fas fa-store-alt"></i>
                                        <p>
                                            Toko
                                        </p>
                                    </a>
                                </li>
                            @endcan
                            @can('view-supplier')
                                <li class="nav-item">
                                    <a href="{{ route('supplier.index') }}"
                                        class="nav-link {{ Request::is('supplier') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-credit-card"></i>
                                        <p>
                                            Supplier
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>

                    </li>
                @endcan
                @can('view-jabatan', 'view-karyawan')
                    <li
                        class="nav-item has-treeview
                    {{ Request::segment(1) === 'jabatan' ? 'menu-open' : '' }}
                    {{ Request::segment(1) === 'karyawan' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::segment(1) === 'jabatan' ? 'active' : '' }}  {{ Request::segment(1) === 'karyawan' ? 'active' : '' }}">
                            <i class=" nav-icon fa fa-users"></i>
                            <p>
                                Karyawan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            @can('view-jabatan')
                                <li class="nav-item">
                                    <a href="{{ route('jabatan.index') }}"
                                        class="nav-link {{ Request::is('jabatan') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-address-card"></i>
                                        <p>
                                            Jabatan
                                        </p>
                                    </a>
                                </li>
                            @endcan

                            @can('view-karyawan')
                                <li class="nav-item">
                                    <a href="{{ route('karyawan.index') }}"
                                        class="nav-link {{ Request::is('karyawan') ? 'active' : '' }}">
                                        <i class="nav-icon fa fa-users"></i>
                                        <p>
                                            Karyawan
                                        </p>
                                    </a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan



                @can('view-user')

                    <li
                        class="nav-item has-treeview {{ Request::segment(1) === 'user' ? 'menu-open' : '' }} {{ Request::segment(1) === 'role' ? 'menu-open' : '' }} {{ Request::segment(1) === 'task' ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ Request::segment(1) === 'user' ? 'active' : '' }} {{ Request::segment(1) === 'role' ? 'active' : '' }}  {{ Request::segment(1) === 'task' ? 'active' : '' }}">
                            <i class=" nav-icon fas fa-table"></i>
                            <p>
                                Pengguna
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}"
                                    class="nav-link {{ Request::segment(1) === 'user' ? 'active' : '' }}">
                                    <i class="far fa-user nav-icon"></i>
                                    <p>Pengguna</p>
                                </a>
                            </li>
                        </ul>

                        @can('view-roles')
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('role.index') }}"
                                        class="nav-link {{ Request::segment(1) === 'role' ? 'active' : '' }}">
                                        <i class="fa fa-key nav-icon"></i>
                                        <p>Hak Akses</p>
                                    </a>
                                </li>
                            </ul>
                        @endcan
                        @role('superadmin')
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('task.index') }}"
                                        class="nav-link {{ Request::segment(1) === 'task' ? 'active' : '' }}">
                                        <i class="fa fa-archive nav-icon"></i>
                                        <p>Task</p>
                                    </a>
                                </li>
                            </ul>
                        @endrole
                    </li>
                @endcan

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="nav-icon fa fa-power-off"></i>
                        <p>
                            Keluar
                            <!-- <span class="right badge badge-danger">New</span> -->
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
