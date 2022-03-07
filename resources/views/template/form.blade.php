@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form
            @if ($store == 'update') action="{{ route($route . '.' . $store, $data->id) }}" @else action="{{ route($route . '.' . $store) }}" @endif
            method="post" role="form" enctype="multipart/form-data">
            <div class="row">
                {{ csrf_field() }}
                @if ($store == 'update')
                    {{ method_field('PUT') }}
                @endif

                @foreach ($colomField as $index => $value)
                    <div class="col-md-{{ $countColom }}">
                        <div class="card">
                            <!-- /.card-header -->

                            <div class="card-body">
                                @foreach (array_slice($form, $value[0], $value[1]) as $key => $item)
                                    @include('template.input')
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-md-{{ $countColomFooter }}">

                    <div class="card">
                        <div class="card-footer clearfix">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- /.row -->
        <!-- Main row -->
        <!-- /.row (main row) -->
    </div><!-- /.container-fluid -->

@endsection

@push('script')
    <script>

    </script>
@endpush
