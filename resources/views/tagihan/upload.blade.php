@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- /.card-header -->
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">

                            <div class="form-group">
                                <div>
                                    <label for="No" class=" form-control-label">Nomor {{ ucfirst($route) }} / Nomor
                                        Spk</label>
                                </div>
                                <div>
                                    <input type="text" name="nomor_tagihan" placeholder="No {{ ucfirst($route) }}"
                                        id="nomor_tagihan"
                                        class="form-control  {{ $errors->has('nomor_tagihan') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ old('nomor_tagihan') }}" required>
                                </div>
                                @if ($errors->has('nomor_tagihan'))
                                    <span class="text-danger">
                                        <strong id="textNo">{{ $errors->first('nomor_tagihan') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="rekanan_id">Pilih Rekanan</label>
                                <select name="rekanan_id" class="selected2 form-control" id="cmbRekanan">
                                    <option value="">--Pilih Rekanan--</option>
                                    @foreach ($dataRekanan as $item)
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
                            <div class="form-group">
                                <div>
                                    <label for="total_lokasi" class=" form-control-label">Total Lokasi</label>
                                </div>
                                <div>
                                    <input type="text" name="total_lokasi" placeholder="Total Lokasi" id="total_lokasi"
                                        class="form-control  {{ $errors->has('total_lokasi') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ old('total_lokasi') }}" required>
                                </div>
                                @if ($errors->has('total_lokasi'))
                                    <span class="text-danger">
                                        <strong id="textNo">{{ $errors->first('total_lokasi') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">Pilih Bulan</label>
                                <select class="form-control select2" id="cmbBulan" name="bulan">
                                    <option selected="selected" value="">Pilih Bulan
                                    </option>
                                    @foreach ($month as $index => $bulan)
                                        <option value="{{ $index + 1 }}" id="bulan_{{ $bulan }}"
                                            {{ old('bulan') == $index + 1 ? 'selected' : '' }}>
                                            {{ $bulan }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('file'))
                                    <span class="text-danger">Mohon upload dengan benar, file harus berekstensi .csv
                                        dengan format yang sesuai.</span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="nama" class=" form-control-label">Silahkan upload file {{ $title }}
                                        dibawah ini</label>
                                </div>
                                <div>
                                    <input type="file" name="file" id="exampleInputFile" required>
                                </div>
                                @if ($errors->has('file'))
                                    <span class="text-danger">Mohon upload dengan benar, file harus berekstensi .csv
                                        dengan format yang sesuai.</span>
                                @endif
                                <span class="text-danger">
                                    <a href="{{ asset('excel/Template Tagihan.xlsx') }}" class="text-danger">Download
                                        Template
                                        {{ $title }}</a>
                                </span>
                            </div>
                        </div>


                        <!-- /.card-body -->
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->

@stop

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"></script>
    <script>
        $('#cmbRekanan').select2({
            placeholder: '--- Pilih Rekanan ---',
            width: '100%'
        });
        $('#cmbBulan').select2({
            placeholder: '--- Pilih Bulan ---',
            width: '100%'
        });
    </script>
@endpush
