@extends('template.app')
@section('title', 'Ubah Aduan ' . $aduan->title)

@push('head')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" integrity="sha512-xodZBNTC5n17Xt2atTPuE1HxjVMSvLVW9ocqUKLsCC5CXdbqCmblAshOMAS6/keqq/sMZMZ19scR4PsZChSR7A==" crossorigin=""/>
<style type="text/css">
    #map {
        height: 45vh;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
        @csrf --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $title }}</h3>
                </div>
                <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <div>
                                        <label for="title" class=" form-control-label">Title</label>
                                    </div>
                                    <div>
                                        <input type="text" name="title" placeholder="Title"
                                            class="{{ $errors->has('title') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ old('title') }}" required id="">
                                    </div>
                                    @if ($errors->has('title'))
                                    Title
                                    <span class="text-danger">
                                        <strong id="textkk">Title wajib diisi!</strong>
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
                                            value="{{ old('sumber_informasi') }}" required id="">
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
                                        <select class="{{ $errors->has('sumber_informasi') ? 'form-control is-invalid' : 'form-control' }}" name="jenis_aduan_id" id="jenis_aduan_id">
                                        @forelse ($jenis_aduan as $item)
                                            <option class="form-control" value="{{ $item->id }}">{{ $item->name }}</option>
                                        @empty
                                            <option disabled>-</option>
                                        @endforelse
                                        </select>
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
                                            required>{{ old('body') }}</textarea>
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
                {{-- </form> --}}
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
                                            value="{{ old('lokasi') }}" required id="lokasi">
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
                                            value="{{ old('lat_long') }}" required id="lat_long">
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
<script type="text/javascript">
    var lat_long = "{{ $aduan->lat_long }}";
    var map = L.map('map').setView(lat_long.split(","), 13);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: 'pk.eyJ1IjoidnRyYWNlIiwiYSI6ImNsMW5seTQ4MDBlYzYzZHBkb2g3cG5sejkifQ.pdkpsfYUcs6c5Ln0evdR6Q'
    }).addTo(map);

    var marker = L.marker(lat_long.split(",")).addTo(map)
        .bindPopup('<b>Alamat</b>').openPopup();

    var popup = L.popup();

    function onMapClick(e) {
        popup
            .setLatLng(e.latlng)
            .setContent("You clicked the map at " + e.latlng.toString())
            // .setContent("You clicked the map at " + e.latlng.toString() + " Alamat: " + e.LongLabel)
            .openOn(map);

        // Auto Fill form lat_long
        document.getElementById('lat_long').value = e.latlng.toString()

        // geocodeService.reverse().latlng(e.latlng).run(function (error, result) {
        //     if (error) {
        //         return;
        //     }
        //     let elems = result.address.LongLabel.split(',')
        //     if (elems.length == 6) {
        //         address = result.address.LongLabel;
        //     }
        //     if (elems.length == 7) {
        //         // remove commercial name
        //         address = result.address.LongLabel.replace(result.address.Match_addr + ',', '');
        //     }
        //     let ad = address.split(',');
        //     alert(ad[0] + ', ' + ad[2] + ' ' + ad[1] + '');
        // });
    }
    map.on('click', onMapClick);
</script>
@endpush
