@extends('template.app')
@section('title', 'Daftar Absensi')
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Daftar {{ $title }}</h3>
                        <a href="{{ route('absensi.create') }}" target="_blank"
                            class="btn btn-sm btn-primary float-right text-light">
                            <i class="fa fa-plus"></i>Tambah
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Waktu</th>
                                    <th>Jumlah Terlambat</th>
                                    {{-- <th class="text-center" width="20%">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absensi as $item)
                                    <tr>

                                        <td>{{ ++$no }}</td>
                                        <td>{{ $item->karyawan->nama }}</td>
                                        <td>{{ $item->status }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>{{ $item->created_at->format('d F Y h:i:s A ') }}</td>
                                        <td>{{ $item->menit }}</td>
                                        {{-- <td class="text-center">
                                    <a href="{{ route('role.edit', $item->id) }}"
                                        class="btn btn-sm btn-warning text-light">
                                        <i class="nav-icon fas fa-edit"></i> Ubah</a>
                                    <form id="form-{{ $item->id }}" action="{{ route('role.destroy', $item->id) }}"
                                        method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}
                                    </form>
                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                                        title="Hapus" onclick=deleteconf("{{ $item->id }}")>
                                        <i class="fa fa-trash"></i> Hapus
                                    </button>
                                </td> --}}
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6">Data absensi tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $absensi->appends(request()->input())->links('template.pagination') }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div>
@stop
