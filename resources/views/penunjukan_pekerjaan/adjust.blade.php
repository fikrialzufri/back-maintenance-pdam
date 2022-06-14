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
                    <div class="card-header">
                        <div class="card-title">Detail Pekerjaan</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">

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
                                @if (!auth()->user()->hasRole('admin-asisten-manager'))
                                    @if ($pekerjaanUtama)
                                        @if ($pekerjaanUtama->status == 'selesai koreksi')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="no_ticket" class=" form-control-label">Total Tagihan
                                                            Pekerjaan</label>
                                                    </div>
                                                    <div>
                                                        <h3>
                                                            <strong id="total_tagihan_pekerjaan">Rp.
                                                                {{ format_uang($totalPekerjaan) }}</strong>
                                                        </h3>
                                                    </div>

                                                </div>
                                            </div>
                                        @endif

                                    @endif
                                @endif
                                <div class="col-12">
                                    <div class="form-group">
                                        <h6 class="">Lokasi Pekerjaan</h6>
                                    </div>
                                    <div id="mapdua"></div>
                                </div>
                            </div>
                            <div class="col-6">
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
        <input type="hidden" name="id_pekerjaan" id="idPekerjaan" value="{{ $pekerjaanUtama->id }}">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header  justify-content-between">
                        <div class="card-title">Daftar Pekerjaan</div>

                    </div>
                    <div class="card-body">
                        <div>
                            <label for="rekanan" class=" form-control-label">
                                <h3>Pekerjaan :
                                    {{ $pekerjaanUtama->No_Spk }} </h3>
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
                                            @forelse ($pekerjaanUtama->hasItem as $nomor => $barang)
                                                <tr id="{{ $barang->slug }}_{{ $pekerjaanUtama->id }}">
                                                    <td>{{ $nomor + 1 }}
                                                    </td>
                                                    <td>{{ $barang->nama }}</td>
                                                    <td>{{ $barang->jenis }}</td>
                                                    <td id="qty_{{ $barang->slug }}_{{ $pekerjaanUtama->id }}">
                                                        {{ $barang->qty }}</td>

                                                    <td>
                                                        Rp. {{ format_uang($barang->harga) }}
                                                    </td>

                                                    <td>
                                                        Rp.
                                                        {{ format_uang($barang->harga * $barang->qty) }}
                                                    </td>

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
                                                <th>{{ $pekerjaanUtama->hasItem->sum('pivot.qty') }}</th>
                                                @if ($perencaan === true)
                                                    <th></th>
                                                    <th>
                                                        <span id="total_harga_tampil">
                                                            Rp.
                                                            {{ format_uang($pekerjaanUtama->total_harga) }}
                                                        </span>
                                                        <input type="hidden" name="total_harga_value"
                                                            value="{{ $pekerjaanUtama->total_harga }}"
                                                            id="total_harga_value">
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
                                                <th @if ($perencaan === true) width="10" @else width="50" @endif>
                                                    Volume</th>
                                                @if ($perencaan === true)
                                                    <th>Total Harga</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @forelse ($pekerjaanUtama->hasGalianPekerjaan as $key => $value)
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
                                                    {{ $pekerjaanUtama->luas_galian }} m<sup>2</sup>
                                                </th>
                                                @if ($perencaan === true)
                                                    <th>
                                                        <span id="total_galian_tampil">
                                                            Rp.
                                                            {{ format_uang($pekerjaanUtama->hasGalianPekerjaan->sum('total')) }}
                                                        </span>
                                                        <input type="hidden" name="total_galian_value"
                                                            value="{{ $pekerjaanUtama->hasGalianPekerjaan->sum('total') }}"
                                                            id="total_galian_value">
                                                    </th>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th @if ($perencaan === true) colspan="6" @else colspan="5" @endif>
                                                    Grand Total
                                                </th>
                                                <th>
                                                    <span id="total_semua_pekerjaan_tampil">
                                                        Rp.
                                                        {{ format_uang($pekerjaanUtama->total_pekerjaan) }}
                                                    </span>
                                                    <input type="hidden" name="total_semua_pekerjaan_value"
                                                        value="{{ $pekerjaanUtama->total_pekerjaan }}"
                                                        id="total_semua_pekerjaan_value">

                                                </th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-12 mt-3">
                                <div class="mb-4">
                                    <h6>Daftar Koreksi Pekerjaan</h6>
                                </div>

                                @if ($pekerjaanUtama->tagihan == 'tidak')
                                    @if ($tombolEdit === 'bisa')
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
                                                                    {{ $pekerjaan->nama }} || Kategori :
                                                                    {{ $pekerjaan->jenis }}
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
                                                    <h5 class="sub-title">Keterangan</h5>
                                                    <div class="form-group">
                                                        <div class="input-group mb-2 mr-sm-2">
                                                            <input type="text" name="input_keterangan_pekerjaan"
                                                                id="input_keterangan_pekerjaan"
                                                                placeholder="Keterangan Pekerjaan" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <h5 class="sub-title">Pilih Waktu</h5>
                                                    <div class="form-radio">

                                                        <div class="radio radiofill radio-inline">
                                                            <label>
                                                                <input type="radio" class="harga_pekerjaan"
                                                                    name="harga_pekerjaan" value="siang" checked="checked">
                                                                <i class="helper"></i>Siang
                                                            </label>
                                                        </div>
                                                        <div class="radio radiofill radio-inline">
                                                            <label>
                                                                <input type="radio" class="harga_pekerjaan"
                                                                    name="harga_pekerjaan" value="malam">
                                                                <i class="helper"></i>Malam
                                                            </label>
                                                        </div>
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
                                        </form>
                                    @endif
                                @endif
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
                                        @forelse ($daftarPelaksaanAdjust as $key => $pekerjaan)
                                            <tr id="listPekerjaan_{{ $pekerjaan->id }}" class="list_table_pekerjaan">
                                                <td class="text-center nomor_pekerjaan" data-index="{{ $key + 1 }}">
                                                    {{ $key + 1 }}
                                                </td>
                                                <td>
                                                    {{ $pekerjaan->pekerjaan }}
                                                </td>
                                                <td>
                                                    <span
                                                        id="jumlah_pekerjaan_tampil_{{ $pekerjaan->id }}">{{ $pekerjaan->qty }}
                                                    </span>

                                                    <input type="hidden" id="jumlah_pekerjaan_value_{{ $pekerjaan->id }}"
                                                        name="jumlah_pekerjaan" value="{{ $pekerjaan->qty }}">
                                                </td>
                                                @if ($perencaan == true)
                                                    <td>
                                                        <span id="total_pekerjaan_tampil_{{ $pekerjaan->id }}">
                                                            Rp.
                                                            {{ format_uang($pekerjaan->total) }}
                                                        </span>
                                                        <input type="hidden"
                                                            id="total_pekerjaan_value_{{ $pekerjaan->id }}"
                                                            name="total_pekerjaan" value="{{ $pekerjaan->total }}"
                                                            class="total_pekerjaan">
                                                    </td>
                                                @endif
                                                <td>
                                                    </span>
                                                    <span id="keterangan_pekerjaan_{{ $pekerjaan->id }}">
                                                        {{ $pekerjaan->keterangan }}</span>

                                                    <input type="hidden"
                                                        id="keterangan_pekerjaan_value_{{ $pekerjaan->id }}"
                                                        name="keterangan_pekerjaan" value="{{ $pekerjaan->keterangan }}">
                                                </td>
                                                @if ($rekanan_id == null)
                                                    @if ($tombolEdit === 'bisa')
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
                                                    @endif
                                                @endif
                                            </tr>
                                        @empty
                                            <tr class="pekerjaanTidakAda">
                                                <td colspan="10">Data Pekerjaan tidak ada</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>

                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    {{--  --}}
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
                                        <strong>{{ $aduan->kategori_aduan }}</strong>
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
                                                    <li>{{ $pekerjaanUtama->nama }}</li>
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
                                        <label for="detail_lokasi" class=" form-control-label">Detail Lokasi</label>
                                    </div>
                                    <div>
                                        <strong>{{ $aduan->detail_lokasi }}</strong>
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
        // -- galian

        $('#cmbPekerjaan').select2({
            placeholder: '--- Pilih Pekerjaan ---',
            width: '100%'
        });

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


        function totalHarga(modul) {
            let sumTotal = 0;
            let sumGrandTotal = 0;

            $('.total_' + modul).each(function() {
                sumTotal += parseFloat($(this)
                    .val());
            });

            let totalGalian = $('#total_galian_value').val();
            let total = $('#total_harga_value').val();
            sumGrandTotal = parseFloat(total) + parseFloat(sumTotal) + parseFloat(totalGalian);

            console.log(total);
            console.log(sumGrandTotal);

            $('#total_semua_pekerjaan_tampil').text(formatRupiah(Math.floor(
                sumGrandTotal).toString(), 'Rp. '));
            $('#total_tagihan_pekerjaan').text(formatRupiah(Math.floor(
                sumGrandTotal).toString(), 'Rp. '));

            $('#total_semua_pekerjaan_value').val(sumGrandTotal);

        }

        $(document).on("click", ".btn-galian-edit", function(e) {
            let galian_id = $(this).data('galian');
            let getpanjangan = $('#panjang_value_' + galian_id).val();
            let getlebar = $('#lebar_value_' + galian_id).val();
            let getdalam = $('#dalam_value_' + galian_id).val();
            let getketerangan = $('#keterangan_value_' + galian_id).val();


            let lebar = $('#lebar_galian').val(getlebar);
            let dalam = $('#dalam_galian').val(getdalam);
            let panjang = $('#panjang_galian').val(getpanjangan);
            let keterangan = $('#keterangan_galian').val(getketerangan);

            $('#cmbGalian').val(galian_id).trigger('change');
        });

        $(document).on("click", ".btn-galian-hapus", function(e) {
            let content = '';
            let modul = 'galian';
            let item_id = $(this).data('galian');

            let item = $('#listgalian_' + item_id).length;
            if (item > 0) {
                $('#listgalian_' + item_id).remove();

                $('#tableGalian').append(content);
            }

            let n = 1;
            $('.nomor_' + modul).each(function(index, item) {
                let number = n++;
                $(item).text(number);
                $(this).attr('data-index', number);
            });


            $.when($.ajax({
                type: 'POST',
                url: "{{ route('pelaksanaan-pekerjaan.galian.hapus') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id,
                    item_id,
                },
                success: function(data) {
                    toast('success hapus ' + modul)
                    totalHarga(modul);
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
                totalHarga(modul)
            });
        });


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
            $.when($.ajax({
                type: 'POST',
                url: "{{ route('pelaksanaan-pekerjaan.hapus.pekerjaan') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    id,
                    modul,
                    item,
                },
                success: function(data) {
                    console.log(data);
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
            })).then(function(data, textStatus, jqXHR) {
                console.log(data);
                totalHarga(modul)
                $('#cmb' + modulLowcasse).val(null).trigger('change');
                $('#jumlah_' + modul).val('');
            });

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

        // -- end galian
        $(document).ready(function() {


            // ----- Pekerjaan


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
            $('#jumlah_dokumentasi').keypress(function(event) {
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
            $('#jumlah_transportasi').keypress(function(event) {
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
            $("#cmbTransportasi").on("change", function(e) {
                $('#cmbTransportasi').parent().removeClass('is-invalid')
            });

            function elementPekerjaan(id, nomor, pekerjaan, jumlah, total, keterangan, modul, perencanaan) {
                console.log(id);
                let modulLowcasse = capitalizeFirstLetter(modul);
                let pekerjaanUtama = $('#idPekerjaan').val();

                let elementTotal = '';

                if (perencanaan === 'true') {
                    elementTotal = `<td>
                        <span id="total_${modul}_tampil_${id}">
                            ${formatRupiah(Math.floor(total).toString(), '')}
                        </span>
                        <input type="hidden" id="total_${modul}_value_${id}"
                            name="total_${modul}" value="${total}" class="total_${modul}">
                    </td>`;
                }

                return `<tr id="list${modulLowcasse}_${id}" class="list_table_${modul}">
                    <td class="text-center nomor_${modul}" data-index="${nomor}">${nomor}
                    </td>
                    <td>${pekerjaan}</td>
                    <td>
                        <span id="jumlah_${modul}_tampil_${id}">${jumlah}</span>
                        <input type="hidden" name="jumlah" id="jumlah_${modul}_value_${id}" value="${jumlah}">
                    </td>
                    ${elementTotal}
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
                    $.when($.ajax({
                        type: 'POST',
                        url: "{{ route('pelaksanaan-pekerjaan.pekerjaan') }}",
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
                                perencanaan,
                                total
                            } = data.data;


                            let lengthPekerjaan = $('#list' + modulLowcasse + '_' + item_id)
                                .length;

                            let tableCount = $('#table' + modulLowcasse + '  > tbody > tr')
                                .length;
                            let nomor = tableCount + 1;

                            if (lengthPekerjaan !== 0) {
                                $('#jumlah_' + modul + '_tampil_' + item_id).text(jumlah);

                                $('#total_' + modul + '_tampil_' + item_id).text(formatRupiah(
                                    Math
                                    .floor(total).toString(), 'Rp. '));
                                $('#total_' + modul + '_value_' + item_id).val(total);

                                $('#keterangan_' + modul + '_' + item_id).text(keterangan);

                                $('#jumlah_' + modul + '_value_' + item_id).val(jumlah);
                                $('#keterangan_' + modul + '_value_' + item_id).val(keterangan);

                                toast('success mengubah ' + modul + '')
                            } else {

                                let content = elementPekerjaan(
                                    item_id,
                                    nomor,
                                    pekerjaan,
                                    jumlah,
                                    total,
                                    keterangan,
                                    modul,
                                    perencanaan
                                );
                                $('#table' + modulLowcasse).append(content);
                                toast('success menambah ' + modulLowcasse)
                            }
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
                        totalHarga(modul);

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
            $('#formDokumentasi').on('submit', function(e) {
                e.preventDefault();
                let modul = 'dokumentasi';
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
