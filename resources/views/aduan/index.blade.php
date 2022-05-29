@extends('template.app')
@section('title', 'List Aduan')

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Daftar {{ $title }}</h3>
                        @can('create-aduan')
                            <a href="{{ route($route . '.create') }}" class="btn btn-sm btn-primary float-right text-light">
                                <i class="fa fa-plus"></i>Tambah Data
                            </a>
                        @endcan
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Tiket</th>
                                    <th>Nomor Aduan</th>
                                    <th>Kategori Aduan</th>
                                    <th>Atas Nama</th>
                                    <th>Sumber Informasi</th>
                                    <th>Tanggal</th>
                                    <th>Admin Wilayah</th>
                                    <th>Wilayah</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    @canany(['create-aduan', 'delete-aduan'])
                                        <th class="text-center" width="5%">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nomor = 0;
                                @endphp
                                @forelse ($aduan as $index => $item)
                                    @php
                                        ++$nomor;
                                    @endphp
                                    <tr>
                                        <td>{{ $nomor + ($aduan->CurrentPage() - 1) * $aduan->PerPage() }}</td>
                                        <td>{{ $item->no_ticket }}</td>
                                        <td>{{ $item->no_aduan }}</td>
                                        <td>{{ $item->kategori_aduan }}</td>
                                        <td>{{ $item->atas_nama }}</td>
                                        <td>{{ $item->sumber_informasi }}</td>
                                        <td>{{ tanggal_indonesia($item->created_at) }}</td>
                                        <td>{{ ucfirst($item->user) }}</td>
                                        <td>{{ $item->wilayah }}</td>
                                        <td>{{ $item->lokasi }}</td>
                                        <td>{{ ucfirst($item->status_aduan) }}</td>
                                        @if ($item->status == 'draft')
                                            @canany(['create-aduan', 'delete-aduan'])
                                                <td class="text-center">
                                                    @can('create-aduan')
                                                        <a href="{{ route('aduan.edit', $item->slug) }}"
                                                            class="btn btn-sm btn-warning text-light">
                                                            <i class="nav-icon fas fa-edit"></i> Ubah
                                                        </a>
                                                    @endcan
                                                    @can('delete-aduan')
                                                        <form id="form-{{ $item->slug }}"
                                                            action="{{ route('aduan.destroy', $item->slug) }}" method="POST"
                                                            style="display: none;">
                                                            {{ csrf_field() }}
                                                            {{ method_field('DELETE') }}
                                                        </form>
                                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                            data-placement="top" title="Hapus"
                                                            onclick=deleteconf("{{ $item->slug }}")>
                                                            <i class="fa fa-trash"></i> Hapus
                                                        </button>
                                                    @endcan
                                                </td>
                                            @endcan
                                        @endif
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
                        {{ $aduan->appends(request()->input())->links('template.pagination') }}
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
