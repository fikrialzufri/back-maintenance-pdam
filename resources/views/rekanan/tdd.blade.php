@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">

                    <!-- /.card-header -->

                    <div class="card-body">
                        {{-- show image --}}
                        <img src="{{ asset('storage/rekanan/' . $rekanan->tdd) }}" alt="{{ $rekanan->nama }}."
                            class="card-img-top">

                    </div>

                    <!-- /.card-body -->
                    <div class="card-footer clearfix">

                    </div>

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
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                window.open('', '_self').close();
            }, 5000);
            setInterval(function() {
                window.open('', '_self').close();
            }, 5000);
        });
    </script>
@endpush
