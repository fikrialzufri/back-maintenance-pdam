@extends('template.app')
@section('title', 'Detail Pekerjaan ' . $aduan->no_aduan)

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

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    @if ($aduan->status != 'draft')
                        <div class="card-header">
                            <div class="card-title">Detail Pekerjaan</div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">

                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="no_ticket" class=" form-control-label">No SPK : </label>
                                            </div>
                                            <div>
                                                <h2>
                                                    <strong>{{ $aduan->no_spk }}</strong>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="no_ticket" class=" form-control-label">Nama Pekerja :</label>
                                            </div>
                                            <div>
                                                <h2>
                                                    <strong>{{ $aduan->rekanan }}</strong>
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <div>
                                                <label for="no_ticket" class=" form-control-label">Keterangan Penambahan
                                                    Item</label>
                                            </div>
                                            <div>
                                                <h2>
                                                    <strong>{{ $aduan->keterangan_barang }}</strong>
                                                </h2>
                                            </div>

                                        </div>
                                    </div>
                                    @if (!auth()->user()->hasRole('admin-asisten-manajer'))
                                        @if ($pekerjaanUtama)
                                            @if ($pekerjaanUtama->status == 'selesai koreksi' || $pekerjaanUtama->status == 'diadjust')
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <div>
                                                            <label for="no_ticket" class=" form-control-label">Total Tagihan
                                                                Pekerjaan</label>
                                                        </div>
                                                        <div>
                                                            <h3>
                                                                <strong id="total_tagihan_pekerjaan">Rp.
                                                                    {{ format_uang($aduan->total_pekerjaan) }}</strong>
                                                            </h3>
                                                        </div>

                                                    </div>
                                                </div>
                                            @endif

                                        @endif
                                    @endif
                                    <div class="col-12">
                                        <div class="form-group">
                                            <h6 class="">Alamat Pekerjaan</h6>
                                        </div>
                                        <div>
                                            <a href="https://maps.google.com/?q={{ $lat_long_pekerjaan }}"
                                                target="__blank" class="text-danger">
                                                {{ $lokasi_pekerjaan }}
                                            </a>
                                        </div>
                                        <br>

                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <h6 class="">Lokasi Pekerjaan</h6>
                                        </div>
                                        <div id="mapdua"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row d-flex flex-row">
                                        <div class="col-12">
                                            <h6 class="">Foto Pekerjaan</h6>
                                        </div>
                                    </div>
                                    <div class="row d-flex flex-row">
                                        @foreach ($fotoPekerjaan as $ftpekerjaan)
                                            <div class="p-2 pop">
                                                <img src="{{ $ftpekerjaan['url'] }}" width="100px" alt="1"
                                                    class="img-thumbnail rounded mx-auto d-block">
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <div class="row d-flex flex-row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <h6 class="">Foto Bahan</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex flex-row">

                                        @foreach ($fotoBahan as $ftbahan)
                                            <div class="p-2 pop">
                                                <img src="{{ $ftbahan['url'] }}" width="100px" alt="1"
                                                    class="img-thumbnail rounded mx-auto d-block">
                                            </div>
                                        @endforeach
                                    </div>
                                    <hr>
                                    <div class="row d-flex flex-row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <h6 class="">Foto Penyelesaian
                                                    Pekerjaan</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row d-flex flex-row">

                                        @foreach ($fotoPenyelesaian as $ftpenyelesaian)
                                            <div class="p-2 pop">
                                                <img src="{{ $ftpenyelesaian['url'] }}" width="100px" alt="1"
                                                    class="img-thumbnail rounded mx-auto d-block">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                        </div>
                    @else
                        @canany(['edit-penunjukan-pekerjaan', 'create-penunjukan-pekerjaan'])
                            <div class="card-header">
                                <div class="card-title">Pilih Pekerja</div>
                            </div>
                            <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                                @csrf
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="hidden" name="slug" value="{{ $aduan->slug }}">
                                                <div>
                                                    <select name="rekanan_id" class="selected2 form-control" id="cmbRekanan">
                                                        <option value="">--Pilih Pekerja--</option>
                                                        @foreach ($rekanan as $rek)
                                                            <option value="{{ $rek->id }}"
                                                                {{ old('rekanan_id') == $rek->id ? 'selected' : '' }}>
                                                                {{ $rek->nama }}
                                                            </option>
                                                        @endforeach
                                                        @foreach ($karyawanPekerja as $kary)
                                                            <option value="{{ $kary->id }}"
                                                                {{ old('rekanan_id') == $kary->id ? 'selected' : '' }}>
                                                                {{ $kary->nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @if ($errors->has('rekanan_id'))
                                                        <span class="text-danger">
                                                            <strong
                                                                id="textrule">{{ $errors->first('rekanan_id') }}</strong>
                                                        </span>
                                                    @endif
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-footer clearfix">
                                    <button type="submit" class="btn btn-primary">Simpan</button>
                                </div>
                            </form>
                        @endcanany
                    @endif
                </div>
            </div>
        </div>
        <div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <img src="" class="imagepreview" style="width: 100%;">
                    </div>
                </div>
            </div>
        </div>
        {{-- koreksi --}}

        @if ($aduan->status != 'draft')
            @if (!auth()->user()->hasRole('admin-asisten-manajer'))
                @isset($pekerjaanUtama)
                    <input type="hidden" name="id_pekerjaan" id="idPekerjaan" value="{{ $pekerjaanUtama->id }}">
                    <div class="row">
                        <div class="col-12">

                            <div class="card">
                                <div class="card-header  justify-content-between">
                                    <div class="row">

                                        <div class="col-12">
                                            <h3 class="card-title">Daftar Pekerjaan</h3>
                                            <hr>
                                        </div>
                                        @if ($pengawas === true && $pekerjaanUtama->status === 'selesai')
                                            <div class="col-12">
                                                <form action="" id="formPekerjaan">
                                                    @if ($rekanan_id == null)
                                                        @if ($tombolEdit === 'bisa')
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <h6>Pilih pekerjaan</h6>

                                                                    <div class="form-group">
                                                                        <select class="form-control select2"
                                                                            id="cmbPekerjaan">
                                                                            <option selected="selected" value="">Pilih
                                                                                pekerjaan
                                                                            </option>
                                                                            @foreach ($listPekerjaan as $i => $peker)
                                                                                <option value="{{ $peker->id }}"
                                                                                    id="peker_{{ $peker->id }}">
                                                                                    {{ $peker->nama }}
                                                                                </option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <h6>Jumlah</h6>
                                                                    <div class="form-group">
                                                                        <div class="input-group mb-2 mr-sm-2">
                                                                            <input type="text" name="jumlah_pekerjaan"
                                                                                id="jumlah_pekerjaan" placeholder="jumlah"
                                                                                class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <h6>Keterangan</h6>
                                                                    <div class="form-group">
                                                                        <div class="input-group mb-2 mr-sm-2">
                                                                            <input type="text"
                                                                                name="input_keterangan_pekerjaan"
                                                                                id="input_keterangan_pekerjaan"
                                                                                placeholder="Keterangan pekerjaan"
                                                                                class="form-control">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <h6>Pilih Waktu</h6>
                                                                    <div class="form-radio">
                                                                        <form>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio"
                                                                                        class="harga_pekerjaan"
                                                                                        name="harga_pekerjaan" value="siang"
                                                                                        checked="checked">
                                                                                    <i class="helper"></i>Siang
                                                                                </label>
                                                                            </div>
                                                                            <div class="radio radiofill radio-inline">
                                                                                <label>
                                                                                    <input type="radio"
                                                                                        class="harga_pekerjaan"
                                                                                        name="harga_pekerjaan" value="malam">
                                                                                    <i class="helper"></i>Malam
                                                                                </label>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                                <div class="col">
                                                                    <div class="">
                                                                        <button type="submit" id="btn_pekerjaan"
                                                                            class="btn btn-primary">Update
                                                                            Pekerjaan</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </form>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ $action }}" method="post" id="form-update" role="form">
                                    {{ csrf_field() }}
                                    {{ method_field('PUT') }}
                                    <div class="card-body">
                                        <table class="table table-bordered table-responsive" width="100%"
                                            id="tablePekerjaan">
                                            <thead>
                                                <tr>
                                                    <th width="5">#</th>
                                                    <th width="500">Pekerjaan</th>
                                                    <th width="50">Jenis</th>
                                                    <th width="50">Pengguna</th>
                                                    <th width="150">Jumlah</th>
                                                    <th width="200">Keterangan</th>
                                                    <th width="50">Aksi</th>
                                                    @if ($pekerjaanUtama->status === 'selesai koreksi')
                                                        <th width="250">Total</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($daftarPekerjaan->hasItem))
                                                    @forelse ($daftarPekerjaan->hasItem as $key => $pekerjaan)
                                                        <tr id="listPekerjaan_{{ $pekerjaan->id }}"
                                                            class="list_table_pekerjaan">
                                                            <td class="text-center nomor_pekerjaan"
                                                                data-index="{{ $key + 1 }}"
                                                                @if ($pekerjaanUtama->status === 'dikoreksi') rowspan="3" @else rowspan="2" @endif>
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td
                                                                @if ($pekerjaanUtama->status === 'dikoreksi') rowspan="3" @else rowspan="2" @endif>
                                                                {{ $pekerjaan->nama }}
                                                            </td>
                                                            <td
                                                                @if ($pekerjaanUtama->status === 'dikoreksi') rowspan="3" @else rowspan="2" @endif>
                                                                {{ $pekerjaan->jenis }}
                                                            </td>
                                                            <td>Rekanan</td>
                                                            <td>{{ $pekerjaan->pivot->qty }}</td>
                                                            <td>{{ $pekerjaan->pivot->keterangan }}</td>
                                                            @if ($pekerjaanUtama->status === 'selesai koreksi')
                                                                <td rowspan="3">
                                                                    Rp. {{ format_uang($pekerjaan->pivot->total) }}</td>
                                                            @endif

                                                        </tr>
                                                        <tr>
                                                            <td>Pengawas</td>
                                                            @if ($pengawas === true && $pekerjaanUtama->status === 'selesai')
                                                                <td><input type="text"
                                                                        name="qty_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                        id="qty_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                        placeholder="Koreksi Pengawas" class="form-control">
                                                                </td>
                                                                <td><input type="text"
                                                                        name="keterangan_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                        id="keterangan_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                        placeholder="Keterangan Pengawas"
                                                                        class="form-control">
                                                                </td>
                                                            @else
                                                            @endif
                                                        </tr>
                                                        @if ($pekerjaanUtama->status === 'dikoreksi')
                                                            <tr>
                                                                <td>Perencanaan</td>
                                                                <td></td>
                                                            </tr>
                                                        @endif
                                                    @empty
                                                        <tr class="pekerjaanTidakAda">
                                                            <td colspan="10">Data Pekerjaan tidak ada</td>
                                                        </tr>
                                                    @endforelse
                                                @else
                                                    <tr class="pekerjaanTidakAda">
                                                        <td colspan="10">Data Pekerjaan tidak ada</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                @if ($perencaan == true)
                                                    @if (isset($daftarPekerjaan->hasItem))
                                                        <tr>
                                                            <th @if ($pekerjaanUtama->status === 'diadjust') colspan="13" @elseif ($pekerjaanUtama->status === 'selesai koreksi') colspan="10" @else colspan="9" @endif
                                                                class="text-right">Total
                                                            </th>
                                                            <th>
                                                                <span id="grand_total_pekerjaan_tampil">
                                                                    Rp.
                                                                    {{ format_uang($daftarPekerjaan->hasItem->sum('pivot.total')) }}
                                                                </span>
                                                                <input type="hidden" id="grand_total_pekerjaan_value"
                                                                    name="grand_total_pekerjaan"
                                                                    value="{{ $daftarPekerjaan->hasItem->sum('pivot.total') }}"
                                                                    class="grand_total_pekerjaan total_tagihan">
                                                            </th>

                                                        </tr>
                                                    @endif
                                                @endif
                                            </tfoot>

                                        </table>
                                        {{-- Galian --}}
                                        <table class="table table-bordered table-responsive" width="100%" id="tableGalian">
                                            <thead>
                                                <tr>
                                                    <th width="5">#</th>
                                                    <th width="500">Galian</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($daftarGalian))
                                                    @forelse ($daftarGalian as $inv => $galian)
                                                        @php
                                                            $sum = 0;
                                                        @endphp
                                                        <tr id="listgalian_{{ $galian->item_id }}"
                                                            class="list_table_galian">
                                                            <td class="text-center nomor_galian"
                                                                data-index="{{ $inv + 1 }}">
                                                                {{ $inv + 1 }}
                                                            </td>
                                                            <td>
                                                                {{ $galian->pekerjaan }}
                                                            </td>


                                                        </tr>
                                                    @empty
                                                        <tr class="galianTidakAda">
                                                            <td colspan="10">Data galian tidak ada</td>
                                                        </tr>
                                                    @endforelse
                                                @else
                                                    <tr class="galianTidakAda">
                                                        <td colspan="10">Data galian tidak ada</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                @if ($perencaan == true)
                                                @endif
                                                @if (isset($daftarGalian))
                                                    <tr>
                                                        <th colspan="5"> Total
                                                        </th>
                                                        <th class="text-center">
                                                            {{ $pekerjaanUtama->luas_galian }} m<sup>2</sup>
                                                        </th>
                                                        <th></th>
                                                        <th class="text-center">
                                                            {{ $daftarGalian->sum('qty_pengawas') }} m<sup>2</sup>
                                                        </th>
                                                        @if ($perencaan === true)
                                                            <th colspan="3">

                                                            </th>
                                                            @if ($pekerjaanUtama->status === 'diadjust')
                                                                <th>
                                                                    {{ $daftarGalian->sum('qty_perencanaan_adjust') }}
                                                                    m<sup>2</sup>
                                                                </th>
                                                                <th></th>
                                                                <th></th>
                                                            @endif

                                                            <th>Rp.
                                                                {{ format_uang($daftarGalian->sum('total')) }}
                                                            </th>
                                                        @endif
                                                    </tr>
                                                    <tr>
                                                        <th
                                                            @if ($pekerjaanUtama->status === 'diadjust') colspan="14" @else colspan="11" @endif>
                                                            Grand Total
                                                        </th>
                                                        <th>Rp.
                                                            {{ format_uang($pekerjaanUtama->total_pekerjaan) }}
                                                        </th>
                                                    </tr>
                                                @endif
                                            </tfoot>

                                        </table>
                                        {{-- End Galian --}}
                                    </div>
                                    <div class="card-footer">
                                        @if (!auth()->user()->hasRole('rekanan'))
                                            @if ($tombolEdit === 'bisa')
                                                <div class="row">
                                                    <div class="col-12">
                                                        <input type="hidden" name="pekerjan" id="pekerjan"
                                                            value="{{ $pekerjaanUtama->id }}">
                                                        <input type="hidden" name="no_spk" id="no_spk"
                                                            value="{{ $aduan->no_spk }}">
                                                        <div class="card">
                                                            <button type="button" id="simpan_koreksi"
                                                                class="btn btn-primary">Simpan Koreksi
                                                                Pekerjaan</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                @endisset
            @endif
        @endif


        {{-- Detail Aduan --}}
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    {{-- <form> --}}
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="no_ticket" class=" form-control-label">Nomor Tiket</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->no_ticket }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="mps" class=" form-control-label">MPS</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->mps }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="atas_nama" class=" form-control-label">Atas Nama</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->atas_nama }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="kategori_aduan" class=" form-control-label">Kategori Aduan</label>
                                    </div>
                                    <div>
                                        <strong>{{ ucfirst($aduan->kategori_aduan) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="sumber_informasi" class=" form-control-label">Sumber Informasi</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->sumber_informasi }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="nama_pelanggan" class=" form-control-label">Nama Pelanggan</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->nama_pelanggan }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="no_pelanggan" class=" form-control-label">Nomor Pelanggan</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->no_pelanggan }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="jenis_aduan_id" class=" form-control-label">Jenis Aduan</label>
                                    </div>
                                    <div>
                                        @forelse ($aduan->hasJenisAduan as $index => $item)
                                            <div>
                                                <ul>
                                                    <li>{{ $item->nama }}</li>
                                                </ul>
                                            </div>
                                        @empty
                                            <strong>Jenis Aduan Kosong</strong>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="keterangan" class=" form-control-label">Keterangan</label>
                                    </div>
                                    <div>
                                        <strong>{!! $aduan->sumber_informasi !!}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Detail Lokasi</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="lokasi" class=" form-control-label">Lokasi</label>
                                    </div>
                                    <div>
                                        <a href="https://maps.google.com/?q={{ $aduan->lat_long }}" target="__blank"
                                            class="text-danger">
                                            <strong>{{ $aduan->lokasi }}</strong>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="detail_lokasi" class=" form-control-label">Detail Lokasi</label>
                                    </div>
                                    <div>
                                        <a href="https://maps.google.com/?q={{ $aduan->lat_long }}" target="__blank"
                                            class="text-danger">
                                            <strong>{{ $aduan->detail_lokasi }}</strong>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="lat_long" class=" form-control-label">Koordinat (Latitude,
                                            Longitude)</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->lat_long }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="map"></div>
                            </div>
                        </div>
                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>
@stop

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        let id = $('#idPekerjaan').val();

        function capitalizeFirstLetter(string) {
            return string.replace(/^./, string[0].toUpperCase());
        }

        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function formatRupiahTanpaRp(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            // tambahkan titik jika yang di input sudah menjadi angka ribuan
            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
            return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
        }
        // -- galian

        $('#simpan_koreksi').on('click', function() {
            let spk = $('#no_spk').val();

            const swalWithBootstrapButtons = swal.mixin({
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                buttonsStyling: false,
            });
            swalWithBootstrapButtons({
                title: "Anda Yakin ?",
                text: "Mengoreksi Pekerjaan : " + spk,
                type: "question",
                showCancelButton: true,
                confirmButtonText: "Ya, yakin Mengoreksi data!",
                cancelButtonText: "Tidak, kembali!",
            }).then((result) => {
                if (result.value) {
                    swalWithBootstrapButtons(
                        "Mengoreksi!",
                        "data anda telah dikoreksi.",
                        "success"
                    );
                    document.getElementById("form-update").submit();
                } else if (
                    // Read more about handling dismissals
                    result.dismiss === swal.DismissReason.cancel
                ) {
                    swalWithBootstrapButtons(
                        "Kembali",
                        "Mohon berhati-hati untuk mengoreksi data",
                        "error"
                    );
                }
            });
        });
        $('.pop').on('click', function() {
            $('.imagepreview').attr('src', $(this).find('img').attr('src'));
            $('#imagemodal').modal('show');
        });

        function toast(text) {
            $.toast({
                heading: 'Success',
                text: text,
                showHideTransition: 'slide',
                icon: 'success',
                loaderBg: '#f2a654',
                position: 'top-right'
            })
        }

        $(document).on('keypress', '.numberOnly', function(event) {
            if (event.which < 46 ||
                event.which > 59) {
                event.preventDefault();
            } // prevent if not number/dot

            if (event.which == 46 &&
                $(this).val().indexOf('.') != -1) {
                event.preventDefault();
            } // prevent if already dot
        })

        // -- end galian
        $(document).ready(function() {
            $('#cmbPekerjaan').select2({
                placeholder: '--- Pilih Pekerjaan ---',
                width: '100%'
            });

            $("#cmbPekerjaan").on("change", function(e) {
                $('#cmbPekerjaan').parent().removeClass('is-invalid')
            });


            $('#jumlah_pekerjaan').keypress(function(event) {
                if (event.which < 46 ||
                    event.which > 59) {
                    event.preventDefault();
                } // prevent if not number/dot

                if (event.which == 46 &&
                    $(this).val().indexOf('.') != -1) {
                    event.preventDefault();
                } // prevent if already dot
                $(this).removeClass("is-invalid");
            })

            $('#formPekerjaan').on('submit', function(e) {
                e.preventDefault();
                let modul = 'pekerjaan';
                saveform(modul);
            });



            function elementPekerjaan(id, nama, slug, jenis, nomor, modul, jumlah, keterangan = null) {
                let modulLowcasse = capitalizeFirstLetter(modul);
                let pekerjaanUtama = $('#idPekerjaan').val();

                let elementTotal = `<tr id="list${modulLowcasse}_${id}" class="list_table_${modul}">
                    <td class="text-center nomor_${modul}" data-index="${nomor}">${nomor}
                    </td>
                    <td>${nama}</td>
                    <td>${jenis}</td>
                    <td>pengawas</td>
                    <td>
                        <input type="text" name="qty_pengawas[${id}]"
                        id="qty_pengawas_${id}" value="${jumlah}"
                        placeholder="Koreksi Pengawas" class="form-control numberOnly">
                    </td>
                    <td>
                        <input type="text" name="keterangan_pengawas[${id}]"
                        id="keterangan_pengawas[${id}]" value="${keterangan}"
                        placeholder="Keterangan Pengawas" class="form-control ">
                    </td>
                    <td>
                            <button type="button"
                                class="btn btn-danger btn-xs text-center btn-hapus"
                                data-pekerjaanutama="${pekerjaanUtama}"
                                data-modul="${modul}" data-item="${id}">
                                <i class="fa fa-trash"></i>
                                Hapus
                            </button>
                    </td>
                </tr>`;

                return elementTotal;

            }

            function tombol() {

                $(document).on("click", ".btn-hapus", function(e) {
                    let id = $(this).data('pekerjaanutama');
                    let modul = $(this).data('modul');
                    let item = $(this).data('item');
                    let content = '';
                    let modulLowcasse = capitalizeFirstLetter(modul);
                    let itemLength = $('#list' + modulLowcasse + '_' + item).length;
                    if (itemLength > 0) {
                        $('#list' + modulLowcasse + '_' + item).remove();

                        $('#table' + modulLowcasse).append(content);
                    }
                    let n = 1;
                    $('.nomor_' + modul).each(function(index, item) {
                        let number = n++;
                        $(item).text(number);
                        $(this).attr('data-index', number);
                    });

                    toast('success hapus ' + modul)

                });
                $(document).on("click", ".btn-edit", function(e) {
                    let id = $(this).data('pekerjaanutama');
                    let modul = $(this).data('modul');
                    let item = $(this).data('item');
                    let modulLowcasse = capitalizeFirstLetter(modul);
                    $('#cmb' + modulLowcasse).val(item).trigger('change');
                    let getjumlah = $('#jumlah_' + modul + '_value_' + item).val();
                    let getketerangan = $('#keterangan_' + modul + '_value_' + item).val();
                    $('#jumlah_' + modul + '_tampil').val(getjumlah);
                    $('#jumlah_' + modul).val(getjumlah);
                    $('#keterangan_' + modul).val(getketerangan);
                });
            }

            tombol()

            function saveform(modul) {

                let modulLowcasse = capitalizeFirstLetter(modul);
                let item = $('#cmb' + modulLowcasse).val();
                let jumlah = $('#jumlah_' + modul).val();
                let keterangan = $('#input_keterangan_' + modul).val();

                if (item === "") {
                    $('#cmb' + modulLowcasse).parent().addClass('is-invalid')
                }
                if (jumlah === "") {
                    $('#jumlah_' + modul).addClass("is-invalid");
                }

                if (item !== "" && jumlah !== "") {
                    let lengthPekerjaan = $('#list' + modulLowcasse + '_' + item).length;
                    let tableCount = $('.nomor_pekerjaan').length;

                    let nomor = tableCount + 1;


                    if (lengthPekerjaan === 0) {
                        $.when($.ajax({
                            type: 'get',
                            url: "{{ route('item.api.index') }}/" + item,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                id
                            },
                            success: function(data) {
                                const {
                                    id,
                                    nama,
                                    slug,
                                    jenis
                                } = data.data;
                                let content = elementPekerjaan(
                                    id, nama, slug, jenis, nomor, modul, jumlah, keterangan
                                );

                                $('#table' + modulLowcasse).append(content);
                                toast('success menambah ' + modulLowcasse);
                                $('#cmb' + modulLowcasse).val(null).trigger('change');
                                $('#jumlah_' + modul).val('');
                                $('#input_keterangan_' + modul).val('');


                            },

                            error: function(data) {
                                Swal.fire({
                                    title: 'Oops...',
                                    text: "Isi dengan lengkap",
                                    footer: '<a href="">terdapat data yang kosong</a>'
                                })

                            }
                        })).then(function(data, textStatus, jqXHR) {
                            // totalHarga(modul);


                        });

                    }

                } else {
                    Swal.fire({
                        title: 'Oops...',
                        text: "Isi data dengan lengkap",
                        footer: '<a href="">terdapat data yang kosong</a>'
                    })
                }

            }

            // --- End Galian
        });
    </script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"
        integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA=="
        crossorigin=""></script>

    <!-- Load Esri Leaflet from CDN -->
    <script src="https://unpkg.com/esri-leaflet@3.0.7/dist/esri-leaflet.js"
        integrity="sha512-ciMHuVIB6ijbjTyEdmy1lfLtBwt0tEHZGhKVXDzW7v7hXOe+Fo3UA1zfydjCLZ0/vLacHkwSARXB5DmtNaoL/g=="
        crossorigin=""></script>

    <script src="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.js"
        integrity="sha512-8bfbGLq2FUlH5HesCEDH9UiuUCnBq0A84yYv+LkUNPk/C2z81PsX2Q/U2Lg6l/QRuKiT3y2De2fy9ZPLqjMVxQ=="
        crossorigin=""></script>

    <script>
        var lat_long = "{{ $aduan->lat_long }}";
        var lokasi = "{{ $aduan->lokasi }}";

        var map = L.map('map').setView(lat_long.split(","), 80);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker(lat_long.split(",")).addTo(map)
            .bindPopup('<b>' + lokasi + '</b>').openPopup();
    </script>
    @if ($aduan->status != 'draft')
        <script>
            var lat_long_pekerjaan = "{{ $lat_long_pekerjaan }}";
            var lokasi_pekerjaan = "{{ $lokasi_pekerjaan }}";
            if (lat_long_pekerjaan != '') {

                var mapdua = L.map('mapdua').setView(lat_long_pekerjaan.split(","), 80);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(mapdua);

                L.marker(lat_long_pekerjaan.split(",")).addTo(mapdua)
                    .bindPopup('<b>' + lokasi_pekerjaan + '</b>').openPopup();
            }
        </script>
    @endif
@endpush
