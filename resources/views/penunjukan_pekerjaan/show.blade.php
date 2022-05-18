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
                        <div class="card-header">
                            <div class="card-title">Daftar Pekerjaan</div>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered " width="100%">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th width="10">Panjang</th>
                                        <th width="10">Lebar</th>
                                        <th width="10">Dalam</th>
                                        <th>Total Harga</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($pekerjaan->hasGalianPekerjaan))

                                        @forelse ($pekerjaan->hasGalianPekerjaan as $key => $value)
                                            <tr>
                                                <td>{{ $key + 1 }}
                                                </td>
                                                <td>{{ $value->panjang }} m</td>
                                                <td>{{ $value->lebar }} m</td>
                                                <td>{{ $value->dalam }} m</td>
                                                <td>Rp. {{ format_uang($value->total) }}</td>
                                                <td>{{ $value->keterangan }}</td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Data Item Galian ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="10">Data Item Galian ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($pekerjaan->hasGalianPekerjaan))
                                        <tr>
                                            <th colspan="4" class="text-right">Grand Total
                                            </th>
                                            <th>Rp. {{ format_uang($pekerjaan->total_galian) }}

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
                        <div class="card-header">
                            <div class="card-title">Daftar Bahan</div>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered " width="100%">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Nama</th>
                                        <th>Jenis barang</th>
                                        <th>Satuan barang</th>
                                        <th width="10">Jumlah</th>
                                        <th width="150">Harga</th>
                                        <th width="150">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarBarang->hasItem))
                                        @forelse ($daftarBarang->hasItem as $nomor => $barang)
                                            <tr id="{{ $barang->slug }}_{{ $aduan->id }}">
                                                <td>{{ $nomor + 1 }}
                                                </td>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->jenis }}</td>
                                                <td>{{ $barang->satuan }}</td>
                                                <td class="text-center">
                                                    {{ $barang->pivot->qty }}</td>
                                                <td>
                                                    Rp. {{ format_uang($barang->pivot->harga) }}
                                                </td>
                                                <td>
                                                    Rp. {{ format_uang($barang->pivot->harga * $barang->pivot->qty) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Data Item tidak ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="10">Data Item tidak ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($daftarBarang->hasItem))
                                        <tr>
                                            <th colspan="4" class="text-right">Grand Total
                                            </th>
                                            <th class="text-center">{{ $daftarBarang->hasItem->sum('pivot.qty') }}
                                            </th>
                                            <th>Rp. {{ format_uang($daftarBarang->hasItem->sum('pivot.harga')) }}
                                            </th>
                                            <th>Rp. {{ format_uang($daftarBarang->total_harga) }}

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
                        <div class="card-header">
                            <div class="card-title">Alat Bantu</div>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered " width="100%">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Nama</th>
                                        <th>Jenis barang</th>
                                        <th>Satuan barang</th>
                                        <th width="10">Jumlah</th>
                                        <th width="150">Harga</th>
                                        <th width="150">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarBarang->hasItem))
                                        @forelse ($daftarAlatBantu->hasItem as $nomor => $barang)
                                            <tr id="{{ $barang->slug }}_{{ $aduan->id }}">
                                                <td>{{ $nomor + 1 }}
                                                </td>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->jenis }}</td>
                                                <td>{{ $barang->satuan }}</td>
                                                <td class="text-center">
                                                    {{ $barang->pivot->qty }}</td>
                                                <td>
                                                    Rp. {{ format_uang($barang->pivot->harga) }}
                                                </td>
                                                <td>
                                                    Rp. {{ format_uang($barang->pivot->harga * $barang->pivot->qty) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Data Item tidak ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="10">Data Item tidak ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($daftarAlatBantu->hasItem))
                                        <tr>
                                            <th colspan="4" class="text-right">Grand Total
                                            </th>
                                            <th class="text-center">{{ $daftarAlatBantu->hasItem->sum('pivot.qty') }}
                                            </th>
                                            <th>Rp. {{ format_uang($daftarAlatBantu->hasItem->sum('pivot.harga')) }}
                                            </th>
                                            <th>Rp. {{ format_uang($daftarAlatBantu->total_harga) }}

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
                        <div class="card-header">
                            <div class="card-title">Transportasi</div>
                        </div>
                        <div class="card-body">

                            <table class="table table-bordered " width="100%">
                                <thead>
                                    <tr>
                                        <th width="5">#</th>
                                        <th>Nama</th>
                                        <th>Jenis barang</th>
                                        <th>Satuan barang</th>
                                        <th width="10">Jumlah</th>
                                        <th width="150">Harga</th>
                                        <th width="150">Total Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($daftarTransportasi->hasItem))
                                        @forelse ($daftarTransportasi->hasItem as $nomor => $barang)
                                            <tr id="{{ $barang->slug }}_{{ $aduan->id }}">
                                                <td>{{ $nomor + 1 }}
                                                </td>
                                                <td>{{ $barang->nama }}</td>
                                                <td>{{ $barang->jenis }}</td>
                                                <td>{{ $barang->satuan }}</td>
                                                <td class="text-center">
                                                    {{ $barang->pivot->qty }}</td>
                                                <td>
                                                    Rp. {{ format_uang($barang->pivot->harga) }}
                                                </td>
                                                <td>
                                                    Rp. {{ format_uang($barang->pivot->harga * $barang->pivot->qty) }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10">Data Item tidak ada</td>
                                            </tr>
                                        @endforelse
                                    @else
                                        <tr>
                                            <td colspan="10">Data Item tidak ada</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    @if (isset($daftarTransportasi->hasItem))
                                        <tr>
                                            <th colspan="4" class="text-right">Grand Total
                                            </th>
                                            <th class="text-center">
                                                {{ $daftarTransportasi->hasItem->sum('pivot.qty') }}
                                            </th>
                                            <th>Rp. {{ format_uang($daftarTransportasi->hasItem->sum('pivot.harga')) }}
                                            </th>
                                            <th>Rp. {{ format_uang($daftarTransportasi->total_harga) }}

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
        $(function() {
            $('#rekanan').select2({
                placeholder: '--- Pilih Rekanan ---',
                width: '100%'
            });

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
