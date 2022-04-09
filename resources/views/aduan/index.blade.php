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
                        <a href="{{ route($route . '.create') }}" class="btn btn-sm btn-primary float-right text-light">
                            <i class="fa fa-plus"></i>Tambah Data
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nomor Tiket</th>
                                    <th>Nomor Aduan</th>
                                    <th>Atas Nama</th>
                                    <th>Sumber Informasi</th>
                                    <th>Lokasi</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th class="text-center" width="20%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($aduan as $index => $item)
                                    <tr>
                                        <td>{{ ++$no }}</td>
                                        <td>{{ $item->no_ticket }}</td>
                                        <td>{{ $item->no_aduan }}</td>
                                        <td>{{ $item->atas_nama }}</td>
                                        <td>{{ $item->sumber_informasi }}</td>
                                        <td>{{ $item->lokasi }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ tanggal_indonesia($item->created_at) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('aduan.edit', $item->slug) }}"
                                                class="btn btn-sm btn-warning text-light">
                                                <i class="nav-icon fas fa-edit"></i> Ubah</a>
                                            <form id="form-{{ $item->slug }}"
                                                action="{{ route('aduan.destroy', $item->slug) }}" method="POST"
                                                style="display: none;">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                            <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                                                title="Hapus" onclick=deleteconf("{{ $item->slug }}")>
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10">Data Aduan tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $aduan->links() }}
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