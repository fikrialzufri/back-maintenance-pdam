@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@push('head')
    <link rel="stylesheet" href="{{ asset('plugins/mohithg-switchery/dist/switchery.min.css') }}">
@endpush
@section('content')`
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST" role="form"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="col-12">
                                        <h6>Detail Tagihan</h6>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="NoTagihan" class=" form-control-label">Nomor Tagihan </label>
                                            </div>
                                            <div>
                                                <input type="text" name="no_tagihan" id="No Tagihan"
                                                    placeholder="No Tagihan " class="form-control" readonly
                                                    value="{{ $nomor_tagihan }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="NoTagihan" class=" form-control-label">Total Lokasi</label>
                                            </div>
                                            <div>
                                                <input type="text" name="no_tagihan" id="No Tagihan"
                                                    placeholder="No Tagihan " class="form-control" readonly
                                                    value="{{ $total_lokasi }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="NoTagihan" class=" form-control-label">Tanggal Tagihan</label>
                                            </div>
                                            <div>
                                                <input type="text" name="no_tagihan" id="No Tagihan"
                                                    placeholder="No Tagihan " class="form-control" readonly
                                                    value="{{ $tanggal_tagihan }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="rekanan" class=" form-control-label">Rekanan</label>
                                            </div>
                                            <div>
                                                <input type="text" name="rekanan" id="rekanan" placeholder="Rekanan "
                                                    class="form-control" readonly value="{{ $rekanan }}">
                                            </div>
                                        </div>
                                    </div>
                                    @if ($tagihan->kode_vocher != '')
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="voucher" class=" form-control-label">Voucher</label>
                                                </div>
                                                <div>
                                                    <input type="text" placeholder="voucher " class="form-control"
                                                        readonly value="{{ $tagihan->kode_vocher }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="total_tagihan" class=" form-control-label">Total Tagihan</label>
                                            </div>
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Rp.</div>
                                                </div>
                                                <input type="text" name="total_tagihan" id="total_tagihan_all"
                                                    placeholder="" class="form-control" readonly
                                                    value="{{ format_uang($total) }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6 timeline">
                                    <h6>List Persetujuan</h6>
                                    <ul>
                                        @forelse ($list_persetujuan as $item)
                                            @if ($item->is_setuju === true)
                                                <li>
                                                    <div class="bullet bg-primary"></div>
                                                    <div class="time">{{ $item->tanggal_disetujui }}</div>
                                                    <div class="desc">
                                                        <h3>{{ $item->nama }}</h3>
                                                        <h4>{{ $item->jabatan }}</h4>
                                                    </div>
                                                </li>
                                            @else
                                                <li>
                                                    <div class="bullet bg-navy"></div>
                                                    <div class="time"></div>
                                                    <div class="desc">
                                                        <h3>212</h3>
                                                        <h4>12</h4>
                                                    </div>
                                                </li>
                                            @endif
                                        @empty
                                        @endforelse


                                    </ul>
                                </div>



                            </div>
                            @if (isset($tagihan->hasPelaksanaanPekerjaan))
                                @foreach ($tagihan->hasPelaksanaanPekerjaan as $key => $item)
                                    <div>
                                        <label for="rekanan" class=" form-control-label">
                                            <h3>Pekerjaan :
                                                {{ $item->No_Spk }} </h3>
                                        </label>

                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div>
                                                <span>Daftar Pekerjaan</span>
                                                <table class="table table-bordered " width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5">#</th>
                                                            <th>Nama</th>
                                                            <th>Jenis Pekerjaan</th>
                                                            <th width="10">Jumlah</th>
                                                            @if ($perencaan === true)
                                                                <th>Harga</th>
                                                                <th>Total Harga</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($item->hasItem as $nomor => $barang)
                                                            <tr class="{{ $barang->pivot->harga == 0 ? 'bg-danger' : '' }}"
                                                                id="{{ $barang->slug }}_{{ $item->id }}">
                                                                <td>{{ $nomor + 1 }}
                                                                </td>
                                                                <td>{{ $barang->nama }}</td>
                                                                <td>{{ $barang->jenis }}</td>
                                                                <td id="qty_{{ $barang->slug }}_{{ $item->id }}">
                                                                    {{ $barang->pivot->qty }}</td>
                                                                @if ($perencaan === true)
                                                                    @if ($barang->pivot->harga == 0)
                                                                        <td>
                                                                            <div class="input-group mb-2 mr-sm-2">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text">Rp.</div>
                                                                                </div>
                                                                                <input type="text"
                                                                                    name="harga_{{ $barang->slug }}_{{ $item->id }}"
                                                                                    id="harga_{{ $barang->slug }}_{{ $item->id }}"
                                                                                    placeholder="" class="form-control">
                                                                            </div>
                                                                        </td>
                                                                        <td
                                                                            id="total_harga_{{ $barang->slug }}_{{ $item->id }}">
                                                                            Rp. 0</td>
                                                                        @push('script')
                                                                            <script>
                                                                                $('#harga_{{ $barang->slug }}_{{ $item->id }}').on("input", function() {

                                                                                    let val = formatRupiah(this.value, '');
                                                                                    $('#harga_{{ $barang->slug }}_{{ $item->id }}').val(val);
                                                                                    let qty = parseInt($('#qty_{{ $barang->slug }}_{{ $item->id }}').text());
                                                                                    let totalHarga = val.replace(".", "") * qty;

                                                                                    $('#total_harga_{{ $barang->slug }}_{{ $item->id }}').text("Rp. " + formatRupiah(totalHarga
                                                                                        .toString(), ''));

                                                                                    $('#{{ $barang->slug }}_{{ $item->id }}').removeClass('bg-danger');
                                                                                });
                                                                            </script>
                                                                        @endpush
                                                                    @else
                                                                        <td>
                                                                            Rp. {{ format_uang($barang->pivot->harga) }}
                                                                        </td>

                                                                        <td>
                                                                            Rp.
                                                                            {{ format_uang($barang->pivot->harga * $barang->pivot->qty) }}
                                                                        </td>
                                                                    @endif
                                                                @endif

                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="10">Data Item tidak ada</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3">Total
                                                            </th>
                                                            <th>{{ $item->hasItem->sum('pivot.qty') }}</th>
                                                            @if ($perencaan === true)
                                                                <th></th>
                                                                <th>Rp. {{ format_uang($item->total_harga) }}
                                                                </th>
                                                            @endif
                                                        </tr>
                                                    </tfoot>

                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div>

                                                <table class="table table-bordered " width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5">#</th>
                                                            <th width="500">Pekerjaan</th>
                                                            <th width="10">Panjang</th>
                                                            <th width="10">Lebar</th>
                                                            <th width="10">Dalam</th>
                                                            <th
                                                                @if ($perencaan === true) width="10" @else width="50" @endif>
                                                                Volume</th>
                                                            @if ($perencaan === true)
                                                                <th>Total Harga</th>
                                                            @endif
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        @forelse ($item->hasGalianPekerjaan as $key => $value)
                                                            @php
                                                                $sum = 0;
                                                            @endphp
                                                            <tr>
                                                                <td>{{ $key + 1 }}
                                                                </td>
                                                                <td>{{ $value->pekerjaan }}</td>
                                                                <td>{{ $value->panjang }} m</td>
                                                                <td>{{ $value->lebar }} m</td>
                                                                <td>{{ $value->dalam }} m</td>
                                                                <td>{{ $value->panjang * $value->lebar * $value->dalam }}
                                                                    m<sup>2</sup>
                                                                </td>
                                                                @if ($perencaan === true)
                                                                    <td>Rp. {{ format_uang($value->total) }}</td>
                                                                @endif
                                                                @php
                                                                    $sum += $value->panjang * $value->lebar * $value->dalam;
                                                                @endphp
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="10">Data Item Galian ada</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="5"> Total
                                                            </th>
                                                            <th>
                                                                {{ $item->luas_galian }} m<sup>2</sup>
                                                            </th>
                                                            @if ($perencaan === true)
                                                                <th>Rp.
                                                                    {{ format_uang($item->hasGalianPekerjaan->sum('total')) }}
                                                                </th>
                                                            @endif
                                                        </tr>
                                                        <tr>
                                                            <th
                                                                @if ($perencaan === true) colspan="6" @else colspan="5" @endif>
                                                                Grand Total
                                                            </th>
                                                            <th>Rp.
                                                                {{ format_uang($item->total_pekerjaan) }}
                                                            </th>
                                                        </tr>
                                                    </tfoot>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                            @if (!auth()->user()->hasRole('rekanan'))
                                @if ($keuangan === true)
                                    <div class="row mb-5">
                                        <div class="col-12">
                                            <div>
                                                <label for="kode_voucher" class=" form-control-label">Kode Voucher</label>
                                            </div>
                                            <div>
                                                <input type="text" name="kode_voucher" placeholder="Kode Voucher"
                                                    class="form-control  {{ $errors->has('kode_voucher') ? 'form-control is-invalid' : 'form-control' }}"
                                                    value="{{ old('kode_voucher') }}" required>
                                            </div>

                                            @if ($errors->has('kode_voucher'))
                                                <span class="text-danger">
                                                    <strong id="textkk">{{ $errors->first('kode_voucher') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                @if ($bntSetuju === false)
                                    <div class="row">
                                        <div class="col-12">
                                            <div>
                                                <button type="submit" class="btn btn-primary">Setujui Pekerjaan</button>
                                            </div>
                                        </div>

                                    </div>
                                @endif
                            @endif
                        </div>

                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <div class="row">


                                @if ($tagihan)
                                    <div class="col-2">
                                        @if (!auth()->user()->hasRole('rekanan'))
                                            @if ($tagihan->status === 'disetujui')
                                                <a href="{{ route('tagihan.word') }}?id={{ $tagihan->id }}"
                                                    target="_blank" class="btn btn-danger"><span
                                                        class="nav-icon fa fa-file-word" aria-hidden="true"></span>
                                                    Privew Tagihan</a>
                                            @endif
                                        @else
                                            <a href="{{ route('tagihan.word') }}?id={{ $tagihan->id }}"
                                                target="_blank" class="btn btn-danger"><span
                                                    class="nav-icon fa fa-file-word" aria-hidden="true"></span>
                                                Privew Tagihan</a>
                                        @endif
                                    </div>
                                @endif
                            </div>

                        </div>
                    </form>
                </div>
                <!-- ./col -->
            </div>
            @isset($tagihanItem)
                @if (count($tagihanItem) > 0)
                    <div class="col-md-12">
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="card-body">
                                <table class="table table-bordered table-responsive" width="100%" id="tableDokumentasi">
                                    <thead>
                                        <tr>
                                            <th width="5">#</th>
                                            <th width="5">No Pekerjaan</th>
                                            <th width="150">Uraian Rekanan</th>
                                            <th width="10%">Master Uraian</th>
                                            <th width="10">Jenis Pekerjaan</th>
                                            <th width="5">Jenis Harga</th>
                                            <th width="120">Harga Rekanan</th>
                                            <th width="120">Harga Master</th>
                                            <th width="5" class="text-center">Jumlah</th>
                                            <th width="120">Total</th>
                                            <th width="100">Tanggal Adjust</th>
                                            <th width="120">Harga Adjust</th>
                                            <th width="10">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (isset($tagihanItem))
                                            @forelse ($tagihanItem as $key => $tagihanItem)
                                                <tr class="{{ $tagihanItem->selisih == 'ya' ? 'bg-danger' : '' }}"
                                                    id="listtagihan_{{ $tagihanItem->id }}"
                                                    data-tagihan_id="{{ $tagihanItem->id }}"
                                                    data-master="{{ $tagihanItem->master }}"
                                                    data-jenis_harga="{{ $tagihanItem->jenis_harga }}"
                                                    data-jumlah="{{ $tagihanItem->jumlah }}" class="list_table_tagihan">
                                                    <td class="text-center nomor_tagihan" data-index="{{ $key + 1 }}">
                                                        {{ $key + 1 }}
                                                    </td>
                                                    <td>
                                                        {{ $tagihanItem->no_pekerjaan }}
                                                    </td>
                                                    <td>
                                                        {{ $tagihanItem->uraian }}
                                                    </td>
                                                    <td>
                                                        <span class="ubah_pekerjaan" data-tagihan_id="{{ $tagihanItem->id }}"
                                                            data-master="{{ $tagihanItem->master }}"
                                                            data-jenis_harga="{{ $tagihanItem->jenis_harga }}"
                                                            data-item_id="{{ $tagihanItem->jenis_harga }}"
                                                            data-jumlah="{{ $tagihanItem->jumlah }}" role="button"
                                                            id="nama_master_tagihan_{{ $tagihanItem->id }}">

                                                            {{ $tagihanItem->master }}
                                                        </span>
                                                    </td>

                                                    <td id="jenis_{{ $tagihanItem->id }}">
                                                        {{ $tagihanItem->item_jenis }}
                                                    </td>
                                                    <td>
                                                        {{ $tagihanItem->jenis_harga }}
                                                    </td>
                                                    <td>
                                                        Rp. {{ format_uang($tagihanItem->harga_uraian) }}
                                                    </td>
                                                    <td>
                                                        Rp. {{ format_uang($tagihanItem->harga_master) }}
                                                    </td>
                                                    <td id="jumlah_{{ $tagihanItem->id }}" class="text-center">
                                                        {{ $tagihanItem->jumlah }}
                                                    </td>
                                                    <td>
                                                        <span id="total_tagihan_{{ $tagihanItem->id }}">
                                                            Rp. {{ format_uang($tagihanItem->grand_total) }}
                                                        </span>

                                                        <input type="hidden" name="total_tagihan_value_"
                                                            class="total_tagihan_value"
                                                            value="{{ $tagihanItem->grand_total }}"
                                                            id="total_tagihan_value_{{ $tagihanItem->id }}">
                                                    </td>
                                                    <td id="tanggal_adjust_{{ $tagihanItem->id }}">

                                                        {{ $tagihanItem->tanggal_adjust_indo }}

                                                    </td>
                                                    <td>

                                                        <div class="form-group">
                                                            <div class="input-group mb-2 mr-sm-2">
                                                                <input type="text" name="harga_adjust"
                                                                    id="harga_adjus_{{ $tagihanItem->id }}"
                                                                    placeholder="Harga"
                                                                    value="{{ format_uang($tagihanItem->total_adjust) }}"
                                                                    class="form-control">
                                                            </div>
                                                        </div>
                                                        @push('script')
                                                            <script>
                                                                $('#harga_adjus_' + '{{ $tagihanItem->id }}').on("input keyup paste", function() {
                                                                    harga = this.value.split('.').join('');
                                                                    $('#harga_adjus_' + '{{ $tagihanItem->id }}').val(harga);
                                                                    let val = formatRupiah(this.value, ' ');
                                                                    $('#harga_adjus_' + '{{ $tagihanItem->id }}').val(val);
                                                                });
                                                            </script>
                                                        @endpush
                                                    </td>
                                                    <td>
                                                        <button type="buttom" id="btn_adjust_{{ $tagihanItem->id }}"
                                                            data-tagihan_id="{{ $tagihanItem->id }}"
                                                            data-item_id="{{ $tagihanItem->item_id }}"
                                                            data-jumlah="{{ $tagihanItem->jumlah }}"
                                                            class="btn btn-primary btn_adjust">Ubah</button>
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr class="tagihanTidakAda">
                                                    <td colspan="10">Data tagihan tidak ada</td>
                                                </tr>
                                            @endforelse
                                        @else
                                            <tr class="tagihanTidakAda">
                                                <td colspan="10">Data tagihan tidak ada</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    <tfoot>
                                        @if (isset($tagihanItem))
                                            <tr>
                                                <th colspan="8" class="text-right">Grand Total
                                                </th>
                                                <th class="text-center">
                                                    {{ $tagihanItem->sum('jumlah') }}
                                                </th>
                                                <th>
                                                    <span id="grand_total_tagihan_tampil">
                                                        Rp.
                                                        {{ format_uang($total) }}
                                                    </span>
                                                    <input type="hidden" id="grand_total_tagihan_value"
                                                        name="grand_total_tagihan" value="{{ $total }}"
                                                        class="grand_total_tagihan">
                                                </th>

                                            </tr>
                                        @endif
                                    </tfoot>

                                </table>
                            </div>
                            <div class="card-footer clearfix">
                                <a href="{{ route('tagihan.excel') }}?id={{ $tagihan->id }}"
                                    class="btn btn-success"><span class="nav-icon fa fa-file-excel" aria-hidden="true"></span>
                                    Export Excel Tagihan</a>

                                <a href="{{ route('tagihan.word') }}?id={{ $tagihan->id }}" target="_blank"
                                    class="btn btn-danger"><span class="nav-icon fa fa-file-word" aria-hidden="true"></span>
                                    Privew Tagihan</a>
                            </div>
                        </div>
                        <!-- ./col -->
                    </div>
                @endif
            @endisset
            <!-- /.row -->
            <!-- Main row  dataitem-->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    <div class="modal fade" id="list_item_modal" role="dialog" aria-labelledby="pekerjaan_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pekerjaan_label">Ganti Pekerjaan</span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form id="formPembayaran" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group mb-2 mr-sm-2">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">Cari : </div>
                                </div>
                                <input type="text" class="form-control" id="search" placeholder="Cari">
                            </div>
                        </div>
                        <table class="table-responsive table table-head-fixed  table-bordered " id="tableItem"
                            style="height:700px">
                            <thead>
                                <tr>
                                    <th class="">Nama</th>
                                    <th class="">Jenis</th>
                                    <th class="">Harga Siang</th>
                                    <th class="">Harga Malam</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dataitem as $nor => $item)
                                    <tr>
                                        <td>
                                            <span class="ganti_pekerjaan" data-item="{{ $item->id }}"
                                                data-tagihan="">
                                                {{ $item->nama }}
                                            </span>

                                        </td>
                                        <td>{{ $item->jenis }}</td>
                                        <td>{{ format_uang($item->harga) }}</td>
                                        <td>{{ format_uang($item->harga_malam) }}</td>
                                    </tr>
                                @endforeach

                            </tbody>

                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('script')
    <style>
        [role=button] {
            cursor: pointer;
        }

    </style>
@endpush
@push('script')
    <script>
        $(function() {
            $("#nama").keypress(function() {
                $("#nama").removeClass("is-invalid");
                $("#textNama").html("");
            });
            $("#description").keypress(function() {
                $("#description").removeClass("is-invalid");
                $("#textdescription").html("");
            });

            var $rows = $('#tableItem tr');
            $('#search').keyup(function() {
                var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

                $rows.show().filter(function() {
                    var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                    return !~text.indexOf(val);
                }).hide();
            });

            $("#tableDokumentasi tr td").click(function() {
                //get <td> element values here!!??
            });
            $(".ubah_pekerjaan").click(function() {
                // let item_id = $(this).val();
                let item_id = $(this).data('item_id');
                let id = $(this).data('tagihan_id');
                let jumlah = $(this).data('jumlah');
                let master = $(this).data('master');
                let jenis_harga = $(this).data('jenis_harga');

                let harga = $('#harga_adjus_' + id).val();
                $('#master_nama').text($(this).data('master'));

                $('#list_item_modal').modal('toggle');
                $('#tagihan_id_ganti').val(id);
                $('#pekerjaan_label').val(master);

                $(".ganti_pekerjaan").attr('data-tagihan', id);
                $(".ganti_pekerjaan").attr('data-item_id', item_id);
                $(".ganti_pekerjaan").attr('data-jumlah', jumlah);
                $(".ganti_pekerjaan").attr('data-jenis_harga',
                    jenis_harga);

            });

            $(".btn_adjust").on("click", function(e) {
                let item_id = $(this).data('item_id');;
                let id = $(this).data('tagihan_id');
                let jumlah = $(this).data('jumlah');
                let jenis_harga = $(this).data('jenis_harga');
                let harga = $('#harga_adjus_' + id).val();


                $.when($.ajax({
                    type: 'POST',
                    url: "{{ route('tagihan.adjust') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id,
                        item_id,
                        jumlah,
                        jenis_harga,
                        harga,
                    },
                    success: function(data) {

                        const {
                            id,
                            nama,
                            harga,
                            harga_malam,
                            tanggal,
                            grand_total
                        } = data.data;

                        $.toast({
                            heading: 'Success',
                            text: "Mengubahan pekerjaan",
                            showHideTransition: 'slide',
                            icon: 'success',
                            loaderBg: '#f2a654',
                            position: 'top-right'
                        })


                        $('#listtagihan_' + id).removeClass('bg-danger');
                        $("#tanggal_adjust_" + id).text(tanggal);

                        $("#total_tagihan_" + id).text("Rp. " + formatRupiah(
                            grand_total.toString(),
                            ' '));

                        $("#total_tagihan_value_" + id).val(grand_total);
                    },
                    error: function(data) {
                        Swal.fire({
                            title: 'Oops...',
                            text: "gagal mengubah tagihan ",
                            footer: '<a href="">terdapat data yang kosong</a>'
                        })
                    }
                })).then(function(data, textStatus, jqXHR) {
                    // totalHarga(modul)
                    const {
                        id,
                        nama,
                        harga,
                        harga_malam,
                    } = data.data;
                    $('#nama_master_tagihan_' + id).text(nama);

                    let sumTotal = 0;

                    $('.total_tagihan_value').each(function() {
                        sumTotal += parseFloat($(this)
                            .val());
                    });
                    $('#grand_total_tagihan_value_' + id).val(sumTotal);
                    $('#grand_total_tagihan_tampil_' + id).text(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));

                    $('#total_tagihan_all').val(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));

                    $('#grand_total_tagihan_tampil').text('Rp. ' + formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));
                    $('#grand_total_tagihan_value').val(sumTotal);
                });
            });

            $(".ganti_pekerjaan").on("click", function(e) {
                e.stopPropagation();
                let id = $(this).data('item');
                let master = $(this).data('master');
                let tagihan_id = $(this).data('tagihan');
                let jumlah = $(this).data('jumlah');
                let jenis_harga = $(this).data('jenis_harga');
                let totalharga = 0;
                $.when($.ajax({
                    type: 'GET',
                    url: "{{ route('item.detail') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id,
                        jenis_harga,
                    },
                    success: function(data) {

                        const {
                            id,
                            nama,
                            harga,
                            jenis,
                            harga_malam,
                        } = data.data;

                        if (jenis_harga == 'malam') {
                            $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga_malam
                                .toString(),
                                ' '));

                            totalharga = jumlah * harga_malam;
                        } else {
                            $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga
                                .toString(),
                                ' '));

                            totalharga = jumlah * harga;
                        }

                        $("#total_tagihan_" + tagihan_id).text("Rp. " + formatRupiah(
                            totalharga
                            .toString(),
                            ' '));

                        $("#total_tagihan_value_" + tagihan_id).val(totalharga);

                        $("#tagihan_master_" + tagihan_id).attr('data-tagihan', tagihan_id);
                        $("#tagihan_master_" + tagihan_id).attr('data-jumlah', jumlah);
                        $("#tagihan_master_" + tagihan_id).attr('data-item_id', id);
                        $("#tagihan_master_" + tagihan_id).attr('data-jenis_harga',
                            jenis_harga);

                        $("#btn_adjust_" + tagihan_id).attr('data-tagihan', tagihan_id);
                        $("#btn_adjust_" + tagihan_id).attr('data-jumlah', jumlah);
                        $("#btn_adjust_" + tagihan_id).attr('data-item_id', id);
                        $("#btn_adjust_" + tagihan_id).attr('data-jenis_harga',
                            jenis_harga);
                        $("#jenis_" + tagihan_id).text(jenis);



                    },
                    error: function(data) {
                        Swal.fire({
                            title: 'Oops...',
                            text: "gagal Mengahapus " +
                                modul,
                            footer: '<a href="">terdapat data yang kosong</a>'
                        })
                    }
                })).then(function(data, textStatus, jqXHR) {
                    // totalHarga(modul)
                    const {
                        id,
                        nama,
                        harga,
                        harga_malam,
                    } = data.data;
                    $('#nama_master_tagihan_' + tagihan_id).text(nama);
                    $('#list_item_modal').modal('toggle');

                    let sumTotal = 0;

                    $('.total_tagihan_value').each(function() {
                        sumTotal += parseFloat($(this)
                            .val());
                    });
                    $('#grand_total_tagihan_value_' + tagihan_id).val(sumTotal);
                    $('#grand_total_tagihan_tampil_' + tagihan_id).text(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));

                    $('#total_tagihan_all').val(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));

                    $('#grand_total_tagihan_tampil').text('Rp. ' + formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));
                    $('#grand_total_tagihan_value').val(sumTotal);
                });

            });

            function totalharga(tagihan_id) {
                let sumTotal = 0;

                $('.total_tagihan_value').each(function() {
                    sumTotal += parseFloat($(this)
                        .val());
                });
                $('#grand_total_tagihan_value_' + tagihan_id).val(sumTotal);
                $('#grand_total_tagihan_tampil_' + tagihan_id).text(formatRupiah(
                    Math
                    .floor(
                        sumTotal).toString(), 'Rp. '));

                $('#total_tagihan_all').val(formatRupiah(
                    Math
                    .floor(
                        sumTotal).toString(), 'Rp. '));

                $('#grand_total_tagihan_tampil').text(formatRupiah(
                    Math
                    .floor(
                        sumTotal).toString(), 'Rp. '));
                $('#grand_total_tagihan_value').val(sumTotal);
            }

            // $('.prevSpan').on('click', function() {
            //     $(".prevSpan").text("«");
            // });
            $(".cmbItem").on("change", function(e) {
                let item_id = $(this).val();
                let tagihan_id = $(this).data('tagihan_id');
                let jenis_harga = $(this).data('jenis_harga');
                let getharga = 0;
                let total_harga = 0;
                $.when($.ajax({
                    type: 'GET',
                    url: "{{ route('item.detail') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id: item_id,
                        jenis_harga,
                    },
                    success: function(data) {

                        const {
                            harga,
                            harga_malam,
                        } = data.data;

                        if (jenis_harga == 'malam') {
                            $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga_malam
                                .toString(),
                                ' '));
                        } else {
                            $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga
                                .toString(),
                                ' '));
                        }

                    },
                    error: function(data) {
                        Swal.fire({
                            title: 'Oops...',
                            text: "gagal Mengahapus " +
                                modul,
                            footer: '<a href="">terdapat data yang kosong</a>'
                        })
                    }
                })).then(function(data, textStatus, jqXHR) {
                    // totalHarga(modul)
                });
            });
        });
    </script>
@endpush
