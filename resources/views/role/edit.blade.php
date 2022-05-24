@extends('template.app')
@section('title', 'Edit Hak Akses ' . $role->name)
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }} {{ $role->name }}</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="card-body">

                            <div class="form-group">
                                <div>
                                    <label for="Name" class=" form-control-label">Nama</label>
                                </div>
                                <div>
                                    <input type="text" name="name" placeholder="Nama Hak Akses"
                                        class="form-control  {{ $errors->has('name') ? 'form-control is-invalid' : 'form-control' }}"
                                        value="{{ $role->name }}" required id="">
                                </div>
                                @if ($errors->has('name'))
                                    nama
                                    <span class="text-danger">
                                        <strong id="textkk">Mohon Maskan Nama dengan benar</strong>
                                    </span>
                                @endif
                            </div>
                            <div class="form-group">
                                <table class="table table-bordered table-striped" border='10' style=" text-align:center;">
                                    <thead>
                                        <tr>
                                            <th scope="col" rowspan="2" class="text-center"
                                                style="vertical-align:middle">Tugas</th>
                                            <th scope="col" colspan="5" class="text-center">Hak Akses</th>
                                        </tr>
                                        <tr>
                                            <th scope="col" class="text-center">

                                                Pilih Semua
                                            </th>
                                            <th scope="col" colspan="5" class="text-center">Modul</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tasks as $task)
                                            <tr>

                                                <td scope="row">{{ $task->description }}</td>
                                                <th scope="col" class="text-center">

                                                    <input type="checkbox" name="izin" value="{{ $task->slug }}"
                                                        class="checkAll checkAll{{ $task->slug }}" />
                                                </th>
                                                @foreach ($task->permissions as $permission)
                                                    <td class="{{ $task->slug }}">
                                                        <div class=" hak{{ $task->slug }}">
                                                            <input type="checkbox" name="izin_akses[]"
                                                                value="{{ $permission->id }}"
                                                                class="check{{ $task->slug }} hakakses"
                                                                id="{{ $permission->slug }}"
                                                                {{ in_array($permission->name, $izin) ? 'checked' : '' }} />
                                                            {{ $permission->slug }}
                                                        </div>
                                                    </td>
                                                @endforeach
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

    @stop

    @push('script')
        <script>
            $(function() {
                $(".checkAll").on('change', function() {
                    if ($(this).is(':checked')) {
                        $(".check" + this.value).prop('checked', true);
                    } else {
                        $(".check" + this.value).prop('checked', false);
                    }
                });
                $(".hakakses").on('click', function() {
                    var header = $(this).attr('class');
                    var classParent = header.replace(" hakakses", "");

                    var countChecked = $('.' + classParent + ':checked').length;

                    var parentClass = $(this).closest('td').attr('class');

                    if (countChecked == 4) {
                        $(".checkAll" + parentClass).prop('checked', true);
                    } else {
                        $(".checkAll" + parentClass).prop('checked', false);
                    }
                });

                var arrayClassParent = $(".hakakses")
                    .map(function() {
                        var header = $(this).attr('class');
                        return header.replace(" hakakses", "");

                    }).toArray();
                // var classParentEdit = headerEdit.replace(" hakakses", "");

                // var countCheckedEdit = $('.' + classParentEdit + ':checked').length;
                var uniqueNames = [];
                $.each(arrayClassParent, function(i, el) {
                    if ($.inArray(el, uniqueNames) === -1) uniqueNames.push(el);
                });

                $.each(uniqueNames, function(index, value) {
                    var countChecked = $('.' + value + ':checked').length;

                    var parentClass = $('.' + value).closest('td').attr('class');
                    if (countChecked == 4) {

                        $(".checkAll" + parentClass).prop('checked', true);
                    }
                });
            });
        </script>
    @endpush
