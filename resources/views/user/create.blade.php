@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
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
                        <div class="card-body">
                            <div class="form-group">
                                <div>
                                    <label for="usernam" class=" form-control-label">Username </label>
                                </div>
                                <div>
                                    <input type="text" name="username" placeholder="username (NIK)"
                                        class="form-control  {{ $errors->has('username') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ old('username') }}" required>
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
                                    <label for="Name" class=" form-control-label">Name</label>
                                </div>
                                <div>
                                    <input type="text" name="name" placeholder="Name User"
                                        class="form-control  {{ $errors->has('name') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ old('name') }}" required>
                                </div>
                                @if ($errors->has('name'))
                                    <span class="text-danger">
                                        <strong id="textkk">{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="email" class=" form-control-label">Email</label>
                                </div>
                                <div>
                                    <input type="email" name="email" placeholder="Masukan Email"
                                        class="form-control  {{ $errors->has('email') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ old('email') }}" required>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="text-danger">
                                        <strong id="textemail">{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="password" class=" form-control-label">Password</label>
                                </div>
                                <div>
                                    <input type="password" name="password" placeholder="Password"
                                        class="form-control  {{ $errors->has('password') ? 'form-control is-invalid' : 'form-control' }}"
                                        required>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="text-danger">
                                        <strong id="textpassword">{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <div>
                                    <label for="passwordConfrim" class=" form-control-label">Konfirmasi Password</label>
                                </div>
                                <div>
                                    <input type="password" name="passwordConfrim" placeholder="Password Confrim"
                                        class="form-control  {{ $errors->has('passwordConfrim') ? 'form-control is-invalid' : 'form-control' }}"
                                        required>
                                </div>
                                @if ($errors->has('passwordConfrim'))
                                    <span class="text-danger">
                                        <strong id="textpasswordConfrim">{{ $errors->first('passwordConfrim') }}</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group ">
                                <label for="rule">Hak Akses</label>
                                <select name="rule" class="selected2 form-control" id="cmbrule" required>
                                    <option value="">--Pilih Hak Akses--</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('rule') == $role->id ? 'selected' : '' }}>{{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('rule'))
                                    <span class="text-danger">
                                        <strong id="textrule">{{ $errors->first('rule') }}</strong>
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
            $("#email").keypress(function() {
                $("#email").removeClass("is-invalid");
                $("#textemail").html("");
            });
            $("#password").keypress(function() {
                $("#password").removeClass("is-invalid");
                $("#textpassword").html("");
            });
            $("#password").keypress(function() {
                $("#password").removeClass("is-invalid");
                $("#textpassword").html("");
            });

            // $("#id_rt").keypress(function(){
            //   $("#id_rt").removeClass("is-invalid");
            //   $("#textid_rt").html("");
            // });
            $('#cmbrule').select2({
                placeholder: '--- Pilih Hak Akses ---',
                width: '100%'
            });

        });
    </script>
@endpush
