{{--
@extends('template.app')
@section('title', 'List Pekerjaan ')

@push('head')
<!-- Load Leaflet from CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
    integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
    crossorigin="" />
<!-- Load Esri Leaflet Geocoder from CDN -->
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.css"
    integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
    crossorigin="">
<style type="text/css">
    #map {
        height: 35vh;
    }

    #mapdua {
        height: 36vh;
    }

    .is-invalid .select2-selection,
    .needs-validation~span>.select2-dropdown {
        border-color: red !important;
    }
</style>
@endpush

@section('content') --}}
<div class="col-md-12">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">



            @foreach ($data  as $key => $pekerjaan)
                <table style="border: 3px solid black;" class="table table-bordered table-responsive" width="100%">
                    <thead>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black; text-align:left;">No Urut Spk</th>

                            <th width="50" colspan="5" style="border: 3px solid black; text-align:left;">{{$key+1}}</th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nomor SPK</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->no_spk}}</b></th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nomor Tiket</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                @if($pekerjaan->hasAduan != null)

                                <b>{{$pekerjaan->hasAduan->no_ticket}}</b>
                                @endif
                            </th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Keterangan</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                @if($pekerjaan->hasAduan != null)

                                <b>{{$pekerjaan->hasAduan->keterangan}}</b>
                                @endif
                            </th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Lokasi Pekerjaan</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">

                                @if($pekerjaan->hasAduan != null)

                                <b>{{$pekerjaan->hasAduan->lokasi}}</b>
                                @endif
                            </th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nama Pekerja</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->rekanan}}</b></th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Total Pekerjaan</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>Rp. {{format_uang($pekerjaan->total_pekerjaan)}}</b></th>

                        </tr>
                        @if ($pekerjaan->rekanan_pkp == 'ya')
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">PPN 11 % </th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>Rp. {{format_uang(($pekerjaan->total_pekerjaan * 11) / 100)}}</b>
                            </th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Grandtotal</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>
                                    Rp. {{
                                    format_uang($pekerjaan->total_pekerjaan + (($pekerjaan->total_pekerjaan * 11) / 100))
                                    }}
                                </b>
                            </th>

                        </tr>
                        @endif
                        @if ($pekerjaan->kode_vourcher)

                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nomor Voucher</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->kode_vourcher}}</b>
                            </th>

                        </tr>
                        @endif
                        @if ($pekerjaan->tanggal_vourcher)
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Tanggal Voucher</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->tanggal_vourcher}}</b>
                            </th>

                        </tr>
                        @endif
                        @if ($pekerjaan->kode_anggaran)
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Kode Anggaran</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->kode_anggaran}}</b>
                            </th>

                        </tr>
                        @endif

                    </thead>
                    <tbody>
                        <tr>
                            <th width="50" style="border: 3px solid black; text-align:center;">Pekerjaan</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Jenis</th>
                            <th width="35" style="border: 3px solid black; text-align:center;">Pengguna</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Jumlah</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Harga Satuan</th>
                            <th width="50" style="border: 3px solid black; text-align:center;">Keterangan</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Total</th>
                        </tr>
                        @forelse ($pekerjaan->hasItem as $key => $item)
                            <tr>
                                <td style="border: 3px solid black; vertical-align: middle;"
                                 rowspan="5"
                                 >{{$item->nama}}</td>
                                <td style="border: 3px solid black; vertical-align: middle;"
                                 rowspan="5"
                                 >{{$item->jenis}}</td>
                                <td style="border: 3px solid black; ">Rekanan</td>
                                <td style="border: 3px solid black; text-align:center;">{{$item->pivot->qty}}</td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.{{ format_uang($item->pivot->harga ) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">{{$item->pivot->keterangan}}</td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.{{ format_uang($item->pivot->qty * $item->pivot->harga) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 3px solid black; ">Pengawas</td>
                                @if (isset($pekerjaan->hasItemPengawas[$key]))
                                    <td style="border: 3px solid black; text-align:center;">
                                        {{ $pekerjaan->hasItemPengawas[$key]->pivot->qty }}
                                    </td>

                                    <td style="border: 3px solid black; text-align:center;">
                                        Rp.
                                        {{ format_uang(
                                        $pekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                    </td>
                                    <td style="border: 3px solid black; text-align:center;">
                                        {{ $pekerjaan->hasItemPengawas[$key]->pivot->keterangan }}
                                    </td>
                                    <td style="border: 3px solid black; text-align:center;">
                                        Rp.
                                        {{ format_uang($pekerjaan->hasItemPengawas[$key]->pivot->qty *
                                        $pekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                    </td>
                                @else
                                    <td style="border: 3px solid black; text-align:center;">-</td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                            <tr>
                                <td style="border: 3px solid black; ">Asisten Manajer Pengawas</td>
                                @if (isset($pekerjaan->hasItemAsmenPengawas[$key]))
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty }}
                                </td>

                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang(
                                    $pekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemAsmenPengawas[$key]->pivot->keterangan }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang($pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty *
                                    $pekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                </td>
                                @else
                                <td style="border: 3px solid black; text-align:center;">-</td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                            <tr>
                                <td style="border: 3px solid black; vertical-align: middle;" rowspan="2">Perencanaan</td>
                                @if (isset($pekerjaan->hasItemPerencanaan[$key]))
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty }}
                                </td>

                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang(
                                    $pekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemPerencanaan[$key]->pivot->keterangan }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang($pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty *
                                    $pekerjaan->hasItemPerencanaan[$key]->pivot->harga) }}
                                </td>
                                @else
                                <td style="border: 3px solid black; text-align:center;">-</td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                            <tr>
                                {{-- <td style="border: 3px solid black; text-align:center;">Perencanaan</td> --}}
                                @if (isset($pekerjaan->hasItemPerencanaanAdujst[$key]))
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->qty }}
                                </td>

                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang(
                                    $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->harga) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->keterangan }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang($pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->qty *
                                    $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->harga) }}
                                </td>
                                @else
                                <td style="border: 3px solid black; text-align:center;">

                                -</td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                        @empty

                        @endforelse

                    </tbody>

                    <tbody>
                        @forelse ($pekerjaan->hasGalianPekerjaan as $gl => $galian)
                        <tr>
                            <td style="border: 3px solid black; vertical-align: middle;" @if ($galian->galian_perencanaan_adjust_panjang && $galian->galian_perencanaan_adjust_lebar && $galian->galian_perencanaan_adjust_dalam)
                            rowspan="5"
                            @else
                            rowspan="4"
                            @endif>{{$item->nama}}</td>
                            <td style="border: 3px solid black; vertical-align: middle;" @if ($galian->galian_perencanaan_adjust_panjang && $galian->galian_perencanaan_adjust_lebar && $galian->galian_perencanaan_adjust_dalam)
                            rowspan="5"
                            @else
                            rowspan="4"
                            @endif>{{$item->jenis}}</td>
                            <td style="border: 3px solid black; ">Rekanan</td>
                            <td style="border: 3px solid black; text-align:center;">
                                {{ str_replace('.', ',', $galian->panjang) }} *
                                {{ str_replace('.', ',', $galian->lebar) }}
                                @if ($galian->dalam !== 0.0)
                                *
                                {{ str_replace('.', ',', $galian->dalam) }}

                                @endif
                                =
                                {{str_replace('.', ',', round($galian->volume_rekanan, 3))}}
                            </td>
                            <td style="border: 3px solid black; text-align:center;">
                                Rp.{{ format_uang($galian->harga ) }}
                            </td>
                            <td style="border: 3px solid black; text-align:center;">{{$galian->keterangan}}</td>
                            <td style="border: 3px solid black; text-align:center;">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 3px solid black; ">Pengawas</td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_pengawas_panjang)
                                {{ str_replace('.', ',', $galian->galian_pengawas_panjang) }}

                                *
                                @endif
                                @if ($galian->galian_pengawas_lebar)
                                {{ str_replace('.', ',', $galian->galian_pengawas_lebar) }}

                                *
                                @endif
                                @if ($galian->galian_pengawas_lebar)
                                {{ str_replace('.', ',', $galian->galian_pengawas_dalam) }}
                                @endif
                                @if ($galian->galian_pengawas_panjang || $galian->galian_pengawas_lebar || $galian->galian_pengawas_lebar )
                                =
                                {{ str_replace('.', ',', round($galian->volume, 3)) }}
                                @endif

                            </td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_pengawas_panjang || $galian->galian_pengawas_lebar || $galian->galian_pengawas_lebar )
                                Rp.{{ format_uang($galian->harga ) }}
                                @endif
                            </td>
                            <td style="border: 3px solid black; text-align:center;">{{$galian->galian_pengawas_keterangan}}</td>
                            <td style="border: 3px solid black; text-align:center;">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 3px solid black; ">Asisten Manajer Pengawas</td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_asmen_pengawas_panjang)
                                {{ str_replace('.', ',', $galian->galian_asmen_pengawas_panjang) }}

                                *
                                @endif
                                @if ($galian->galian_asmen_pengawas_lebar)
                                {{ str_replace('.', ',', $galian->galian_asmen_pengawas_lebar) }}

                                *
                                @endif
                                @if ($galian->galian_asmen_pengawas_dalam)
                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_dalam) }}
                                    =
                                @endif


                                @if ($galian->galian_asmen_pengawas_panjang || $galian->galian_asmen_pengawas_lebar || $galian->galian_asmen_pengawas_dalam )
                                =
                                {{ str_replace('.', ',', round($galian->volume_asmen, 3)) }}
                                @endif

                            </td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_asmen_pengawas_panjang || $galian->galian_asmen_pengawas_lebar ||
                                $galian->galian_asmen_pengawas_dalam )
                                Rp.{{ format_uang($galian->harga ) }}
                                @endif
                            </td>
                            <td style="border: 3px solid black; text-align:center;">{{$galian->galian_asmen_pengawas_keterangan}}</td>
                            <td style="border: 3px solid black; text-align:center;">
                                -
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 3px solid black; vertical-align: middle;"
                            @if ($galian->galian_perencanaan_adjust_panjang && $galian->galian_perencanaan_adjust_lebar && $galian->galian_perencanaan_adjust_dalam)
                            rowspan="2"
                            @endif
                            >Perencanaan</td>
                            <td style="border: 3px solid black; text-align:center;">

                                @if ($galian->galian_asmen_pengawas_panjang)
                                {{ str_replace('.', ',', $galian->galian_asmen_pengawas_panjang) }}

                                *
                                @endif
                                @if ($galian->galian_asmen_pengawas_lebar)
                                {{ str_replace('.', ',', $galian->galian_asmen_pengawas_lebar) }}

                                *
                                @endif
                                @if ($galian->galian_asmen_pengawas_dalam)
                                {{ str_replace('.', ',', $galian->galian_asmen_pengawas_dalam) }}
                                =
                                @endif


                                @if ($galian->galian_asmen_pengawas_panjang || $galian->galian_asmen_pengawas_lebar ||
                                $galian->galian_asmen_pengawas_dalam )
                                =
                                {{ str_replace('.', ',', round($galian->volume_asmen, 3)) }}
                                @endif
                            </td>
                            <td style="border: 3px solid black; text-align:center;">

                                @if ($galian->galian_perencanaan_harga_satuan)
                                Rp.{{ format_uang($galian->galian_perencanaan_harga_satuan ) }}
                                @endif

                            </td>
                            <td style="border: 3px solid black; text-align:center;">{{$galian->galian_perencanaan_keterangan}}</td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_perencanaan_harga_satuan)
                                Rp.
                                {{ format_uang($galian->galian_perencanaan_harga_satuan * $galian->volume_asmen) }}
                                @endif
                            </td>
                        </tr>
                        @if ($galian->galian_perencanaan_adjust_panjang && $galian->galian_perencanaan_adjust_lebar && $galian->galian_perencanaan_adjust_dalam)

                        <tr>
                            {{-- <td style="border: 3px solid black; text-align:center;">Perencanaan</td> --}}
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_perencanaan_adjust_panjang)
                                {{ str_replace('.', ',', $galian->galian_perencanaan_adjust_panjang) }}

                                *
                                @endif
                                @if ($galian->galian_perencanaan_adjust_panjang)
                                {{ str_replace('.', ',', $galian->galian_perencanaan_adjust_panjang) }}

                                *
                                @endif
                                @if ($galian->galian_perencanaan_adjust_lebar)
                                {{ str_replace('.', ',', $galian->galian_perencanaan_adjust_lebar) }}
                                =
                                @endif


                                @if ($galian->galian_perencanaan_adjust_panjang || $galian->galian_perencanaan_adjust_panjang ||
                                $galian->galian_perencanaan_adjust_lebar )
                                =
                                {{ str_replace('.', ',', round($galian->volume_adjust, 3)) }}
                                @endif

                            </td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_perencanaan_adjust_harga_satuan)
                                Rp.{{ format_uang($galian->galian_perencanaan_adjust_harga_satuan ) }}
                                @endif
                            </td>
                            <td style="border: 3px solid black; text-align:center;">{{$galian->galian_perencanaan_adjust_keterangan}}</td>
                            <td style="border: 3px solid black; text-align:center;">
                                @if ($galian->galian_perencanaan_adjust_harga_satuan)
                                Rp.
                                {{ format_uang($galian->galian_perencanaan_adjust_total) }}
                                @endif
                            </td>
                        </tr>
                        @endif
                        @empty

                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="6" style="border: 3px solid black; text-align:right;">
                                 Total
                            </th>
                            <th style="border: 3px solid black; text-align:center;">
                                Rp.
                                {{ format_uang($pekerjaan->total_pekerjaan) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            @endforeach

        </div>
    </div>
    <!-- ./col -->
</div>
{{-- @stop --}}
