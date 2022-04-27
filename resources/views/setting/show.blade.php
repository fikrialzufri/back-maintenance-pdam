@extends('template.app')
@section('title', 'Setting')
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-5">
                <div class="card">
                    <!-- /.card-header -->
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="galian" class=" form-control-label">Galian </label>
                                </div>
                                <div>
                                    <div class="input-group mb-2 mr-sm-2">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">Rp.</div>
                                        </div>
                                        <input type="text" name="galian" placeholder="isi Galian" id="galian"
                                            class="form-control  {{ $errors->has('galian') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ format_uang($setting->galian) }}" required>
                                    </div>
                                </div>
                                @if ($errors->has('galian'))
                                    <span class="text-danger">
                                        <strong id="textGalian">{{ $errors->first('galian') }}</strong>
                                    </span>
                                @endif
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
        </div>
    </div><!-- /.container-fluid -->

@stop

@push('script')
    <script>
        $(function() {
            $("#nama").keypress(function() {
                $("#nama").removeClass("is-invalid");
                $("#textNama").html("");
            });
            $("#description").keypress(function() {
                $("#description").removeClass("is-invalid");
                $("#textDescription").html("");
            });

            $('#galian').on("input", function() {

                let val = formatRupiah(this.value, '');
                $('#galian').val(val);
            });

            /* Fungsi formatRupiah */
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, '').toString(),
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
                return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
            }
        });
    </script>
@endpush
