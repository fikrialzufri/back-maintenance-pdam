@extends('template.app')
@section('title', 'Ubah Aduan ' . $aduan->no_aduan)

@push('head')
<!-- Load Leaflet from CDN -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"
integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A=="
crossorigin=""/>
<!-- Load Esri Leaflet Geocoder from CDN -->
<link rel="stylesheet" href="https://unpkg.com/esri-leaflet-geocoder@3.1.2/dist/esri-leaflet-geocoder.css"
integrity="sha512-IM3Hs+feyi40yZhDH6kV8vQMg4Fh20s9OzInIIAc4nx7aMYMfo+IenRUekoYsHZqGkREUgx0VvlEsgm7nCDW9g=="
crossorigin="">
<style type="text/css">
    #map {
        height: 45vh;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="no_ticket" class=" form-control-label">Nomor Tiket</label>
                                    </div>
                                    <div>
                                        <input type="text" name="no_ticket" placeholder="Nomor Tiket"
                                            class="{{ $errors->has('no_ticket') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $aduan->no_ticket }}" required id="">
                                    </div>
                                    @if ($errors->has('no_ticket'))
                                    Nomor Tiket
                                    <span class="text-danger">
                                        <strong id="textkk">Nomor Tiket wajib diisi!</strong>
                                    </span>
                                    @endif
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
                                        <input type="text" name="mps" placeholder="MPS"
                                            class="{{ $errors->has('mps') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $aduan->mps }}" required id="">
                                    </div>
                                    @if ($errors->has('mps'))
                                    MPS
                                    <span class="text-danger">
                                        <strong id="textkk">MPS wajib diisi!</strong>
                                    </span>
                                    @endif
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
                                        <input type="text" name="atas_nama" placeholder="Atas Nama"
                                            class="{{ $errors->has('atas_nama') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $aduan->atas_nama }}" required id="">
                                    </div>
                                    @if ($errors->has('atas_nama'))
                                    Atas Nama
                                    <span class="text-danger">
                                        <strong id="textkk">Atas Nama wajib diisi!</strong>
                                    </span>
                                    @endif
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
                                        <input type="text" name="sumber_informasi" placeholder="Sumber Informasi"
                                            class="{{ $errors->has('sumber_informasi') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $aduan->sumber_informasi }}" required id="">
                                    </div>
                                    @if ($errors->has('sumber_informasi'))
                                    Sumber Informasi
                                    <span class="text-danger">
                                        <strong id="textkk">Sumber Informasi wajib diisi!</strong>
                                    </span>
                                    @endif
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
                                        @forelse ($jenis_aduan as $index => $item)
                                        <div>
                                            <input class="border-checkbox" type="checkbox" name="jenis_aduan_id[]" value="{{ $item->id }}" id="checkbox{{ $index }}" {{ in_array($item->id, $jenisAduan) ? 'checked' : ''}}>
                                            <label class="border-checkbox-label" for="checkbox{{ $index }}">{{ $item->nama }}</label>
                                        </div>
                                        @empty
                                            <strong>Jenis Aduan Kosong</strong>
                                        @endforelse
                                    </div>
                                    @if ($errors->has('jenis_aduan_id'))
                                    Sumber Informasi
                                    <span class="text-danger">
                                        <strong id="textkk">Sumber Informasi wajib diisi!</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="body" class=" form-control-label">Detail Aduan</label>
                                    </div>
                                    <div>
                                        <textarea
                                            class="{{ $errors->has('body') ? 'form-control is-invalid' : 'form-control' }}"
                                            name="body" id="body" rows="10" placeholder="Detail Aduan"
                                            required>{{ $aduan->body }}</textarea>
                                    </div>
                                    @if ($errors->has('body'))
                                    Detail Aduan
                                    <span class="text-danger">
                                        <strong id="textkk">Detail Aduan wajib diisi!</strong>
                                    </span>
                                    @endif
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
                                        <input type="text" name="lokasi" placeholder="Lokasi"
                                            class="{{ $errors->has('lokasi') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $aduan->lokasi }}" required id="lokasi">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="lat_long" class=" form-control-label">Koordinat (Latitude, Longitude)</label>
                                    </div>
                                    <div>
                                        <input type="text" name="lat_long" placeholder="Koordinat"
                                            class="{{ $errors->has('lat_long') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $aduan->lat_long }}" required id="lat_long">
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
                    <div class="card-footer clearfix">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@push('script')
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

    var geocodeService = L.esri.Geocoding.geocodeService({
        apikey: "AAPK8176d782dece458a826c6ad408eeadf1rNg3Erse47Uah_Ij6q4nyG-WI3ryr5IBT8nb3hRNh2TfpyCkl0wVQjdk3nzJbBFo" // replace with your api key - https://developers.arcgis.com
    });

    var popup = L.popup();

    function onMapClick(e) {
        // Auto Fill form lat_long
        document.getElementById('lat_long').value = e.latlng.toString()

        geocodeService.reverse().latlng(e.latlng).run(function (error, result) {
            popup
                .setLatLng(e.latlng)
                .setContent("Anda memilih koordinat: " + e.latlng.toString() + " Dengan alamat: " + result.address.LongLabel)
                .openOn(map);

            // Auto Fill form lokasi
            document.getElementById('lokasi').value = result.address.LongLabel.toString()
        });
    }
    map.on('click', onMapClick);
</script>
@endpush