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
                                @if ($index >= 2)
                                    <div id="shift-tampil">
                                        <label for="" class=" form-control-label">
                                            Denda
                                        </label>
                                        <div class="salestable ">
                                            <table class="table-responsive table table-head-fixed  table-bordered "
                                                id="tableProduk" style="height:400px">
                                                <thead>
                                                    <tr>
                                                        <th class=" text-center" width="2%">No.</th>
                                                        <th class="" width="25%">Nama Shift</th>
                                                        <th class="" width="20%">Denda</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body-table">
                                                    @forelse ($dataShift as $index => $item)
                                                        <tr>
                                                            <td class="text-center">{{ $index + 1 }}</td>
                                                            <td>{{ $item->name }}</td>
                                                            <td>
                                                                <div class="input-group mb-2 mr-sm-2">
                                                                    <div class="input-group-prepend">
                                                                        <div class="input-group-text">Rp.</div>
                                                                    </div>
                                                                    @if ($store == 'update')
                                                                        @if (isset($jabatanDenda))
                                                                            <input type="text"
                                                                                name="denda[{{ $item->id }}]"
                                                                                id="{{ $item->slug }}"
                                                                                @foreach ($jabatanDenda as $val) @if ($item->id == $val->id)
                                                                                    value="{{ format_uang($val->pivot->denda) }}" @endif
                                                                                @endforeach
                                                                            placeholder="Harga {{ $item->nama }}"
                                                                            class="form-control {{ $errors->has($item->id) ? 'is-invalid' : '' }}">
                                                                        @endif
                                                                    @else
                                                                        <input type="text"
                                                                            name="denda[{{ $item->id }}]"
                                                                            id="{{ $item->slug }}"
                                                                            value="{{ old('denda')[$item->id] ?? '' }}"
                                                                            placeholder="Denda {{ $item->nama }}"
                                                                            class="form-control {{ $errors->has($item->id) ? 'is-invalid' : '' }}">
                                                                    @endif
                                                                    <input type="hidden"
                                                                        name="shift_id[{{ $item->id }}]"
                                                                        value="{{ $item->id }}">
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="6">Data shift tidak ada</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="row">
                    <div class="col-md-12" id="button-card">

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
        <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
        <script>
            $('#diskon').bind("input keyup paste ", function() {
                let text = this.value.replace(/[^0-9]/g, '');
                let type_diskon = $('input[name="type_diskon"]:checked').val();
                value = $(this).val();
                if (type_diskon === 'persen') {
                    if ((value !== '') && (value.indexOf('.') === -1)) {
                        $(this).val(Math.max(Math.min(value, 100), -100));
                    }
                } else {
                    $('#diskon').val(formatRupiah(text.toString(), ' '));
                }
            });

            @foreach ($dataShift as $index => $item)
                $('#{{ $item->slug }}').on("input", function() {

                let val = formatRupiah(this.value, '')
                $('#{{ $item->slug }}').val(val)
                });
            @endforeach

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
        </script>
    @endpush
