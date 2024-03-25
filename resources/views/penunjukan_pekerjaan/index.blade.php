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
                                    <select name="kategori" class="selected2 form-control">
                                        <option value="">Semua Kategori</option>
                                        <option value="pipa dinas" {{ $kategori == 'pipa dinas' ? 'selected' : '' }}>Pipa
                                            dinas
                                        </option>
                                        <option value="pipa premier / skunder"
                                            {{ $kategori == 'pipa premier / skunder' ? 'selected' : '' }}>Pipa Premier
                                            /Sskunder
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
                                    {{-- <th>Total Tagihan</th> --}}

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
                                        {{-- <td>{{ format_uang($item->total_pekerjaan) }}</td> --}}


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
        $('#cmbrekanan').select2({
            placeholder: '--- Pilih Rekanan ---',
            width: '100%'
        });
        $('#cmbStatus').select2({
            placeholder: '--- Pilih Status ---',
            width: '100%'
        });
    </script>
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
