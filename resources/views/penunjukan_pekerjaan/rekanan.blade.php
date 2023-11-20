@extends('template.app')
@section('title', 'List Rekapan Rekanan')

@section('content')
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h3 class="card-title">Daftar {{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="">Rekanan</label>
                                    <input type="text" name="search" value="{{ $search }}" class="form-control">
                                </div>
                                <div class="col-lg-1">
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
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama CV</th>
                                    <th>Penanggung Jawab</th>
                                    <th>Nomor No Hp</th>
                                    <th>OPR</th>
                                    <th class="text-center" width="10%">Proses</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rekanan as $index => $item)
                                    <tr
                                        class="{{ $item->keterangan_barang != null && $item->id_rekanan != '' ? 'bg-danger' : '' }} ">
                                        <td>{{ $index + 1 + ($rekanan->CurrentPage() - 1) * $rekanan->PerPage() }}
                                        </td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->nama_penangung_jawab }}</td>
                                        <td>{{ $item->no_hp }}</td>
                                        <td>{{ $item->opr }}</td>

                                        <td class="text-center">
                                            <a href="{{ route('penunjukan_pekerjaan.rekapan', $item->id) }}"
                                                class="btn btn-sm btn-primary text-light m-1">
                                                <i class="nav-icon fas fa-edit"></i>
                                                Proses
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="11">Data Rekanan tidak ada</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix">
                        {{ $rekanan->appends(request()->input())->links('template.pagination') }}
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
@push('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@push('script')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $('#daterange').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('input[name="tanggal"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
        });

        $('input[name="tanggal"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endpush
