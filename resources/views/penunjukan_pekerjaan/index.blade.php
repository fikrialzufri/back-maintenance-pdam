@extends('template.app')
@section('title', 'List Penunjukan Pekerjaan')

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Daftar {{ $title }}</h3>

                        <div>
                            {{-- @if ($rekanan_id == null)
                                <a href="{{ route($route . '.create') }}"
                                    class="btn btn-sm btn-primary float-right text-light">
                                    <i class="fa fa-plus"></i>Tambah Data
                                </a>
                                <a href="{{ route($route . '.upload') }}"
                                    class="btn btn-sm btn-warning float-right text-light mr-5">
                                    <i class="fa fa-file"></i> Upload
                                </a>
                            @endif --}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor SPK</th>
                                    <th>Nomor Aduan</th>
                                    <th>Nomor Tiket</th>
                                    <th>Rekanan</th>
                                    <th>Tanggal</th>
                                    <th>Pelapor</th>
                                    <th>Admin</th>
                                    <th width="20%">Lokasi</th>
                                    <th>Status</th>
                                    <th class="text-center" width="10%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nomor = 0;
                                @endphp
                                @forelse ($penunjukan as $index => $item)
                                    @php
                                        ++$nomor;
                                    @endphp
                                    <tr class="{{ $item->keterangan_barang != null ? 'bg-danger' : '' }} ">
                                        <td>{{ $nomor + ($penunjukan->CurrentPage() - 1) * $penunjukan->PerPage() }}
                                        </td>
                                        <td>{{ $item->no_spk }}</td>
                                        <td>{{ $item->no_aduan }}</td>
                                        <td>{{ $item->no_ticket }}</td>
                                        <td>{{ $item->rekanan }}</td>
                                        <td>{{ tanggal_indonesia($item->created_at) }}</td>
                                        <td>{{ $item->atas_nama }}</td>
                                        <td>{{ ucfirst($item->user) }}</td>
                                        <td>{{ $item->lokasi }}</td>
                                        <td>{{ ucfirst($item->status_aduan) }}</td>

                                        <td class="text-center">
                                            <a href="{{ route('penunjukan_pekerjaan.show', $item->slug) }}"
                                                class="btn btn-sm {{ $item->btn }}   text-light">
                                                @if (auth()->user()->hasRole('rekanan'))
                                                    <i class="nav-icon fa fa-search"></i> Detail
                                                @elseif (auth()->user()->hasRole('staf-pengawas'))
                                                    @if ($item->status_aduan == 'Selesai dikerjakan')
                                                        <i class="nav-icon fas fa-eye"></i> Koreksi
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('staf-perencanaan'))
                                                    @if ($item->status_aduan == 'Dikoreksi pengawas')
                                                        <i class="nav-icon fas fa-eye"></i> Koreksi
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('admin-asisten-manager'))
                                                    @if ($item->status_aduan == 'Belum ditunjuk')
                                                        <i class="nav-icon fas fa-eye"></i> Proses
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @else
                                                    <i class="nav-icon fa fa-search"></i> Detail
                                                @endif
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11">Data Aduan tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $penunjukan->appends(request()->input())->links('template.pagination') }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
@stop
