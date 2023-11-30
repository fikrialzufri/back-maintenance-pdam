@extends('template.app')
@section('title', 'Edit Manajemen Hak Akses ' . $title)

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
                            <div class="col-sm-6">

                                <div class="form-group">
                                    <div>
                                        <label for="nama" class=" form-control-label">Nama {{ $title }}</label>
                                    </div>
                                    <div>
                                        <input type="text" name="name" id="nama"
                                            placeholder="Nama {{ $title }}"
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
                                        <input type="text" name="description"
                                            placeholder="Description {{ ucfirst($route) }}" id="description"
                                            class="form-control  {{ $errors->has('description') ? 'form-control is-invalid' : 'form-control' }}"
                                            value="{{ $task->description }}" required>
                                    </div>
                                    @if ($errors->has('description'))
                                        <span class="text-danger">
                                            <strong id="textDescription">{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group ">
                                    <div>
                                        <label for="description" class=" form-control-label">Tambah Permission</label>
                                    </div>


                                    <div class="input-group input-group-button">
                                        <input type="text" name="permission" class="form-control"
                                            placeholder="Permission">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button"
                                                id="btnTambahPermission">Tambah</button>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="form-group">
                                <table class="table table-bordered table-striped" border='10'
                                    style=" text-align:center;">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center" style="vertical-align:middle">Tugas</th>
                                            <th scope="col" class="text-center" style="vertical-align:middle">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach ($task->permissions as $permission)
                                            <tr id="permission_{{ $permission->slug }}">
                                                <td class="{{ $task->name }}">
                                                    {{-- hiden --}}
                                                    <input type="hidden" name="permission[]"
                                                        value="{{ $permission->slug }}">
                                                    {{-- hiden name input --}}
                                                    <input type="hidden" name="permission_name[]"
                                                        value="{{ $permission->name }}">
                                                    {{-- hiden name input --}}
                                                    <div class=" hak{{ $task->name }}">
                                                        {{ $permission->name }}
                                                    </div>
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip"
                                                        data-placement="top" title="Hapus" type="button"
                                                        onclick=deleteconfpermision("permission_{{ $permission->slug }}")>
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        function deleteconfpermision(params) {
            // delete element
            $("#" + params).remove();

        }
        $(function() {
            $("#nama").keypress(function() {
                $("#nama").removeClass("is-invalid");
                $("#textNama").html("");
            });
            $("#description").keypress(function() {
                $("#description").removeClass("is-invalid");
                $("#textdescription").html("");
            });
            // btnTambahPermission on click
            $("#btnTambahPermission").click(function() {


                var permission = $("input[name='permission']").val();
                var slug = permission.replace(/\s+/g, '-').toLowerCase();
                var id_permission = 'permission_' + slug;
                // tambah element baru
                var html = '<tr id="' + id_permission + '">' +
                    '<td class="' + permission + '">' +
                    '<div class=" hak' + permission + '">' +
                    permission +
                    '<input type="hidden" name="permission[]" value="' + slug + '">' +
                    '<input type="hidden" name="permission_name[]" value="' + permission + '">' +
                    '</div>' +
                    '</td>' +
                    '<td>' +
                    '<button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Hapus" onclick=deleteconfpermision("' +
                    id_permission + '")>' +
                    '<i class="fa fa-trash"></i>' +
                    '</button>' +
                    '</td>' +
                    '</tr>';

                // tambah element baru
                $("table tbody").append(html);
                // clear input
                $("input[name='permission']").val("");
            });
        });
    </script>
@endpush
