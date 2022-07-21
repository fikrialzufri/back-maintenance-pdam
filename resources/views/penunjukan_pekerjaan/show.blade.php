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
                                    <div class="col-12 timeline">
                                        <h6>List Persetujuan Pekerjaan</h6>
                                        <ul>
                                            @forelse ($list_persetujuan as $item)
                                                <li>
                                                    <div class="bullet bg-primary"></div>
                                                    <div class="time">{{ $item->tanggal_disetujui }}</div>
                                                    <div class="desc">
                                                        <p>{{ $item->nama }} - {{ $item->jabatan }}</p>
                                                    </div>
                                                </li>
                                            @empty
                                            @endforelse


                                        </ul>
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
                                            <a href="https://maps.google.com/?q={{ $lat_long_pekerjaan }}" target="__blank"
                                                class="text-danger">
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
                                                <h6 class="">Foto Pekerjaan, Bahan, Alat Bantu dan Galian</h6>
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

                        @if ($penunjukan->status_mobile < 3 && $penunjukan->status !== 'approve')
                            @if ($pekerjaanUtama)
                                @if ($pekerjaanUtama->status_mobile < 2)
                                    @canany(['edit-penunjukan-pekerjaan', 'create-penunjukan-pekerjaan'])
                                        <div class="card-header">
                                            <div class="card-title">Pilih Pekerja</div>
                                        </div>
                                        <form action="{{ $action }}" method="post" role="form"
                                            enctype="multipart/form-data">
                                            @csrf
                                            {{ method_field('PUT') }}
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <input type="hidden" name="slug" value="{{ $aduan->slug }}">
                                                            <div>
                                                                <select name="rekanan_id" class="selected2 form-control"
                                                                    id="cmbRekanan" required>
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
                            @else
                                @canany(['edit-penunjukan-pekerjaan', 'create-penunjukan-pekerjaan'])
                                    <div class="card-header">
                                        <div class="card-title">Pilih Pekerja</div>
                                    </div>
                                    <form action="{{ $action }}" method="post" role="form"
                                        enctype="multipart/form-data">
                                        @csrf
                                        {{ method_field('PUT') }}
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="hidden" name="slug" value="{{ $aduan->slug }}">
                                                        <div>
                                                            <select name="rekanan_id" class="selected2 form-control"
                                                                id="cmbRekanan" required>
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
                        @endif
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
                                                    <select name="rekanan_id" class="selected2 form-control" id="cmbRekanan"
                                                        required>
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
                                                            <strong id="textrule">{{ $errors->first('rekanan_id') }}</strong>
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
                                        @if ($pengawas === true && $pekerjaanUtama->status === 'approve')
                                            <div class="col-12">
                                                <form action="" id="formPekerjaan">
                                                    @if ($rekanan_id == null)
                                                        @if ($tombolEdit === 'bisa')
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <h6>Pilih Pekerjaan</h6>

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
                                                                                class="form-control numberOnly">
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
                                                                            class="btn btn-primary">Tambah Pekerjaan</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endif
                                                </form>
                                            </div>
                                        @endif
                                        @if ($asmenpengawas === true && $pekerjaanUtama->status === 'koreksi pengawas')
                                            <div class="col-12">
                                                <form action="" id="formPekerjaan">
                                                    @if ($rekanan_id == null)
                                                        @if ($tombolEdit === 'bisa')
                                                            <div class="row">
                                                                <div class="col-2">
                                                                    <h6>Pilih Pekerjaan</h6>

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
                                                                                class="form-control numberOnly">
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
                                                                            class="btn btn-primary">Tambah
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
                                                    <th width="150">Jumlah </th>
                                                    @if ($perencaan == false && $pekerjaanUtama->status === 'dikoreksi')
                                                        <th width="300">Keterangan</th>
                                                    @endif
                                                    @if ($pekerjaanUtama->status === 'approve' || $pekerjaanUtama->status === 'koreksi pengawas')
                                                        <th width="300">Keterangan</th>
                                                    @endif
                                                    @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                        <th width="250">Harga Satuan</th>
                                                        <th width="300">Keterangan</th>
                                                        <th width="250">Total</th>
                                                    @endif

                                                    @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                        <th width="250">Harga Satuan</th>
                                                        <th width="300">Keterangan</th>
                                                        <th width="250">Total</th>
                                                    @endif

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($daftarPekerjaan->hasItem))
                                                    @forelse ($daftarPekerjaan->hasItem as $key => $pekerjaan)
                                                        <tr id="listPekerjaan_{{ $pekerjaan->pivot->item_id }}"
                                                            class="list_table_pekerjaan">
                                                            <td class="text-center nomor_pekerjaan"
                                                                data-index="{{ $key + 1 }}"
                                                                @if ($pekerjaanUtama->status === 'koreksi pengawas') rowspan="3"
                                                                @elseif($pekerjaanUtama->status === 'dikoreksi' || $pekerjaanUtama->status == 'selesai koreksi') rowspan="4"
                                                                @elseif ($pekerjaanUtama->status === 'diadjust') rowspan="5"
                                                                @else rowspan="2" @endif>
                                                                {{ $key + 1 }}
                                                            </td>
                                                            <td
                                                                @if ($pekerjaanUtama->status === 'koreksi pengawas') rowspan="3"
                                                                @elseif($pekerjaanUtama->status === 'dikoreksi' || $pekerjaanUtama->status == 'selesai koreksi') rowspan="4"
                                                                @elseif ($pekerjaanUtama->status === 'diadjust') rowspan="5"
                                                                @else rowspan="2" @endif>
                                                                {{ $pekerjaan->nama }}
                                                            </td>
                                                            <td
                                                                @if ($pekerjaanUtama->status === 'koreksi pengawas') rowspan="3"
                                                                @elseif($pekerjaanUtama->status === 'dikoreksi' || $pekerjaanUtama->status == 'selesai koreksi') rowspan="4"
                                                                @elseif ($pekerjaanUtama->status === 'diadjust') rowspan="5"
                                                                @else rowspan="2" @endif>
                                                                {{ $pekerjaan->jenis }}
                                                            </td>
                                                            <td>Rekanan</td>
                                                            <td>{{ str_replace('.', ',', $pekerjaan->pivot->qty) }}</td>
                                                            @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                <td>Rp. {{ format_uang($pekerjaan->pivot->harga) }}</td>
                                                                <td>{{ $pekerjaan->pivot->keterangan }}</td>
                                                                <td>
                                                                    Rp.{{ format_uang($pekerjaan->pivot->qty * $pekerjaan->pivot->harga) }}
                                                                </td>
                                                            @endif

                                                            @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                                <td>Rp. {{ format_uang($pekerjaan->pivot->harga) }}</td>
                                                                <td>{{ $pekerjaan->pivot->keterangan }}</td>
                                                                <td>
                                                                    Rp.{{ format_uang($pekerjaan->pivot->qty * $pekerjaan->pivot->harga) }}
                                                                </td>
                                                            @endif


                                                        </tr>
                                                        <tr>
                                                            <td>Pengawas</td>
                                                            @if ($pengawas === true && $pekerjaanUtama->status === 'approve')
                                                                <td><input type="text"
                                                                        name="qty_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                        id="qty_pengawas_{{ $pekerjaan->pivot->item_id }}"
                                                                        placeholder="Koreksi Pengawas"
                                                                        class="form-control numberOnly"
                                                                        value="{{ $pekerjaan->pivot->qty }}">
                                                                </td>
                                                                <td><input type="text"
                                                                        name="keterangan_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                        id="keterangan_pengawas_{{ $pekerjaan->pivot->item_id }}"
                                                                        placeholder="Keterangan Pengawas"
                                                                        class="form-control">
                                                                </td>
                                                            @else
                                                                @if (isset($daftarPekerjaan->hasItemPengawas[$key]))
                                                                    <td>
                                                                        {{ str_replace('.', ',', $daftarPekerjaan->hasItemPengawas[$key]->pivot->qty) }}
                                                                    </td>
                                                                    @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                        <td>Rp.
                                                                            {{ format_uang($daftarPekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                                                        </td>
                                                                    @endif

                                                                    @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                                        <td>
                                                                            Rp.
                                                                            {{ format_uang($daftarPekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $daftarPekerjaan->hasItemPengawas[$key]->pivot->keterangan }}
                                                                        </td>
                                                                        <td>
                                                                            Rp.
                                                                            {{ format_uang($daftarPekerjaan->hasItemPengawas[$key]->pivot->qty * $daftarPekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                                                        </td>
                                                                    @else
                                                                        <td>
                                                                            {{ $daftarPekerjaan->hasItemPengawas[$key]->pivot->keterangan }}
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            @endif
                                                        </tr>
                                                        @if ($pekerjaanUtama->status === 'koreksi pengawas')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>
                                                                @if ($asmenpengawas === true)
                                                                    <td><input type="text"
                                                                            name="qty_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                            id="qty_pengawas_{{ $pekerjaan->pivot->item_id }}"
                                                                            placeholder="Koreksi Pengawas"
                                                                            class="form-control numberOnly"
                                                                            @if (isset($daftarPekerjaan->hasItemPengawas[$key])) value="{{ $daftarPekerjaan->hasItemPengawas[$key]->pivot->qty }}"
                                                                            @else
                                                                            value="{{ $pekerjaan->pivot->qty }}" @endif>
                                                                    </td>
                                                                    <td><input type="text"
                                                                            name="keterangan_pengawas[{{ $pekerjaan->pivot->item_id }}]"
                                                                            id="keterangan_pengawas_{{ $pekerjaan->pivot->item_id }}"
                                                                            placeholder="Keterangan Pengawas"
                                                                            class="form-control">
                                                                    </td>
                                                                @else
                                                                    <td>

                                                                    </td>
                                                                    <td>

                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endif
                                                        @if ($pekerjaanUtama->status === 'dikoreksi')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>

                                                                @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                    <td>
                                                                        {{ str_replace('.', ',', $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty) }}
                                                                    </td>
                                                                    @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                        <td>Rp.
                                                                            {{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty * $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                                                        </td>
                                                                    @endif
                                                                    <td>
                                                                        {{ $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->keterangan }}
                                                                    </td>
                                                                    @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                                        <td>Rp.
                                                                            {{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                                                        </td>
                                                                    @endif
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Perencanaan</td>
                                                                @if ($perencaan == true)
                                                                    <td></td>
                                                                    <td>
                                                                        <div class="input-group mb-2 mr-sm-2">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">Rp.</div>
                                                                            </div>
                                                                            <input type="text" class="form-control"
                                                                                id="harga_perencanaan_pekerjaan{{ $pekerjaan->pivot->item_id }}{{ $key }}"
                                                                                name="harga_perencanaan_pekerjaan[{{ $pekerjaan->pivot->item_id }}]"
                                                                                value="{{ format_uang($pekerjaan->pivot->harga) }}"
                                                                                placeholder="Koreksi Perencanaan">
                                                                        </div>
                                                                        @push('script')
                                                                            <script>
                                                                                $("#harga_perencanaan_pekerjaan{{ $pekerjaan->pivot->item_id }}{{ $key }}").on("input", function() {

                                                                                    let val = formatRupiahTanpaRp(this.value, '')
                                                                                    $("#harga_perencanaan_pekerjaan{{ $pekerjaan->pivot->item_id }}{{ $key }}").val(val)
                                                                                });
                                                                            </script>
                                                                        @endpush
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            id="keterangan_perencanaan_pekerjaan{{ $pekerjaan->pivot->item_id }}{{ $key }}"
                                                                            name="keterangan_perencanaan_pekerjaan[{{ $pekerjaan->pivot->item_id }}]"
                                                                            placeholder="Keterangan Perencanaan">
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                    <td></td>
                                                                @endif
                                                            </tr>
                                                        @elseif ($pekerjaanUtama->status === 'selesai koreksi')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>

                                                                @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                    <td>
                                                                        {{ str_replace('.', ',', $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty) }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->keterangan }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty * $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Perencanaan</td>
                                                                @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                    <td>{{ str_replace('.', ',', $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty) }}
                                                                    </td>
                                                                @endif
                                                                @if (isset($daftarPekerjaan->hasItemPerencanaan[$key]))
                                                                    <td>
                                                                        Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemPerencanaan[$key]->pivot->harga) }}
                                                                    </td>
                                                                    <td>{{ $daftarPekerjaan->hasItemPerencanaan[$key]->pivot->keterangan }}
                                                                    </td>
                                                                    @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                        <td>
                                                                            Rp.{{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty * $daftarPekerjaan->hasItemPerencanaan[$key]->pivot->harga) }}
                                                                        </td>
                                                                    @endif
                                                                @endif

                                                            </tr>
                                                        @elseif ($pekerjaanUtama->status === 'diadjust')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>

                                                                @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                    <td>
                                                                        {{ str_replace('.', ',', $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty) }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->keterangan }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty * $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td rowspan="2">Perencanaan
                                                                </td>
                                                                <td></td>
                                                                @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                    <td>{{ str_replace('.', ',', $daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty) }}
                                                                    </td>
                                                                @endif
                                                                @if (isset($daftarPekerjaan->hasItemPerencanaan[$key]))
                                                                    <td>
                                                                        Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemPerencanaan[$key]->pivot->harga) }}
                                                                    </td>
                                                                    <td>{{ $daftarPekerjaan->hasItemPerencanaan[$key]->pivot->keterangan }}
                                                                    </td>
                                                                    @if (isset($daftarPekerjaan->hasItemAsmenPengawas[$key]))
                                                                        <td>
                                                                            Rp.{{ format_uang($daftarPekerjaan->hasItemAsmenPengawas[$key]->pivot->qty * $daftarPekerjaan->hasItemPerencanaan[$key]->pivot->harga) }}
                                                                        </td>
                                                                    @endif
                                                                @endif

                                                            </tr>
                                                            <tr>

                                                                @if (isset($daftarPekerjaan->hasItemPerencanaanAdujst[$key]))
                                                                    <td> {{ str_replace('.', ',', $daftarPekerjaan->hasItemPerencanaanAdujst[$key]->pivot->qty) }}
                                                                    </td>
                                                                    <td>
                                                                        Rp.
                                                                        {{ format_uang($daftarPekerjaan->hasItemPerencanaanAdujst[$key]->pivot->harga) }}
                                                                    </td>
                                                                    <td>{{ $daftarPekerjaan->hasItemPerencanaanAdujst[$key]->pivot->keterangan }}
                                                                    </td>
                                                                    <td>
                                                                        Rp.{{ format_uang($daftarPekerjaan->hasItemPerencanaanAdujst[$key]->pivot->qty * $daftarPekerjaan->hasItemPerencanaanAdujst[$key]->pivot->harga) }}
                                                                    </td>
                                                                @endif

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
                                                @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                    @if (isset($daftarPekerjaan->hasItem))
                                                        <tr>
                                                            <th colspan="7" class="text-right">Total
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

                                        <hr>
                                        {{-- Galian --}}
                                        @if ($pengawas === true && $pekerjaanUtama->status === 'approve')
                                            <div class="col-12">
                                                @if ($rekanan_id == null)
                                                    @if ($tombolEdit === 'bisa')
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h6>Pilih Galian</h6>

                                                                <div class="form-group">
                                                                    <select class="form-control select2" id="cmbGalian">
                                                                        <option selected="selected" value="">Pilih
                                                                            galian
                                                                        </option>
                                                                        @foreach ($listPekerjaanGalian as $i => $gal)
                                                                            <option value="{{ $gal->id }}">
                                                                                {{ $gal->nama }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Panjang</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="panjang_galian"
                                                                            id="panjang_galian" placeholder="panjang"
                                                                            class="form-control numberOnly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Lebar</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="lebar_galian"
                                                                            id="lebar_galian" placeholder="lebar"
                                                                            class="form-control numberOnly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Dalam</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="dalam_galian"
                                                                            id="dalam_galian" placeholder="Dalam"
                                                                            class="form-control numberOnly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Keterangan</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="input_keterangan_galian"
                                                                            id="input_keterangan_galian"
                                                                            placeholder="Keterangan galian"
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
                                                                                <input type="radio" class="harga_galian"
                                                                                    name="harga_galian" value="siang"
                                                                                    checked="checked">
                                                                                <i class="helper"></i>Siang
                                                                            </label>
                                                                        </div>
                                                                        <div class="radio radiofill radio-inline">
                                                                            <label>
                                                                                <input type="radio" class="harga_galian"
                                                                                    name="harga_galian" value="malam">
                                                                                <i class="helper"></i>Malam
                                                                            </label>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="">
                                                                    <button type="button" id="btn_galian"
                                                                        class="btn btn-primary">Tambah Galian</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                        @if ($asmenpengawas === true && $pekerjaanUtama->status === 'koreksi pengawas')
                                            <div class="col-12">
                                                @if ($rekanan_id == null)
                                                    @if ($tombolEdit === 'bisa')
                                                        <div class="row">
                                                            <div class="col-2">
                                                                <h6>Pilih Galian</h6>

                                                                <div class="form-group">
                                                                    <select class="form-control select2" id="cmbGalian">
                                                                        <option selected="selected" value="">Pilih
                                                                            galian
                                                                        </option>
                                                                        @foreach ($listPekerjaanGalian as $i => $gal)
                                                                            <option value="{{ $gal->id }}">
                                                                                {{ $gal->nama }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Panjang</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="panjang_galian"
                                                                            id="panjang_galian" placeholder="panjang"
                                                                            class="form-control numberOnly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Lebar</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="lebar_galian"
                                                                            id="lebar_galian" placeholder="lebar"
                                                                            class="form-control numberOnly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Dalam</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="dalam_galian"
                                                                            id="dalam_galian" placeholder="Dalam"
                                                                            class="form-control numberOnly">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <h6>Keterangan</h6>
                                                                <div class="form-group">
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <input type="text" name="input_keterangan_galian"
                                                                            id="input_keterangan_galian"
                                                                            placeholder="Keterangan galian"
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
                                                                                <input type="radio" class="harga_galian"
                                                                                    name="harga_galian" value="siang"
                                                                                    checked="checked">
                                                                                <i class="helper"></i>Siang
                                                                            </label>
                                                                        </div>
                                                                        <div class="radio radiofill radio-inline">
                                                                            <label>
                                                                                <input type="radio" class="harga_galian"
                                                                                    name="harga_galian" value="malam">
                                                                                <i class="helper"></i>Malam
                                                                            </label>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                            <div class="col">
                                                                <div class="">
                                                                    <button type="button" id="btn_galian"
                                                                        class="btn btn-primary">Tambah
                                                                        Galian</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                        <table class="table table-bordered table-responsive" width="100%" id="tableGalian">
                                            <thead>
                                                <tr>
                                                    <th width="5">#</th>
                                                    <th width="500">Galian</th>
                                                    <th width="100">User</th>
                                                    <th width="100">Panjang</th>
                                                    <th width="100">Lebar</th>
                                                    <th width="100">Dalam</th>
                                                    <th width="100">Volume</th>
                                                    @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                        <th width="200">Harga Satuan</th>
                                                    @endif
                                                    <th width="200">Keterangan</th>
                                                    @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                        <th width="150">Total</th>
                                                    @endif
                                                    @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                        <th width="200">Harga Satuan</th>
                                                        <th width="250">Total</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (isset($daftarGalian))
                                                    @forelse ($daftarGalian as $inv => $galian)
                                                        @php
                                                            $sum = 0;
                                                        @endphp
                                                        <tr id="listGalian_{{ $galian->item_id }}"
                                                            class="list_table_galian">
                                                            <td class="text-center nomor_galian"
                                                                data-index="{{ $inv + 1 }}"
                                                                @if ($pekerjaanUtama->status === 'koreksi pengawas') rowspan="3"
                                                                @elseif($pekerjaanUtama->status === 'dikoreksi' || $pekerjaanUtama->status == 'selesai koreksi') rowspan="4"
                                                                @elseif ($pekerjaanUtama->status === 'diadjust') rowspan="5"
                                                                @else rowspan="2" @endif>
                                                                {{ $inv + 1 }}
                                                            </td>
                                                            <td
                                                                @if ($pekerjaanUtama->status === 'koreksi pengawas') rowspan="3"
                                                                @elseif($pekerjaanUtama->status === 'dikoreksi' || $pekerjaanUtama->status == 'selesai koreksi') rowspan="4"
                                                                @elseif ($pekerjaanUtama->status === 'diadjust') rowspan="5"
                                                                @else rowspan="2" @endif>
                                                                {{ $galian->pekerjaan }}
                                                            </td>
                                                            <td>Rekanan</td>
                                                            <td>{{ str_replace('.', ',', $galian->panjang) }}</td>
                                                            <td>{{ str_replace('.', ',', $galian->lebar) }}</td>
                                                            <td>{{ str_replace('.', ',', $galian->dalam) }}<< /td>
                                                            <td>
                                                                {{ round($galian->volume_rekanan, 3) }}

                                                                m<sup>2
                                                            </td>
                                                            @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                <td>Rp. {{ format_uang($galian->harga_satuan) }}</td>
                                                            @endif
                                                            <td>{{ $galian->keterangan }}</td>
                                                            @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                <td>Rp.
                                                                    {{ format_uang($galian->volume_rekanan * $galian->harga_satuan) }}
                                                                </td>
                                                            @endif
                                                            @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                                <td>Rp. {{ format_uang($galian->harga_satuan) }}</td>
                                                                <td>Rp.
                                                                    {{ format_uang($galian->volume_rekanan * $galian->harga_satuan) }}
                                                                </td>
                                                            @endif

                                                        </tr>
                                                        <tr>
                                                            <td>Pengawas</td>
                                                            @if ($pengawas === true && $pekerjaanUtama->status === 'approve')
                                                                <td><input type="text"
                                                                        name="panjang_pengawas[{{ $galian->item_id }}]"
                                                                        id="panjang_pengawas[{{ $galian->item_id }}]"
                                                                        placeholder="Panjang Pengawas"
                                                                        value="{{ $galian->panjang }}"
                                                                        class="form-control numberOnly">
                                                                </td>
                                                                <td><input type="text"
                                                                        name="lebar_pengawas[{{ $galian->item_id }}]"
                                                                        id="lebar_pengawas[{{ $galian->item_id }}]"
                                                                        placeholder="Lebar Pengawas"
                                                                        value="{{ $galian->lebar }}"
                                                                        class="form-control numberOnly">
                                                                </td>
                                                                <td><input type="text"
                                                                        name="dalam_pengawas[{{ $galian->item_id }}]"
                                                                        id="dalam_pengawas[{{ $galian->item_id }}]"
                                                                        placeholder="Dalam Pengawas"
                                                                        value="{{ $galian->dalam }}"
                                                                        class="form-control numberOnly">
                                                                </td>
                                                                <td>
                                                                    <span id="volume_galian_{{ $galian->item_id }}">

                                                                    </span>
                                                                </td>
                                                                <td><input type="text"
                                                                        name="keterangan_pengawas_galian[{{ $galian->item_id }}]"
                                                                        id="keterangan_pengawas_galian_{{ $galian->item_id }}"
                                                                        placeholder="Keterangan Galian Pengawas"
                                                                        class="form-control">
                                                                </td>
                                                            @else
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_pengawas_panjang) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_pengawas_lebar) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_pengawas_dalam) }}
                                                                </td>
                                                                <td>
                                                                    {{ round($galian->volume, 3) }}
                                                                    m<sup>2
                                                                </td>
                                                                @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                    <td>Rp.
                                                                        {{ format_uang($galian->galian_pengawas_harga_satuan) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $galian->galian_pengawas_keterangan }}
                                                                    </td>


                                                                    <td>Rp.
                                                                        {{ format_uang($galian->galian_pengawas_total) }}
                                                                    </td>
                                                                @endif
                                                                @if ($perencaan == false && $pekerjaanUtama->status === 'dikoreksi')
                                                                    <td>
                                                                        {{ $galian->galian_pengawas_keterangan }}
                                                                    </td>
                                                                @endif
                                                                @if ($perencaan == false && $pekerjaanUtama->status === 'koreksi pengawas')
                                                                    <td>
                                                                        {{ $galian->galian_pengawas_keterangan }}
                                                                    </td>
                                                                @endif
                                                                @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                                    <td>
                                                                        {{ $galian->galian_pengawas_keterangan }}
                                                                    </td>
                                                                    <td>
                                                                        Rp.
                                                                        {{ format_uang($galian->galian_pengawas_harga_satuan) }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($galian->galian_pengawas_total) }}
                                                                    </td>
                                                                @endif
                                                            @endif
                                                        </tr>
                                                        @if ($pekerjaanUtama->status === 'koreksi pengawas')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>
                                                                @if ($asmenpengawas === true)
                                                                    <td><input type="text"
                                                                            name="panjang_pengawas[{{ $galian->item_id }}]"
                                                                            id="panjang_pengawas[{{ $galian->item_id }}]"
                                                                            placeholder="Panjang Pengawas"
                                                                            value="{{ $galian->galian_pengawas_panjang }}"
                                                                            class="form-control numberOnly">
                                                                    </td>
                                                                    <td><input type="text"
                                                                            name="lebar_pengawas[{{ $galian->item_id }}]"
                                                                            id="lebar_pengawas[{{ $galian->item_id }}]"
                                                                            placeholder="Lebar Pengawas"
                                                                            value="{{ $galian->galian_pengawas_lebar }}"
                                                                            class="form-control numberOnly">
                                                                    </td>
                                                                    <td><input type="text"
                                                                            name="dalam_pengawas[{{ $galian->item_id }}]"
                                                                            id="dalam_pengawas[{{ $galian->item_id }}]"
                                                                            placeholder="Dalam Pengawas"
                                                                            value="{{ $galian->galian_pengawas_dalam }}"
                                                                            class="form-control numberOnly">
                                                                    </td>
                                                                    <td>
                                                                        <span id="volume_galian_{{ $galian->item_id }}">

                                                                        </span>
                                                                    </td>
                                                                    <td><input type="text"
                                                                            name="keterangan_pengawas_galian[{{ $galian->item_id }}]"
                                                                            id="keterangan_pengawas_galian_{{ $galian->item_id }}"
                                                                            placeholder="Keterangan Galian Pengawas"
                                                                            class="form-control">
                                                                    </td>
                                                                @else
                                                                    <td>

                                                                    </td>
                                                                    <td>

                                                                    </td>
                                                                    <td>
                                                                    </td>
                                                                    <td>

                                                                    </td>
                                                                @endif
                                                            </tr>
                                                        @endif
                                                        @if ($pekerjaanUtama->status === 'dikoreksi')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_panjang) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_lebar) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_dalam) }}
                                                                </td>
                                                                <td>
                                                                    {{ round($galian->volume_asmen, 3) }}
                                                                    m<sup>2
                                                                </td>
                                                                @if ($perencaan == true && $pekerjaanUtama->status === 'dikoreksi')
                                                                    <td>Rp.
                                                                        {{ format_uang($galian->galian_asmen_pengawas_harga_satuan) }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $galian->galian_asmen_pengawas_keterangan }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($galian->galian_asmen_pengawas_total) }}
                                                                    </td>
                                                                @endif
                                                                @if ($perencaan == false && $pekerjaanUtama->status === 'dikoreksi')
                                                                    <td>
                                                                        {{ $galian->galian_asmen_pengawas_keterangan }}
                                                                    </td>
                                                                @endif
                                                                @if ($perencaan == false && $pekerjaanUtama->status === 'koreksi pengawas')
                                                                    <td>
                                                                        {{ $galian->galian_asmen_pengawas_keterangan }}
                                                                    </td>
                                                                @endif
                                                                @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                                    <td>
                                                                        {{ $galian->galian_asmen_pengawas_keterangan }}
                                                                    </td>
                                                                    <td>
                                                                        Rp.
                                                                        {{ format_uang($galian->galian_asmen_pengawas_harga_satuan) }}
                                                                    </td>
                                                                    <td>Rp.
                                                                        {{ format_uang($galian->galian_asmen_pengawas_total) }}
                                                                    </td>
                                                                @endif
                                                            </tr>
                                                            <tr>
                                                                <td>Perencanaan</td>
                                                                @if ($perencaan == true)
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>
                                                                        <div class="input-group mb-2 mr-sm-2">
                                                                            <div class="input-group-prepend">
                                                                                <div class="input-group-text">Rp.</div>
                                                                            </div>
                                                                            <input type="text" class="form-control"
                                                                                id="harga_galian{{ $galian->item_id }}{{ $inv }}"
                                                                                name="harga_galian[{{ $galian->id }}]"
                                                                                value="{{ format_uang($galian->galian_asmen_pengawas_harga_satuan) }}"
                                                                                placeholder="Harga Perencanaan">
                                                                        </div>
                                                                        @push('script')
                                                                            <script>
                                                                                $("#harga_galian{{ $galian->item_id }}{{ $inv }}").on("input", function() {

                                                                                    let val = formatRupiahTanpaRp(this.value, '')
                                                                                    $("#harga_galian{{ $galian->item_id }}{{ $inv }}").val(val)
                                                                                });
                                                                            </script>
                                                                        @endpush
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" class="form-control"
                                                                            id="keterangan_perencanaa_galian{{ $galian->id }}{{ $inv }}"
                                                                            name="keterangan_perencanaa_galian[{{ $galian->id }}]"
                                                                            value=""
                                                                            placeholder="Keterangan Perencanaan">
                                                                    </td>
                                                                @else
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                @endif
                                                            </tr>
                                                        @elseif ($pekerjaanUtama->status === 'selesai koreksi')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_panjang) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_lebar) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_dalam) }}
                                                                </td>
                                                                <td>
                                                                    {{ round($galian->volume_asmen, 3) }}
                                                                    m<sup>2
                                                                </td>
                                                                <td>
                                                                    {{ $galian->galian_asmen_pengawas_keterangan }}
                                                                </td>
                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->galian_asmen_pengawas_harga_satuan) }}
                                                                </td>
                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->galian_asmen_pengawas_total) }}
                                                                </td>


                                                            </tr>
                                                            <tr>
                                                                <td>Perencanaan</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td> </td>
                                                                <td> {{ $galian->galian_perencanaan_keterangan }}</td>
                                                                <td>Rp. {{ $galian->galian_perencanaan_harga_satuan }}</td>
                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->total) }}
                                                                </td>

                                                            </tr>
                                                        @elseif ($pekerjaanUtama->status === 'diadjust')
                                                            <tr>
                                                                <td>Asisten Manajer Pengawas</td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_panjang) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_lebar) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_dalam) }}
                                                                </td>
                                                                <td>
                                                                    {{ round($galian->volume_asmen, 3) }}
                                                                    m<sup>2
                                                                </td>
                                                                <td>
                                                                    {{ $galian->galian_asmen_pengawas_keterangan }}
                                                                </td>
                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->galian_asmen_pengawas_harga_satuan) }}
                                                                </td>

                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->galian_asmen_pengawas_total) }}
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td rowspan="2">Perencanaan</td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_panjang) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_lebar) }}
                                                                </td>
                                                                <td>
                                                                    {{ str_replace('.', ',', $galian->galian_asmen_pengawas_dalam) }}
                                                                </td>
                                                                <td>
                                                                    {{ round($galian->volume_asmen, 3) }}
                                                                    m<sup>2
                                                                </td>
                                                                <td> {{ $galian->galian_perencanaan_keterangan }}</td>
                                                                <td>
                                                                    Rp. {{ $galian->galian_perencanaan_harga_satuan }}
                                                                </td>
                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->total) }}
                                                                </td>

                                                            </tr>
                                                            <tr>
                                                                <td>{{ $galian->galian_perencanaan_adjust_panjang }}</td>
                                                                <td>{{ $galian->galian_perencanaan_adjust_lebar }}</td>
                                                                <td>{{ $galian->galian_perencanaan_adjust_dalam }}</td>
                                                                <td>{{ round($galian->volume_adjust, 3) }} m<sup>2</td>
                                                                <td> {{ $galian->galian_perencanaan_adjust_keterangan }}
                                                                </td>
                                                                <td>Rp.
                                                                    {{ format_uang($galian->galian_perencanaan_adjust_harga_satuan) }}
                                                                </td>
                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($galian->total) }}
                                                                </td>

                                                            </tr>
                                                        @endif

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
                                                    @if ($pekerjaanUtama->status === 'selesai koreksi' || $pekerjaanUtama->status === 'diadjust')
                                                        <tr>
                                                            <th colspan="9" class="text-right"> Total
                                                            </th>
                                                            <th>Rp.
                                                                {{ format_uang($daftarGalian->sum('total')) }}
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="9" class="text-right">
                                                                Grand Total
                                                            </th>
                                                            <th>Rp.
                                                                {{ format_uang($pekerjaanUtama->total_pekerjaan) }}
                                                            </th>
                                                        </tr>
                                                    @endif
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
                                                            @if (auth()->user()->hasRole('asisten-manajer-distribusi') ||
                                                                auth()->user()->hasRole('asisten-manajer-pengendalian-kehilangan-air'))
                                                                <button type="button" id="simpan_koreksi"
                                                                    class="btn btn-primary">Setujui Pekerjaan</button>
                                                            @else
                                                                <button type="button" id="simpan_koreksi"
                                                                    class="btn btn-primary">Simpan Koreksi
                                                                    Pekerjaan</button>
                                                            @endif
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
                                        <label for="nps" class=" form-control-label">NPS</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->nps }}</strong>
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
        $('#cmbRekanan').select2({
            placeholder: '--- Pilih Pekerjaan ---',
            width: '100%'
        });
    </script>
    @if ($aduan->status != 'draft')
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

            function toastError(text) {
                $.toast({
                    heading: 'Error',
                    text: text,
                    showHideTransition: 'slide',
                    icon: 'error',
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



                function elementPekerjaan(id, nama, slug, jenis, nomor, modul, jumlah, jenis_harga, keterangan = null,
                    karyawan = 'Pengawas') {
                    let modulLowcasse = capitalizeFirstLetter(modul);
                    let pekerjaanUtama = $('#idPekerjaan').val();

                    let elementTotal = `<tr id="list${modulLowcasse}_${id}" class="list_table_${modul}">
                    <td class="text-center nomor_${modul}" data-index="${nomor}">${nomor}
                    </td>
                    <td>${nama}</td>
                    <td>${jenis}</td>
                    <td>${karyawan}</td>
                    <td>
                        <input type="text" name="qty_pengawas[${id}]"
                        id="qty_pengawas_${id}" value="${jumlah}"
                        placeholder="Koreksi Pengawas" class="form-control numberOnly">
                        <input type="hidden" name="jenis_harga_galian[${id}]" value="${jenis_harga}" />
                    </td>
                    <td>
                        <input type="text" name="keterangan_pengawas[${id}]"
                        id="keterangan_pengawas_${id}" value="${keterangan}"
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
                    let jenis_harga = $("input[name='harga_" + modul + "']:checked").val();

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
                                url: "{{ route('item.detail') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    item
                                },
                                success: function(data) {
                                    const {
                                        id,
                                        nama,
                                        slug,
                                        karyawan,
                                        jenis
                                    } = data.data;
                                    let content = elementPekerjaan(
                                        id, nama, slug, jenis, nomor, modul, jumlah, jenis_harga,
                                        keterangan, karyawan
                                    );
                                    $('.' + modul + 'TidakAda').remove();
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
                                    console.log(data);
                                }
                            })).then(function(data, textStatus, jqXHR) {
                                // totalHarga(modul);


                            });

                        } else {
                            toastError('Data pekerjaan sudah ada');
                            $('#cmb' + modulLowcasse).parent().addClass('is-invalid')
                        }

                    } else {
                        Swal.fire({
                            title: 'Oops...',
                            text: "Isi data dengan lengkap",
                            footer: '<a href="">terdapat data yang kosong</a>'
                        })
                    }

                }

                // star galian
                $('#cmbGalian').select2({
                    placeholder: '--- Pilih Galian ---',
                    width: '100%'
                });

                $("#cmbGalian").on("change", function(e) {
                    $('#cmbGalian').parent().removeClass('is-invalid')
                });

                $('#panjang_galian').keypress(function(event) {
                    $(this).removeClass("is-invalid");
                });
                $('#lebar_galian').keypress(function(event) {
                    $(this).removeClass("is-invalid");
                });
                $('#dalam_galian').keypress(function(event) {
                    $(this).removeClass("is-invalid");
                })

                function elementPekerjaanGalian(id, nama, nomor, modul, panjang, lebar, dalam, jenis_harga, keterangan =
                    null, karyawan) {
                    let modulLowcasse = capitalizeFirstLetter(modul);
                    let pekerjaanUtama = $('#idPekerjaan').val();

                    let elementTotalGalian = `<tr id="list${modulLowcasse}_${id}" class="list_table_${modul}">
                    <td class="text-center nomor_${modul}" data-index="${nomor}">${nomor}
                    </td>
                    <td>${nama}</td>
                    <td>${karyawan}</td>
                    <td>
                        <input type="text" name="panjang_pengawas[${id}]"
                        id="panjang_pengawas_${id}" value="${panjang}"
                        placeholder="Panjang" class="form-control numberOnly">
                    </td>
                    <td>
                        <input type="text" name="lebar_pengawas[${id}]"
                        id="lebar_pengawas_${id}" value="${lebar}"
                        placeholder="Lebar" class="form-control numberOnly">
                    </td>
                    <td>
                        <input type="text" name="dalam_pengawas[${id}]"
                        id="dalam_pengawas_${id}" value="${dalam}"
                        placeholder="Koreksi Pengawas" class="form-control numberOnly">
                    </td>
                    <td>
                    </td>
                    <td>
                        <input type="text" name="keterangan_pengawas_galian[${id}]"
                        id="keterangan_pengawas_galian_${id}" value="${keterangan}"
                        placeholder="Keterangan Pengawas" class="form-control ">
                        <input type="hidden" name="jenis_harga[${id}]" value="${jenis_harga}" />
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

                    return elementTotalGalian;

                }

                $('#btn_galian').on("click", function(e) {

                    let modul = 'galian';
                    let item = $('#cmbGalian').val();
                    let panjang = $('#panjang_galian').val();
                    let lebar = $('#lebar_galian').val();
                    let dalam = $('#dalam_galian').val();
                    let keterangan = $('#input_keterangan_galian').val();
                    let jenis_harga = $("input[name='harga_galian']:checked").val();
                    let modulLowcasse = capitalizeFirstLetter(modul);

                    if (item === "") {
                        $('#cmbGalian').parent().addClass('is-invalid')
                    }
                    if (panjang === "") {
                        $('#panjang_galian').addClass("is-invalid");
                    }
                    if (lebar === "") {
                        $('#lebar_galian').addClass("is-invalid");
                    }
                    if (dalam === "") {
                        $('#dalam_galian').addClass("is-invalid");
                    }

                    if (item !== "" && panjang !== "" && dalam !== "" && lebar !== "") {
                        let tableCount = $('.nomor_' + modul).length;

                        let nomor = tableCount + 1;
                        let lengthPekerjaan = $('#list' + modulLowcasse + '_' + item).length;

                        if (lengthPekerjaan === 0) {
                            $.when($.ajax({
                                type: 'get',
                                url: "{{ route('item.detail') }}",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    item
                                },
                                success: function(data) {
                                    const {
                                        id,
                                        nama,
                                        slug,
                                        jenis,
                                        karyawan
                                    } = data.data;
                                    let content = elementPekerjaanGalian(
                                        id, nama,
                                        nomor,
                                        modul,
                                        panjang,
                                        lebar,
                                        dalam,
                                        jenis_harga,
                                        keterangan,
                                        karyawan
                                    );

                                    $('.' + modul + 'TidakAda').remove();
                                    $('#table' + modulLowcasse).append(content);
                                    $('#cmb' + modulLowcasse).val(null).trigger('change');
                                    $('#panjang_' + modul).val('');
                                    $('#lebar_' + modul).val('');
                                    $('#dalam_' + modul).val('');
                                    $('#input_keterangan_' + modul).val('');
                                    toast('success menambah ' + modulLowcasse);

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

                        } else {
                            toastError('Data galian sudah ada');
                            $('#cmbGalian').parent().addClass('is-invalid')
                        }
                    } else {
                        Swal.fire({
                            title: 'Oops...',
                            text: "Isi data dengan lengkap",
                            footer: '<a href="">terdapat data yang kosong</a>'
                        })
                    }

                });

            });
        </script>
    @endif
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
