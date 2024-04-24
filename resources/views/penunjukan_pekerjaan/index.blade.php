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
                            {{-- Tanggal --}}

                            {{-- Kategori Dinas --}}
                            {{-- Rekanan --}}
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" role="form" id="form" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-2">
                                    <label for="">Pencarian</label>
                                    <input type="text" name="search" value="{{ $search }}" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">Spk</label>
                                    <input type="text" name="spk" value="{{ $spk }}" class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    <label for="">Kategori Aduan</label>
                                    <select name="kategori" class="selected2 form-control" id="cmbKategori">
                                        <option value="all" data-allow-clear=true>Semua Kategori</option>
                                        <option value="pipa dinas" {{ $kategori == 'pipa dinas' ? 'selected' : '' }}>Pipa
                                            dinas
                                        </option>
                                        <option value="pipa premier / skunder"
                                            {{ $kategori == 'pipa premier / skunder' ? 'selected' : '' }}>Pipa Premier
                                            /Skunder
                                        </option>

                                    </select>
                                    @if ($errors->has('rule'))
                                        <span class="text-danger">
                                            <strong id="textrule">{{ $errors->first('rule') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                @if (!auth()->user()->hasRole('rekanan'))
                                    <div class="col-lg-2">
                                        <label for="">Daftar Rekanan</label>
                                        <select name="rekanan_id" class="selected2 form-control" id="cmbrekanan">
                                            <option value="">Pilih Rekanan</option>
                                            <option value="all" {{ $rekananid == 'all' ? 'selected' : '' }}>Semua
                                            </option>

                                            @foreach ($rekanan as $rek)
                                                <option value="{{ $rek->id }}"
                                                    {{ $rekananid == $rek->id ? 'selected' : '' }}>{{ $rek->nama }} |
                                                    {{ $rek->opr }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                @endif
                                <div class="col-lg-2">
                                    <label for="">Tanggal Pekerjaan</label>
                                    <input type="text" id="daterange" name="tanggal" value="{{ $tanggal }}"
                                        class="form-control">
                                </div>
                                <div class="col-lg-2">
                                    {{-- @if (isset($status))

                                    @endif --}}
                                    <label for="">Status</label>
                                    <select name="status" class="selected2 form-control" id="cmbStatus">
                                        <option value="">Pilih Status</option>
                                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua</option>
                                        <option value="not" {{ $status == 'not' ? 'selected' : '' }}>Belum ditunjuk
                                        </option>
                                        {{-- Mulai --}}
                                        <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Belum dikerjakan
                                        </option>
                                        <option value="proses" {{ $status == 'proses' ? 'selected' : '' }}>Sedang
                                            dikerjakan
                                        </option>
                                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai
                                            dikerjakan
                                        </option>
                                        <option value="approve" {{ $status == 'approve' ? 'selected' : '' }}>
                                            DiApprove Asisten Manajer
                                        </option>
                                        <option value="approve manajer"
                                            {{ $status == 'approve manajer' ? 'selected' : '' }}>
                                            Diapprove Manajer
                                        </option>
                                        <option value="koreksi pengawas"
                                            {{ $status == 'koreksi pengawas' ? 'selected' : '' }}>Dikoreksi
                                            Pengawas
                                        </option>
                                        <option value="koreksi asmen" {{ $status == 'koreksi asmen' ? 'selected' : '' }}>
                                            Dikoreksi Asmen Pengawas
                                        </option>
                                        <option value="dikoreksi" {{ $status == 'selesai koreksi' ? 'selected' : '' }}>
                                            Disetujui Manajer
                                        </option>
                                        <option value="selesai koreksi"
                                            {{ $status == 'selesai koreksi' ? 'selected' : '' }}>Selesai
                                            Dikoreksi
                                        </option>

                                    </select>
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
                                @if (!auth()->user()->hasRole('rekanan'))
                                @can('export-pekerjaan')
                                    <div class="col-lg-1">
                                        <label for="">Export</label>
                                        <div class="input-group">


                                            <a href="{{ route('penunjukan_pekerjaan.excel')}}?kategori={{$kategori}}&rekanan_id={{$rekananid}}&status={{$status}}&tanggal={{$tanggal}}"   class="btn btn-success" id="export-excel">
                                                <span class="fa fa-file-excel"></span>
                                                Export Excel
                                            </a>
                                        </div>
                                    </div>
                                @endcan
                                @endif
                                @if (auth()->user()->hasRole('asisten-manajer-perencanaan'))
                                    <div class="col-lg-3">
                                        <label for="">Rekapan Pekerjaan</label>
                                        <div class="input-group">
                                            <a href="{{ route('penunjukan_pekerjaan.rekanan') }}" class="btn btn-primary">
                                                <span class="fa fa-edit"></span>
                                                Proses
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                        <table class="table table-bordered table-responsive" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center" width="10%">Aksi</th>
                                    <th>No.</th>
                                    <th>Nomor SPK</th>
                                    <th>Nomor NPS</th>
                                    <th>Nomor Tiket</th>
                                    <th>Pekerja</th>
                                    <th>Kategori Aduan</th>
                                    <th>Tanggal Aduan</th>
                                    <th>Tanggal Pekerjaan</th>
                                    <th>OPR</th>
                                    <th>Pelapor</th>
                                    <th>Admin</th>
                                    <th width="20%">Lokasi</th>
                                    <th>Status</th>
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
                                    <tr class="">
                                        <td class="text-center">
                                            <a href="{{ route('penunjukan_pekerjaan.show', $item->slug) }}"
                                                class="btn btn-sm {{ $item->btn }}   text-light m-1">

                                                @if (auth()->user()->hasRole('rekanan'))
                                                    <i class="nav-icon fa fa-search"></i> Detail
                                                @elseif (auth()->user()->hasRole('admin-distribusi') ||
                                                        auth()->user()->hasRole('admin-pengendalian-kehilangan-air'))
                                                    <i class="nav-icon fa fa-search"></i> Detail
                                                @elseif (auth()->user()->hasRole('asisten-manajer-distribusi') ||
                                                        auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air'))
                                                    @if ($item->status_aduan == 'Selesai dikerjakan')
                                                        <i class="nav-icon fas fa-eye"></i> Setujui
                                                    @elseif ($item->status_aduan == 'Belum ditunjuk')
                                                        <i class="nav-icon fas fa-eye"></i> Tunjuk
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('manajer-distribusi') ||
                                                        auth()->user()->hasRole('manajer-pengendalian-kehilangan-air'))
                                                    @if ($item->status_aduan == 'Approve Asisten Manajer')
                                                        <i class="nav-icon fas fa-eye"></i> Setujui
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('staf-pengawas'))
                                                    @if ($item->status_aduan == 'Selesai dikerjakan')
                                                        <i class="nav-icon fas fa-search"></i> Detail
                                                    @elseif ($item->status_aduan == 'Approve Manajer')
                                                        <i class="nav-icon fas fa-eye"></i> Koreksi
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('asisten-manajer-pengawas'))
                                                    @if ($item->status_aduan == 'Dikoreksi Pengawas')
                                                        <i class="nav-icon fas fa-eye"></i> Koreksi
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('manajer-perawatan'))
                                                    @if ($item->status_aduan == 'Dikoreksi Asmen Pengawas')
                                                        <i class="nav-icon fas fa-eye"></i> Koreksi
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @elseif (auth()->user()->hasRole('asisten-manajer-perencanaan'))
                                                    @if ($item->status_aduan == 'Disetujui Manajer')
                                                        <i class="nav-icon fas fa-eye"></i> Koreksi
                                                    @else
                                                        <i class="nav-icon fa fa-search"></i> Detail
                                                    @endif
                                                @else
                                                    <i class="nav-icon fa fa-search"></i> Detail
                                                @endif

                                            </a>
                                            <br>

                                            @if (auth()->user()->hasRole('asisten-manajer-perencanaan'))
                                                @if ($item->status_aduan == 'selesai koreksi')
                                                    @if ($item->tagihan == 'tidak' && $item->bukan_rekanan != true)
                                                        <a href="{{ route('penunjukan_pekerjaan.adjust', $item->slug) }}"
                                                            class="btn btn-sm btn-success  text-light">
                                                            <i class="nav-icon fa fa-edit"></i> Adjust
                                                        </a>
                                                    @endif
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $nomor + ($penunjukan->CurrentPage() - 1) * $penunjukan->PerPage() }}
                                        </td>
                                        <td>{{ $item->no_spk }}</td>
                                        <td>{{ $item->nps }}</td>
                                        {{-- <td>{{ $item->status_aduan }}</td> --}}
                                        <td>{{ $item->no_ticket }}</td>
                                        <td>{{ $item->rekanan }}</td>
                                        <td>{{ ucfirst($item->kategori_aduan) }}</td>
                                        <td>{{ tanggal_indonesia($item->created_at) }}</td>
                                        <td>{{ $item->tanggal_pekerjaan }}</td>
                                        <td>{{ $item->opr }}</td>
                                        <td>{{ $item->atas_nama }}</td>
                                        <td>{{ ucfirst($item->user) }}</td>
                                        <td>{{ $item->lokasi }}</td>
                                        <td>{{ ucfirst($item->status_aduan) }}</td>

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
@push('head')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        function replaceUrlParam(url, paramName, paramValue){
            var pattern = new RegExp('(\\?|\\&)('+paramName+'=).*?(&|$)')
            var newUrl=url
            if(url.search(pattern)>=0){
                newUrl = url.replace(pattern,'$1$2' + paramValue + '$3');
            }
            else{
                newUrl = newUrl + (newUrl.indexOf('?')>0 ? '&' : '?') + paramName + '=' + paramValue
            }
            return newUrl
        }

        $('#cmbKategori').select2({
            placeholder: '--- Pilih Kategori ---',

            allowClear: true
        }).on('change', function(e) {
            let kategori= $("#cmbKategori option:selected").val();;

            let urlExcel = $('#export-excel').attr('href');
            let urlExport = replaceUrlParam(urlExcel, 'kategori',kategori);
            $('#export-excel').attr('href', urlExport);
            console.log($('#export-excel').attr('href'));
        });
        $('#cmbrekanan').select2({
            placeholder: '--- Pilih Rekanan ---',
            width: '100%'
        }).on('change', function(e) {
            let rekanan_id= $("#cmbrekanan option:selected").val();;

            let urlExcel = $('#export-excel').attr('href');
            let urlExport = replaceUrlParam(urlExcel, 'rekanan_id',rekanan_id);
            $('#export-excel').attr('href', urlExport);
            console.log($('#export-excel').attr('href'));
        });

        $('#cmbStatus').select2({
            placeholder: '--- Pilih Status ---',
            width: '100%'
        }).on('change', function(e) {
            let status= $("#cmbStatus option:selected").val();;
            console.log(status);
            let urlExcel = $('#export-excel').attr('href');
            let urlExport = replaceUrlParam(urlExcel, 'status',status);
            $('#export-excel').attr('href', urlExport);
            console.log($('#export-excel').attr('href'));
        });
        // $('#cmbStatus').on("select2:selecting", function(e) {
        //     let status= e.params.data;
        //     let urlExcel = $('#export-excel').attr('href');
        //     // let tanggal = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');


        //     // let urlExport = replaceUrlParam(urlExcel, 'status',status);
        //     // $('#export-excel').attr('href', urlExport);
        //     // console.log($('#export-excel').attr('href'));
        // });
    </script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

    <script type="text/javascript">
        $('#daterange').daterangepicker();

        // $('#daterange').on('apply.daterangepicker', function(ev, picker) {


        // });


        $('input[name="tanggal"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
            let urlExcel = $('#export-excel').attr('href');
            let tanggal = picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY');
            let urlExport = replaceUrlParam(urlExcel, 'tanggal',tanggal);
            $('#export-excel').attr('href', urlExport);
        });

        $('input[name="tanggal"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    </script>
@endpush
