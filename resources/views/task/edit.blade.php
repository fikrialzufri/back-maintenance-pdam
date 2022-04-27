@extends('template.app')
@section('Edit', 'Edit Manajemen Hak Akses ' . $title)

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ubah {{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="nama" class=" form-control-label">Nama {{ $title }}</label>
                                </div>
                                <div>
                                    <input type="text" name="name" id="nama" placeholder="Nama {{ $title }}"
                                        class="form-control  {{ $errors->has('name') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $task->name }}" required>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="text-danger">
                                        <strong id="textNama">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="description" class=" form-control-label">Description
                                        {{ ucfirst($route) }}</label>
                                </div>
                                <div>
                                    <input type="text" name="description" placeholder="Description {{ ucfirst($route) }}"
                                        id="description"
                                        class="form-control  {{ $errors->has('description') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $task->description }}" required>
                                </div>
                                @if ($errors->has('description'))
                                    <span class="text-danger">
                                        <strong id="textDescription">{{ $errors->first('name') }}</strong>
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
        </div><!-- /.container-fluid -->
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
                $("#textdescription").html("");
            });

        });
    </script>
@endpush
