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
                                    <a href="{{ asset('excel/Template Pekerjaan.xlsx') }}" class="text-danger">Download
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
    <script></script>
@endpush
