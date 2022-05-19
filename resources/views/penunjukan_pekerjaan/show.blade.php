@extends('template.app')
@section('title', 'Detail Aduan ' . $aduan->no_aduan)

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
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="no_ticket" class=" form-control-label">No SPK : </label>
                                        </div>
                                        <div>
                                            <strong>{{ $aduan->no_spk }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <div>
                                            <label for="no_ticket" class=" form-control-label">Nama Rekanan :</label>
                                        </div>
                                        <div>
                                            <strong>{{ $aduan->rekanan }}</strong>
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
                                            <strong>{{ $aduan->keterangan_barang }}</strong>
                                        </div>
                                        <input type="hidden" name="id_pekerjaan" id="idPekerjaan"
                                            value="{{ $pekerjaanUtama->id }}">
                                    </div>
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="card-header">
                            <div class="card-title">Pilih Rekanan</div>
                        </div>
                        <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <input type="hidden" name="slug" value="{{ $aduan->slug }}">
                                            <div>
                                                <select name="rekanan_id" class="selected2 form-control" id="rekanan"
                                                    required>
                                                    <option value="">--Pilih Rekanan--</option>
                                                    @foreach ($rekanan as $item)
                                                        <option value="{{ $item->id }}"
                                                            {{ old('rekanan_id') == $item->id ? 'selected' : '' }}>
                                                            {{ $item->nama }}
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
                    @endif
                </div>
            </div>
        </div>
        @if ($aduan->status != 'draft')
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">Daftar Pekerjaan</div>

                        </div>
                        <div class="card-body">
                            <form action="" id="formPekerjaan">

                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="sub-title">Pilih Pekerjaan</h5>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbPekerjaan">
                                                <option selected="selected" value="">Pilih Pekerjaan
                                                </option>
                                                @foreach ($listPekerjaan as $i => $pekerjaan)
                                                    <option value="{{ $pekerjaan->id }}"
                                                        id="Pekerjaan_{{ $pekerjaan->id }}">
                                                        {{ $pekerjaan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Jumlah</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="jumlah_pekerjaan" id="jumlah_pekerjaan"
                                                    placeholder="jumlah" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">keterangan</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="input_keterangan_pekerjaan"
                                                    id="input_keterangan_pekerjaan" placeholder="Keterangan Pekerjaan"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Pilih Harga</h5>
                                        <div class="form-radio">
                                            <form>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_pekerjaan" name="harga_pekerjaan"
                                                            value="siang" checked="checked">
                                                        <i class="helper"></i>Harga Siang
                                                    </label>
                                                </div>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_pekerjaan" name="harga_pekerjaan"
                                                            value="malam">
                                                        <i class="helper"></i>Harga Malam
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="">
                                            <button type="submit" id="btn_pekerjaan" class="btn btn-primary">Update
                                                Pekerjaan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered " width="100%" id="tablePekerjaan">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="250">Pekerjaan</th>
                                        <th width="10">Jumlah</th>
                                        <th width="200">Total Harga</th>
                                        <th width="150">Keterangan</th>
                                        <th width="5%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarPekerjaan->hasItem))
                                        @forelse ($daftarPekerjaan->hasItem as $key => $pekerjaan)
                                            <tr id="listPekerjaan_{{ $pekerjaan->id }}" class="list_table_pekerjaan">
                                                <td class="text-center nomor_pekerjaan" data-index="{{ $key + 1 }}">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $pekerjaan->nama }}
                                                </td>
                                                <td>
                                                    <span
                                                        id="jumlah_pekerjaan_tampil_{{ $pekerjaan->id }}">{{ $pekerjaan->pivot->qty }}
                                                    </span>

                                                    <input type="hidden" id="jumlah_pekerjaan_value_{{ $pekerjaan->id }}"
                                                        name="jumlah_pekerjaan" value="{{ $pekerjaan->pivot->qty }}">
                                                </td>
                                                <td id="total_pekerjaan_{{ $pekerjaan->id }}">Rp.
                                                    {{ format_uang($pekerjaan->pivot->total) }}</td>
                                                <td>
                                                    <span id="keterangan_pekerjaan_{{ $pekerjaan->id }}">
                                                        {{ $pekerjaan->pivot->keterangan }}</span>

                                                    <input type="hidden"
                                                        id="keterangan_pekerjaan_value_{{ $pekerjaan->id }}"
                                                        name="keterangan_pekerjaan"
                                                        value="{{ $pekerjaan->pivot->keterangan }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning text-light btn-edit"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="pekerjaan" data-item="{{ $pekerjaan->id }}">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                        Ubah
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-xs text-center btn-hapus"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="pekerjaan" data-item="{{ $pekerjaan->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
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
                                    @if (isset($daftarPekerjaan->hasItem))
                                        <tr>
                                            <th colspan="3" class="text-right">Grand Total
                                            </th>
                                            <th>Rp. {{ format_uang($daftarPekerjaan->hasItem->sum('pivot.total')) }}

                                        </tr>
                                    @endif
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">Daftar Galian</div>

                        </div>
                        <div class="card-body">
                            <form action="" id="formGalian">
                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="sub-title">Pilih Galian</h5>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbGalian">
                                                <option selected="selected" value="">Pilih Galian
                                                </option>
                                                @foreach ($listGalian as $i => $galian)
                                                    <option value="{{ $galian->id }}" id="galian_{{ $galian->id }}">
                                                        {{ $galian->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Panjang</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="panjang_galian" id="panjang_galian"
                                                    placeholder="Panjang" class="form-control">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">M</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Lebar</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="lebar_galian" id="lebar_galian" placeholder="Lebar"
                                                    class="form-control">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">M</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Dalam</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="dalam_galian" id="dalam_galian" placeholder="Dalam"
                                                    class="form-control">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">M</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Keterangan</h5>
                                        <div class="form-group">
                                            <textarea name="keterangan_galian" class="form-control" id="keterangan_galian" cols="30"></textarea>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Pilih Harga</h5>
                                        <div class="form-radio">
                                            <form>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_galian" name="harga_galian"
                                                            value="siang" checked="checked">
                                                        <i class="helper"></i>Harga Siang
                                                    </label>
                                                </div>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_galian" name="harga_galian"
                                                            value="malam">
                                                        <i class="helper"></i>Harga Malam
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="">
                                            <button type="submit" id="btn_galian" class="btn btn-primary">Update
                                                Galian</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered " width="100%" id="tableGalian">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="250">Galian</th>
                                        <th width="10">Panjang</th>
                                        <th width="10">Lebar</th>
                                        <th width="100">Dalam</th>
                                        <th width="200">Total Harga</th>
                                        <th width="150">Keterangan</th>
                                        <th width="5%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarGalian))

                                        @forelse ($daftarGalian as $key => $galian)
                                            <tr id="listgalian_{{ $galian->item_id }}" class="list_table_galian">
                                                <td class="text-center nomor_galian" data-index="{{ $key + 1 }}">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $galian->pekerjaan }}

                                                </td>
                                                <td>
                                                    <span
                                                        id="panjang_galian_{{ $galian->item_id }}">{{ $galian->panjang }}
                                                        M</span>

                                                    <input type="hidden" id="panjang_value_{{ $galian->item_id }}"
                                                        name="panjang" value="{{ $galian->panjang }}">
                                                </td>
                                                <td>
                                                    <span
                                                        id="lebar_galian_{{ $galian->item_id }}">{{ $galian->lebar }}
                                                        M</span>
                                                    <input type="hidden" id="lebar_value_{{ $galian->item_id }}"
                                                        name="lebar" value="{{ $galian->lebar }}">
                                                </td>
                                                <td>
                                                    <span id="dalam_galian_{{ $galian->item_id }}">
                                                        {{ $galian->dalam }} M</span>
                                                    <input type="hidden" id="dalam_value_{{ $galian->item_id }}"
                                                        name="lebar" value="{{ $galian->dalam }}">
                                                </td>
                                                <td id="total_galian_{{ $galian->item_id }}">Rp.
                                                    {{ format_uang($galian->total) }}</td>
                                                <td>
                                                    <span id="keterangan_galian_{{ $galian->item_id }}">
                                                        {{ $galian->keterangan }}</span>

                                                    <input type="hidden" id="keterangan_value_{{ $galian->item_id }}"
                                                        name="keterangan" value="{{ $galian->keterangan }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning text-light"
                                                        onclick="editgalian('{{ $galian->item_id }}')">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                        Ubah
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-xs text-center"
                                                        onclick="hapusgalian('{{ $galian->item_id }}')">
                                                        <i class="fa fa-trash"></i>
                                                        Hapus
                                                    </button>
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
                                    @if (isset($daftarGalian))
                                        <tr>
                                            <th colspan="5" class="text-right">Grand Total
                                            </th>
                                            <th>Rp. {{ format_uang($daftarGalian->sum('total')) }}

                                        </tr>
                                    @endif
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">Daftar bahan</div>

                        </div>
                        <div class="card-body">
                            <form action="" id="formBahan">

                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="sub-title">Pilih Bahan</h5>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbBahan">
                                                <option selected="selected" value="">Pilih Bahan
                                                </option>
                                                @foreach ($listBahan as $i => $bahan)
                                                    <option value="{{ $bahan->id }}" id="bahan_{{ $bahan->id }}">
                                                        {{ $bahan->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Jumlah</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="jumlah_bahan" id="jumlah_bahan"
                                                    placeholder="jumlah" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">keterangan</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="input_keterangan_bahan" id="input_keterangan_bahan"
                                                    placeholder="Keterangan bahan" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Pilih Harga</h5>
                                        <div class="form-radio">
                                            <form>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_bahan" name="harga_bahan"
                                                            value="siang" checked="checked">
                                                        <i class="helper"></i>Harga Siang
                                                    </label>
                                                </div>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_bahan" name="harga_bahan"
                                                            value="malam">
                                                        <i class="helper"></i>Harga Malam
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="">
                                            <button type="submit" id="btn_bahan" class="btn btn-primary">Update
                                                bahan</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered " width="100%" id="tableBahan">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="250">bahan</th>
                                        <th width="10">Jumlah</th>
                                        <th width="200">Total Harga</th>
                                        <th width="150">Keterangan</th>
                                        <th width="5%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarBahan->hasItem))
                                        @forelse ($daftarBahan->hasItem as $key => $bahan)
                                            <tr id="listBahan_{{ $bahan->id }}" class="list_table_bahan">
                                                <td class="text-center nomor_bahan" data-index="{{ $key + 1 }}">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $bahan->nama }}
                                                </td>
                                                <td>
                                                    <span
                                                        id="jumlah_bahan_tampil_{{ $bahan->id }}">{{ $bahan->pivot->qty }}
                                                    </span>

                                                    <input type="hidden" id="jumlah_bahan_value_{{ $bahan->id }}"
                                                        name="jumlah_bahan" value="{{ $bahan->pivot->qty }}">
                                                </td>
                                                <td id="total_bahan_{{ $bahan->id }}">Rp.
                                                    {{ format_uang($bahan->pivot->total) }}</td>
                                                <td>
                                                    <span id="keterangan_bahan_{{ $bahan->id }}">
                                                        {{ $bahan->pivot->keterangan }}</span>

                                                    <input type="hidden" id="keterangan_bahan_value_{{ $bahan->id }}"
                                                        name="keterangan_bahan" value="{{ $bahan->pivot->keterangan }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning text-light btn-edit"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="bahan" data-item="{{ $bahan->id }}">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                        Ubah
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-xs text-center btn-hapus"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="bahan" data-item="{{ $bahan->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="bahanTidakAda">
                                                <td colspan="10">Data Bahan tidak ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr class="bahanTidakAda">
                                            <td colspan="10">Data Bahan tidak ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($daftarBahan->hasItem))
                                        <tr>
                                            <th colspan="3" class="text-right">Grand Total
                                            </th>
                                            <th>Rp. {{ format_uang($daftarBahan->hasItem->sum('pivot.total')) }}

                                        </tr>
                                    @endif
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">Daftar Alat Bantu</div>

                        </div>
                        <div class="card-body">
                            <form action="" id="formAlatBantu">

                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="sub-title">Pilih Alat Bantu</h5>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbAlat_bantu">
                                                <option selected="selected" value="">Pilih Alat Bantu
                                                </option>
                                                @foreach ($listAlatBantu as $i => $AlatBantu)
                                                    <option value="{{ $AlatBantu->id }}"
                                                        id="alat_bantu_{{ $AlatBantu->id }}">
                                                        {{ $AlatBantu->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Jumlah</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="jumlah_alat_bantu" id="jumlah_alat_bantu"
                                                    placeholder="jumlah" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">keterangan</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="input_keterangan_alat_bantu"
                                                    id="input_keterangan_alat_bantu" placeholder="Keterangan alat bantu"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Pilih Harga</h5>
                                        <div class="form-radio">
                                            <form>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_alat_bantu"
                                                            name="harga_alat_bantu" value="siang" checked="checked">
                                                        <i class="helper"></i>Harga Siang
                                                    </label>
                                                </div>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_alat_bantu"
                                                            name="harga_alat_bantu" value="malam">
                                                        <i class="helper"></i>Harga Malam
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="">
                                            <button type="submit" id="btn_alat_bantu" class="btn btn-primary">Update
                                                Alat Bantu</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered " width="100%" id="tableAlat_bantu">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="250">Alat Bantu</th>
                                        <th width="10">Jumlah</th>
                                        <th width="200">Total Harga</th>
                                        <th width="150">Keterangan</th>
                                        <th width="5%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarAlatBantu->hasItem))
                                        @forelse ($daftarAlatBantu->hasItem as $key => $alatbantu)
                                            <tr id="listAlat_bantu_{{ $alatbantu->id }}" class="list_table_alat_bantu">
                                                <td class="text-center nomor_alat_bantu" data-index="{{ $key + 1 }}">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $alatbantu->nama }}
                                                </td>
                                                <td>
                                                    <span
                                                        id="jumlah_alat_bantu_tampil_{{ $alatbantu->id }}">{{ $alatbantu->pivot->qty }}
                                                    </span>

                                                    <input type="hidden"
                                                        id="jumlah_alat_bantu_value_{{ $alatbantu->id }}"
                                                        name="jumlah_alat_bantu" value="{{ $alatbantu->pivot->qty }}">
                                                </td>
                                                <td id="total_alat_bantu_{{ $alatbantu->id }}">Rp.
                                                    {{ format_uang($alatbantu->pivot->total) }}</td>
                                                <td>
                                                    <span id="keterangan_alat_bantu_{{ $alatbantu->id }}">
                                                        {{ $alatbantu->pivot->keterangan }}</span>

                                                    <input type="hidden"
                                                        id="keterangan_alat_bantu_value_{{ $alatbantu->id }}"
                                                        name="keterangan_alat_bantu"
                                                        value="{{ $alatbantu->pivot->keterangan }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning text-light btn-edit"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="alat_bantu" data-item="{{ $alatbantu->id }}">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                        Ubah
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-xs text-center btn-hapus"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="alat_bantu" data-item="{{ $alatbantu->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="alat_bantuTidakAda">
                                                <td colspan="10">Data alat bantu tidak ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr class="alat_bantuTidakAda">
                                            <td colspan="10">Data alat bantu tidak ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($daftarAlatBantu->hasItem))
                                        <tr>
                                            <th colspan="3" class="text-right">Grand Total
                                            </th>
                                            <th>Rp. {{ format_uang($daftarAlatBantu->hasItem->sum('pivot.total')) }}

                                        </tr>
                                    @endif
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">

                    <div class="card">
                        <div class="card-header  justify-content-between">
                            <div class="card-title">Daftar Transportasi</div>

                        </div>
                        <div class="card-body">
                            <form action="" id="formTransportasi">

                                <div class="row">
                                    <div class="col-2">
                                        <h5 class="sub-title">Pilih Transportasi</h5>
                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbTransportasi">
                                                <option selected="selected" value="">Pilih Transportasi
                                                </option>
                                                @foreach ($listTransportasi as $i => $Transportasi)
                                                    <option value="{{ $Transportasi->id }}"
                                                        id="transportasi_{{ $Transportasi->id }}">
                                                        {{ $Transportasi->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Jumlah</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="jumlah_transportasi" id="jumlah_transportasi"
                                                    placeholder="jumlah" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">keterangan</h5>
                                        <div class="form-group">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <input type="text" name="input_keterangan_transportasi"
                                                    id="input_keterangan_transportasi" placeholder="Keterangan Transportasi"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h5 class="sub-title">Pilih Harga</h5>
                                        <div class="form-radio">
                                            <form>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_transportasi"
                                                            name="harga_transportasi" value="siang" checked="checked">
                                                        <i class="helper"></i>Harga Siang
                                                    </label>
                                                </div>
                                                <div class="radio radiofill radio-inline">
                                                    <label>
                                                        <input type="radio" class="harga_transportasi"
                                                            name="harga_transportasi" value="malam">
                                                        <i class="helper"></i>Harga Malam
                                                    </label>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="">
                                            <button type="submit" id="btn_transportasi" class="btn btn-primary">Update
                                                Transportasi</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-bordered " width="100%" id="tableTransportasi">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="250">Transportasi</th>
                                        <th width="10">Jumlah</th>
                                        <th width="200">Total Harga</th>
                                        <th width="150">Keterangan</th>
                                        <th width="5%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarTransportasi->hasItem))
                                        @forelse ($daftarTransportasi->hasItem as $key => $Transportasi)
                                            <tr id="listTransportasi_{{ $Transportasi->id }}"
                                                class="list_table_transportasi">
                                                <td class="text-center nomor_transportasi"
                                                    data-index="{{ $key + 1 }}">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $Transportasi->nama }}
                                                </td>
                                                <td>
                                                    <span
                                                        id="jumlah_transportasi_tampil_{{ $Transportasi->id }}">{{ $Transportasi->pivot->qty }}
                                                    </span>

                                                    <input type="hidden"
                                                        id="jumlah_transportasi_value_{{ $Transportasi->id }}"
                                                        name="jumlah_transportasi"
                                                        value="{{ $Transportasi->pivot->qty }}">
                                                </td>
                                                <td id="total_transportasi_{{ $Transportasi->id }}">Rp.
                                                    {{ format_uang($Transportasi->pivot->total) }}</td>
                                                <td>
                                                    <span id="keterangan_transportasi_{{ $Transportasi->id }}">
                                                        {{ $Transportasi->pivot->keterangan }}</span>

                                                    <input type="hidden"
                                                        id="keterangan_transportasi_value_{{ $Transportasi->id }}"
                                                        name="keterangan_transportasi"
                                                        value="{{ $Transportasi->pivot->keterangan }}">
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-warning text-light btn-edit"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="transportasi" data-item="{{ $Transportasi->id }}">
                                                        <i class="nav-icon fas fa-edit"></i>
                                                        Ubah
                                                    </button>
                                                    <button type="button"
                                                        class="btn btn-danger btn-xs text-center btn-hapus"
                                                        data-pekerjaanutama="{{ $pekerjaanUtama->id }}"
                                                        data-modul="transportasi" data-item="{{ $Transportasi->id }}">
                                                        <i class="fa fa-trash"></i>
                                                        Hapus
                                                    </button>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr class="transportasiTidakAda">
                                                <td colspan="10">Data Transportasi tidak ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr class="transportasiTidakAda">
                                            <td colspan="10">Data Transportasi tidak ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($daftarTransportasi->hasItem))
                                        <tr>
                                            <th colspan="3" class="text-right">Grand Total
                                            </th>
                                            <th>Rp. {{ format_uang($daftarTransportasi->hasItem->sum('pivot.total')) }}

                                        </tr>
                                    @endif
                                </tfoot>

                            </table>
                        </div>
                    </div>
                </div>
            </div>

        @endif
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
                                        <strong>{{ $aduan->lokasi }}</strong>
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
        // -- galian
        function editgalian(id) {
            $('#cmbGalian').val(id).trigger('change');
            let getpanjangan = $('#panjang_value_' + id).val();
            let getlebar = $('#lebar_value_' + id).val();
            let getdalam = $('#dalam_value_' + id).val();
            let getketerangan = $('#keterangan_value_' + id).val();

            let lebar = $('#lebar_galian').val(getlebar);
            let dalam = $('#dalam_galian').val(getdalam);
            let panjang = $('#panjang_galian').val(getpanjangan);
            let keterangan = $('#keterangan_galian').val(getketerangan);

        }

        function hapusgalian(id) {
            let content = '';
            let item = $('#listgalian_' + id).length;
            if (item > 0) {
                $('#listgalian_' + id).remove();

                $('#tableGalian').append(content);
            }
            $('.nomor_galian').each(function(index, item) {
                if (parseInt($(item).data('index')) > 2) {
                    $(item).text(index + 1);
                }
            });
        }
        // -- end galian
        $(document).ready(function() {
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

            let id = $('#idPekerjaan').val();

            $('#cmbBahan').select2({
                placeholder: '--- Pilih Bahan ---',
                width: '100%'
            });
            $('#cmbAlat_bantu').select2({
                placeholder: '--- Pilih Alat Bantu ---',
                width: '100%'
            });
            $('#cmbTransportasi').select2({
                placeholder: '--- Pilih Transportasi ---',
                width: '100%'
            });

            // ----- Pekerjaan
            $('#cmbPekerjaan').select2({
                placeholder: '--- Pilih Pekerjaan ---',
                width: '100%'
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
            $('#jumlah_bahan').keypress(function(event) {
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
            $('#jumlah_alat_bantu').keypress(function(event) {
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

            $("#cmbPekerjaan").on("change", function(e) {
                $('#cmbPekerjaan').parent().removeClass('is-invalid')
            });

            function capitalizeFirstLetter(string) {
                return string.replace(/^./, string[0].toUpperCase());
            }

            $(".btn-hapus").on("click", function(e) {
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
                $.ajax({
                    type: 'POST',
                    url: "{{ route('pelaksanaan-pekerjaan.hapus.item') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        id,
                        modul,
                        item,
                    },
                    success: function(data) {
                        toast('success mengubah ' + modul)
                    },
                    error: function(data) {
                        console.log(data);
                        Swal.fire({
                            title: 'Oops...',
                            text: "gagal Mengahapus " + modul,
                            footer: '<a href="">terdapat data yang kosong</a>'
                        })
                    }
                });

            });
            $(".btn-edit").on("click", function(e) {
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

            function elementPekerjaan(id, nomor, pekerjaan, jumlah, total, keterangan, modul) {
                let modulLowcasse = capitalizeFirstLetter(modul);
                let pekerjaanUtama = $('#idPekerjaan').val();
                return `<tr id="list${modulLowcasse}_${id}" class="list_table_${modul}">
                    <td class="text-center nomor_${modul}" data-index="${nomor}">${nomor}
                    </td>
                    <td>${pekerjaan}</td>
                    <td>
                        <span id="jumlah_${modul}_tampil_${id}">${jumlah}</span>
                        <input type="hidden" name="jumlah" id="jumlah_${modul}_value_${id}" value="${jumlah}">
                    </td>
                    <td id="total_${modul}_${id}">
                        Rp. ${total}
                    </td>
                    <td>
                        <span id="keterangan_${modul}_${id}">${keterangan === null ? '' : keterangan}</span>
                        <input type="hidden" name="keterangan" id="keterangan_${modul}_value_${id}" value="${keterangan === null ? '' : keterangan}">
                    </td>
                    <td>
                            <button class="btn btn-sm btn-warning text-light btn-edit"
                                data-pekerjaanutama="${pekerjaanUtama}"
                                data-modul="${modul}" data-item="${id}">
                                <i class="nav-icon fas fa-edit"></i>
                                Ubah
                            </button>
                            <button type="button"
                                class="btn btn-danger btn-xs text-center btn-hapus"
                                data-pekerjaanutama="${pekerjaanUtama}"
                                data-modul="${modul}" data-item="${id}">
                                <i class="fa fa-trash"></i>
                                Hapus
                            </button>
                    </td>
                </tr>`;

            }

            function saveform(modul) {

                let modulLowcasse = capitalizeFirstLetter(modul);
                let item = $('#cmb' + modulLowcasse).val();
                let jumlah = $('#jumlah_' + modul).val();
                let keterangan = $('#input_keterangan_' + modul).val();

                let harga = $("input[name='harga_" + modul + "']:checked").val();
                if (item === "") {
                    $('#cmb' + modulLowcasse).parent().addClass('is-invalid')
                }
                if (jumlah === "") {
                    $('#jumlah_' + modul).addClass("is-invalid");
                }

                if (item !== "" && jumlah !== "") {
                    $('.' + modul + 'TidakAda').remove();
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('pelaksanaan-pekerjaan.item') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id,
                            item,
                            jumlah,
                            harga,
                            keterangan
                        },
                        success: function(data) {
                            console.log(data);
                            const {
                                id,
                                item_id,
                                jumlah,
                                pekerjaan,
                                keterangan,
                                total
                            } = data.data;

                            let lengthPekerjaan = $('#list' + modulLowcasse + '_' + item_id)
                                .length;
                            let tableCount = $('#table' + modulLowcasse + '  > tbody > tr')
                                .length;
                            let nomor = tableCount + 1;

                            if (lengthPekerjaan !== 0) {
                                $('#jumlah_' + modul + '_tampil_' + item_id).text(jumlah);
                                $('#total_' + modul + '_' + item_id).text('Rp.' + total);
                                $('#keterangan_' + modul + '_' + item_id).text(keterangan);

                                $('#jumlah_' + modul + '_value_' + item_id).val(jumlah);
                                $('#keterangan_' + modul + '_value_' + item_id).val(keterangan);

                                toast('success mengubah ' + modul + '')
                            } else {
                                $('.' + modul + 'TidakAda').remove();
                                let content = elementPekerjaan(
                                    item_id,
                                    nomor,
                                    pekerjaan,
                                    jumlah,
                                    total,
                                    keterangan,
                                    modul
                                );
                                $('#table' + modulLowcasse).append(content);
                                toast('success menambah ' + modulLowcasse)
                            }
                            $('#cmb' + modulLowcasse).val(null).trigger('change');
                            $('#jumlah_' + modul).val('');
                            $('#input_keterangan_' + modul).val('');

                            $(".btn-edit").on("click", function(e) {
                                let id = $(this).data('pekerjaanutama');
                                let modul = $(this).data('modul');
                                let item = $(this).data('item');
                                let modulLowcasse = capitalizeFirstLetter(modul);
                                $('#cmb' + modulLowcasse).val(item).trigger('change');
                                let getjumlah = $('#jumlah_' + modul + '_value_' + item).val();
                                let getketerangan = $('#keterangan_' + modul + '_value_' + item)
                                    .val();
                                $('#jumlah_' + modul + '_tampil').val(getjumlah);
                                $('#jumlah_' + modul).val(getjumlah);
                                $('#keterangan_' + modul).val(getketerangan);
                            });

                            $(".btn-hapus").on("click", function(e) {
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
                                $.ajax({
                                    type: 'POST',
                                    url: "{{ route('pelaksanaan-pekerjaan.hapus.item') }}",
                                    data: {
                                        "_token": "{{ csrf_token() }}",
                                        id,
                                        modul,
                                        item,
                                    },
                                    success: function(data) {
                                        toast('success hapus ' + modul)
                                    },
                                    error: function(data) {
                                        console.log(data);
                                        Swal.fire({
                                            title: 'Oops...',
                                            text: "gagal Mengahapus " +
                                                modul,
                                            footer: '<a href="">terdapat data yang kosong</a>'
                                        })
                                    }
                                });

                            });


                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Oops...',
                                text: "Isi dengan lengkap",
                                footer: '<a href="">terdapat data yang kosong</a>'
                            })
                            console.log(data);
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Oops...',
                        text: "Isi data dengan lengkap",
                        footer: '<a href="">terdapat data yang kosong</a>'
                    })
                }
            }
            $('#formPekerjaan').on('submit', function(e) {
                e.preventDefault();
                let modul = 'pekerjaan';
                saveform(modul);
            });
            $('#formAlatBantu').on('submit', function(e) {
                e.preventDefault();
                let modul = 'alat_bantu';
                saveform(modul);
            });

            $('#formBahan').on('submit', function(e) {
                e.preventDefault();
                let modul = 'bahan';
                saveform(modul);
            });
            $('#formTransportasi').on('submit', function(e) {
                e.preventDefault();
                let modul = 'transportasi';
                saveform(modul);
            });
            // ------ End Pekerjaan

            // ----- Galian
            $('#cmbGalian').select2({
                placeholder: '--- Pilih Galian ---',
                width: '100%'
            });
            $('#lebar_galian').keypress(function(event) {
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
            $('#panjang_galian').keypress(function(event) {
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
            $('#dalam_galian').keypress(function(event) {
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

            function elementGalian(id, nomor, pekerjaan, lebar, panjang, dalam, total, keterangan) {
                return `<tr id="listgalian_${id}" class="list_table_galian">
                    <td class="text-center nomor_galian" data-index="${nomor}">${nomor}
                    </td>
                    <td>${pekerjaan}</td>
                    <td>
                        <span id="panjang_galian_${id}">${panjang} M</span>
                        <input type="hidden" name="panjang" id="panjang_value_${id}" value="${panjang}">
                    </td>
                    <td>
                        <span id="lebar_galian_${id}">${lebar} M</span>
                        <input type="hidden" name="lebar" id="lebar_value_${id}" value="${lebar}">
                    </td>
                    <td>
                        <span id="dalam_galian_${id}">${dalam} M</span>
                        <input type="hidden" name="dalam" id="dalam_value_${id}"  value="${dalam}">
                    </td>
                    <td id="total_galian_${id}">
                        Rp. ${total}
                    </td>
                    <td>
                        <span id="keterangan_galian_${id}">${keterangan} M</span>
                        <input type="hidden" name="keterangan" id="keterangan_value_${id}" value="${keterangan}">
                    </td>
                    <td>
                        <button class="btn btn-sm btn-warning text-light">
                            <i class="nav-icon fas fa-edit"  onclick="ubahgalian('${id}')"></i> Ubah</button>
                        <button class="btn btn-sm btn-danger text-light btn-hapus-pekerjaan"
                            onclick="hapusgalian('${id}')">
                            <i class="nav-icon fas fa-trash"></i> Hapus</button>
                    </td>
                </tr>`;

            }
            $("#cmbGalian").on("change", function(e) {
                $('#cmbGalian').parent().removeClass('is-invalid')
            });

            $('#formGalian').on('submit', function(e) {
                e.preventDefault();
                let item = $('#cmbGalian').val();
                let lebar = $('#lebar_galian').val();
                let dalam = $('#dalam_galian').val();
                let panjang = $('#panjang_galian').val();
                let keterangan = $('#keterangan_galian').val();
                let harga = $("input[name='harga_galian']:checked").val();

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


                if (item !== "" && panjang !== "" && lebar !== "" && dalam !== "") {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('pelaksanaan-pekerjaan.galian') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id,
                            item,
                            lebar,
                            dalam,
                            panjang,
                            keterangan,
                            harga,
                        },
                        success: function(data) {
                            console.log(data);
                            const {
                                id,
                                item_id,
                                pekerjaan,
                                panjang,
                                lebar,
                                dalam,
                                total,
                                keterangan
                            } = data.data;

                            let lengthGalian = $('#listgalian_' + item).length;
                            let tableCount = $('#tableGalian  > tbody > tr').length;
                            let nomor = tableCount + 1;

                            $('#cmbGalian').val('');
                            $('#lebar_galian').val('');
                            $('#dalam_galian').val('');
                            $('#panjang_galian').val('');
                            $('#keterangan_galian').val('');

                            if (lengthGalian !== 0) {
                                $('#panjang_galian_' + item_id).text(panjang + ' M');

                                $('#lebar_galian_' + item_id).text(lebar + ' M');
                                $('#dalam_galian_' + item_id).text(dalam + ' M');
                                $('#total_galian_' + item_id).text('Rp.' + total);
                                $('#keterangan_galian_' + item_id).text(keterangan);

                                $('#panjang_value_' + item_id).val(panjang);
                                $('#lebar_value_' + item_id).val(lebar);
                                $('#dalam_value_' + item_id).val(dalam);
                                $('#keterangan_value_' + item_id).val(keterangan);
                                toast('success mengubah galian')
                            } else {
                                $('.galianTidakAda').remove();
                                let content = elementGalian(
                                    item_id, nomor, pekerjaan, lebar, panjang, dalam,
                                    total, keterangan);
                                $('#tableGalian').append(content);
                                toast('success mengubah galian')

                            }
                            $("#cmbGalian").select2("val", "");
                            $('#cmbGalian').val(null).trigger('change');



                        },
                        error: function(data) {
                            console.log(data);
                            Swal.fire({
                                title: 'Oops...',
                                text: "Isi dengan lengkap",
                                footer: '<a href="">terdapat data yang kosong</a>'
                            })
                        }
                    });
                } else {

                    Swal.fire({
                        title: 'Oops...',
                        text: "Isi dengan lengkap",
                        footer: '<a href="">terdapat data yang kosong</a>'
                    })

                }
            });

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
        var map = L.map('map').setView(lat_long.split(","), 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var marker = L.marker(lat_long.split(",")).addTo(map)
            .bindPopup('<b>' + lokasi + '</b>').openPopup();
    </script>
@endpush
