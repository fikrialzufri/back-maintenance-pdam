@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Daftar {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }}
                        </h3>
                        {{ $data->appends(request()->input())->links() }}
                        <div>
                            @can('create-tagihan')
                                <a href="{{ route($route . '.create') }}" class="btn btn-sm btn-primary float-right text-light">
                                    <i class="fa fa-plus"></i>Tambah Data
                                </a>
                                @if (!auth()->user()->hasRole('rekanan'))
                                    <a href="{{ route($route . '.upload') }}"
                                        class="btn btn-sm btn-warning float-right text-light mr-5">
                                        <i class="fa fa-file"></i> Upload
                                    </a>
                                @endif
                            @endcan
                        </div>

                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                @foreach ($searches as $key => $item)
                                    <div class="col-lg-2">

                                        <label for="{{ $item['name'] }}">{{ ucfirst($item['alias']) }}</label>
                                        @include('template.formsearch')
                                    </div>
                                @endforeach

                                <div class="col-lg-3">
                                    <label for="">Aksi</label>
                                    <div class="input-group">


                                        <button type="submit" class="btn btn-warning">
                                            <span class="fa fa-search"></span>
                                            Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <br>
                        <table class="table table-bordered table-responsive" id="example">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    @foreach ($configHeaders as $key => $header)
                                        @if (isset($header['alias']))
                                            <th>{{ ucfirst($header['alias']) }}</th>
                                        @else
                                            <th>{{ ucfirst($header['name']) }}</th>
                                        @endif
                                    @endforeach

                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $index => $item)

                                    <tr
                                        class="{{ $item->belum_adjust == 'ya' ? 'bg-danger' : '' }} {{ $item->belum_persetujuan }}">
                                        <td>{{ $index + 1 + ($data->CurrentPage() - 1) * $data->PerPage() }}</td>
                                        @foreach ($configHeaders as $key => $header)
                                            @if (isset($header['input']))
                                                @if ($header['input'] == 'rupiah')
                                                    <td>Rp. {{ format_uang($item[$header['name']]) }}</td>
                                                @elseif ($header['input'] == 'date')
                                                    <td>
                                                        @if ($item[$header['name']] != null || $item[$header['name']] != '')
                                                            {{ tanggal_indonesia($item[$header['name']]) }}
                                                        @endif
                                                    </td>
                                                @endif
                                            @else
                                                <td>{{ $item[$header['name']] }}</td>
                                            @endif
                                        @endforeach
                                        {{-- <td>
                                            {{ auth()->user()->role[0]->name }}
                                        </td> --}}
                                        <td class="text-center">
                                            <a href="{{ route($route . '.show', $item->slug) }}"
                                                class="btn btn-sm btn-primary text-light" data-toggle="tooltip"
                                                data-placement="top">

                                                @if ($item->belum_persetujuan === 'bg-danger')
                                                    <i class="nav-icon fas fa-eye"></i>
                                                    @if (auth()->user()->hasRole('manajer-distribusi') ||
                                                            auth()->user()->hasRole('keuangan') ||
                                                            auth()->user()->hasRole('staf-keuangan') ||
                                                            auth()->user()->hasRole('manajer-pengendalian-kehilangan-air') ||
                                                            auth()->user()->hasRole('asisten-manajer-tata-usaha') ||
                                                            auth()->user()->hasRole('manajer-umum-dan-kesekretariatan') ||
                                                            auth()->user()->hasRole('direktur-umum') ||
                                                            auth()->user()->hasRole('direktur-utama') ||
                                                            auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') ||
                                                            auth()->user()->hasRole('asisten-manajer-akuntansi') ||
                                                            auth()->user()->hasRole('manajer-keuangan') ||
                                                            auth()->user()->hasRole('manajer-perencanaan') ||
                                                            auth()->user()->hasRole('direktur-teknik'))
                                                        Proses
                                                    @endif
                                                @else
                                                    <i class="nav-icon fas fa-edit"></i>
                                                    Rician
                                                @endif
                                            </a>

                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="10">Data
                                            {{ ucwords(str_replace([':', '_', '-', '*'], ' ', $title)) }} tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $data->appends(request()->input())->links('template.pagination') }}
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div>
@endsection

@push('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/DataTables/css/datatables.css') }}">
@endpush
@push('script')
    <!-- DataTables -->
    <script src="{{ asset('plugins/DataTables/datatables.js') }}"></script>
    <script>
        // $('#example').DataTable({
        //   "paging": true,
        //   "lengthChange": true,
        //   "searching": true,
        //   "ordering": true,
        //   "info": true,
        //   "autoWidth": true,
        //   "pageLength": 20,
        // });
    </script>
@endpush
