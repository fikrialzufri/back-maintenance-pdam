@extends('template.app')
@section('title', 'Edit Hak Akses ' . $user->name)
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="Name" class=" form-control-label">Name</label>
                                </div>
                                <div>
                                    <input type="text" name="name" placeholder="Name User"
                                        class="form-control  {{ $errors->has('name') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $user->name }}" required>
                                </div>
                                @if ($errors->has('name'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('name') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="usernam" class=" form-control-label">Username </label>
                                </div>
                                <div>
                                    <input type="text" name="username" placeholder="username "
                                        class="form-control  {{ $errors->has('username') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $user->username }}" required>
                                </div>
                                @if ($errors->has('username'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('username') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="email" class=" form-control-label">Email</label>
                                </div>
                                <div>
                                    <input type="text" name="email" placeholder="email"
                                        class="form-control  {{ $errors->has('email') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $user->email }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('email') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group">
                                <div>
                                    <label for="passwordNew" class=" form-control-label">Password Baru</label>
                                </div>
                                <div>
                                    <input type="password" name="passwordNew" placeholder="password Baru"
                                        class="form-control  {{ $errors->has('passwordNew') ? 'form-control is-invalid' : 'form-control' }}">
                                </div>
                                @if ($errors->has('passwordNew'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('passwordNew') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="passwordConfrim" class=" form-control-label">Password Confrim</label>
                                </div>
                                <div>
                                    <input type="password" name="passwordConfrim" placeholder="Password Confrim"
                                        class="form-control  {{ $errors->has('passwordConfrim') ? 'form-control is-invalid' : 'form-control' }}">
                                </div>
                                @if ($errors->has('passwordConfrim'))
                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                        role="alert">
                                        {{ $errors->first('passwordConfrim') }}
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group ">
                                <label for="rule">Hak Akses </label>
                                <select name="rule" class="selected2 form-control" id="cmbrule">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }} "
                                            {{ $user->hasRole($role->slug) == 1 ? 'selected' : 'bebel' }}>
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('rule'))
                                    <span class=" text-danger">
                                        <strong id="textrule">Hak Akses salah</strong>
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
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        $(function() {
            $("#nama").keypress(function() {
                $("#nama").removeClass("is-invalid");
                $("#textNama").html("");
            });

            $('#cmbrule').select2({
                placeholder: '--- Pilih Jabatan ---',
                width: '100%'
            });
        });
    </script>
@endpush
