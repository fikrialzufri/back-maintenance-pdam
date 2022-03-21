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
                                    <div id="produk-tampil">

                                        <div class="form-group">
                                            <select class="form-control select2" id="cmbProduk">
                                                <option selected="selected" value="">Pilih Produk
                                                </option>
                                                @foreach ($dataProduk as $i => $item)
                                                    <option value="{{ $item->id }}"
                                                        data-select2-id="produk_{{ $item->id }}"
                                                        id="produk_{{ $item->id }}">
                                                        {{ $item->nama }} | Jenis : {{ $item->jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="salestable ">
                                            <table class="table-responsive table table-head-fixed  table-bordered "
                                                id="tableProduk" style="height:400px">
                                                <thead>
                                                    <tr>
                                                        <th class=" text-center" width="5%">No.</th>
                                                        <th class="" width="25%">Produk</th>
                                                        <th class="" width="13%">Diskon</th>
                                                        <th class=" text-center" width="5%">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="body-table">
                                                    @if ($store == 'update')
                                                        @isset($data)
                                                            @foreach ($data->hasProduk()->get() as $index => $item)
                                                                <tr id="item_{{ $item->id }}" class="list-produk">
                                                                    <td class=" text-center" width="5%" class="nomor_item"
                                                                        data-index="{{ $index + 1 }}">
                                                                        {{ $index + 1 }}</td>
                                                                    <td>{{ $item->nama }}
                                                                        <input type="hidden" class="iditem"
                                                                            name="iditem[]" id="iditem{{ $item->id }}"
                                                                            value="{{ $item->id }}" />
                                                                    </td>
                                                                    <td>
                                                                        <span id="diskon_tampil_{{ $item->id }}">
                                                                            @if ($item->pivot->type_diskon == 'persen')
                                                                                {{ $item->pivot->diskon }} %
                                                                            @else
                                                                                Rp.
                                                                                {{ format_uang($item->pivot->diskon) }}
                                                                            @endif
                                                                        </span>
                                                                        <input type="hidden" class="diskon_produk"
                                                                            name="diskon_produk[]"
                                                                            id="diskon_produk_{{ $item->id }}"
                                                                            value="{{ $item->pivot->diskon }}" />
                                                                        <input type="hidden" class="type_diskon_produk"
                                                                            name="type_diskon_produk[]"
                                                                            id="type_diskon_produk_{{ $item->id }}"
                                                                            value="{{ $item->pivot->type_diskon }}" />
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <button type="button"
                                                                            class="btn btn-warning btn-xs text-center"
                                                                            onclick="hapusitem({{ $item->id }})">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @endisset
                                                    @else
                                                    @endif
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
        $('#diskon').prop("readonly", true);

        $("#produk-tampil").hide();
        $('#cmbProduk').select2({
            width: '100%'
        });
        let content = '';
        var produk = $("input[name='produk']:checked").val();

        if (produk === 'produk') {
            $("#produk-tampil").show();
            @if ($store != 'update')
                $('#itemtidakada').remove();
                $('.list-produk').remove();
                // $('#button-card').attr('class', 'col-md-12')
            @endif
        } else {
            $('#itemtidakada').remove();
            $('.list-produk').remove();
            $("#produk-tampil").hide();
            // $('#button-card').attr('class', 'col-md-6')
        }
        var type_diskon = $("input[name='type_diskon']:checked").val();

        if (type_diskon === 'persen') {
            $('#diskon').prop("type", "number");
            $('#diskon').attr('max', '100');
            $('#diskon').attr('maxlength', '100');
            $('#diskon').prop("readonly", false);
        }

        if (type_diskon === 'produk') {
            $('#diskon').prop("type", "text");
            $('#diskon').prop("readonly", false);
        }
        if (type_diskon) {
            $('#diskon').prop("type", "text");
            $('#diskon').prop("readonly", false);
        }

        $('input[name="type_diskon"]').click(function() {
            $('#diskon').prop("readonly", false);
            var type_diskon = $("input[name='type_diskon']:checked").val();

            if (type_diskon === 'persen') {
                $('#diskon').prop("type", "number");
                $('#diskon').attr('max', '100');
                $('#diskon').attr('maxlength', '100');
            } else {
                $('#diskon').prop("type", "text");
                let diskon = $('input[name="diskon"]').val();
                $('input[name="diskon"]').val(1);
            }
            $('#diskon').val("");
        });

        // check radio produk
        $('input[name="produk"]').click(function() {
            let content = '';
            var produk = $("input[name='produk']:checked").val();
            $('#itemtidakada').remove();
            $('.list-produk').remove();
            if (produk === 'produk') {
                $("#produk-tampil").show();
                // $('#button-card').attr('class', 'col-md-12')
            } else {
                $("#produk-tampil").hide();
                // $('#button-card').attr('class', 'col-md-6')
            }
        });
        var jenis_diskon = $("input[name='jenis_diskon']:checked").val();
        if (jenis_diskon === 'produk') {
            $("#form_mulai").show();
            $("#form_selesai").show();
            $("#form_nota").hide();
            $('input[name="nota"]').val(0)
        } else {
            $("#form_nota").show();
            $("#form_mulai").hide();
            $("#form_selesai").hide();
        }
        $('input[name="jenis_diskon"]').click(function() {

            var jenis_diskon = $("input[name='jenis_diskon']:checked").val();
            if (jenis_diskon === 'produk') {
                $("#form_mulai").show();
                $("#form_selesai").show();
                $("#form_nota").hide();
                $('input[name="nota"]').val(0)
            } else {
                $("#form_nota").show();
                $("#form_mulai").hide();
                $("#form_selesai").hide();
            }
        });

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

        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            let number_string = angka.replace(/[^,\d]/g, '').toString(),
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
            return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
        }

        function hapusitem(id) {
            let content = '';
            let item = $('#item_' + id).length;
            if (item > 0) {
                $('#item_' + id).remove();

                $('#tableProduk').append(content);
            }
            $('.nomor_item').each(function(index, item) {
                console.log(index);
                if (parseInt($(item).data('index')) > 2) {
                    $(item).text(index + 1);
                }
            });
        }

        function itemEmpty() {
            return `<tr id="itemtidakada"><td colspan="3">Belum ada produk</td></tr>`;
        }

        function table(id, nama, diskon, diskon_tampil, nomor = null) {
            const tombolAction = `<td class="text-center">
                                <button type="button" class="btn btn-warning btn-xs text-center" onclick="hapusitem('${id}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>`;

            let content = `<tr id="item_${id}" class="list-produk">
                            <td   class="text-center nomor_item" data-index="${nomor}">
                                ${nomor}
                            </td>
                            <td>${nama}
                            <input type="hidden" class="iditem" name="iditem[]" id="iditem${id}" value="${id}"/>
                            </td>
                            <td>
                                <span id="diskon_tampil_${id}">${diskon_tampil}</span>
                            <input type="hidden" class="diskon_produk" name="diskon_produk[]" id="diskon_produk_${id}" value="${diskon}"/>
                            <input type="hidden" class="type_diskon_produk" name="type_diskon_produk[]" id="type_diskon_produk_${id}" value="${diskon}"/>
                            </td>
                            ${tombolAction}

                            </tr>`;
            return content;
        }

        $("#cmbProduk").on("change", function(e) {
            let selected = [];
            selected = $(e.currentTarget).val();
            let content = '';
            let contentPembayaran = '';

            $("#cmbProduk option[value=" + selected + "]").prop("selected", false);
            $.ajax({
                type: 'get',
                url: "/produk/" + selected,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {

                    const {
                        id,
                        nama,
                    } = data.data;
                    let type_diskon = $('input[name="type_diskon"]:checked').val();
                    let diskon = $('#diskon').val();
                    let diskon_tampil = diskon;

                    //check diskon kosong
                    if (!diskon.trim()) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: "Diskon belum diisi",
                        })
                    } else {
                        $('#itemtidakada').remove();
                        let nomorTeakhir = $('#tableProduk > tbody > tr').length;
                        let item = $('#item_' + id).length;
                        let iditem = $('#iditem_no_karyawan' + id).length;
                        let nomor = parseInt(nomorTeakhir) + 1;

                        if (type_diskon === 'persen') {
                            diskon = $('#diskon').val();
                            diskon_tampil = diskon_tampil + ' %';
                        } else {
                            diskon = diskon.replace(/Rp/g, "");
                            diskon = diskon.replace(" ", "");
                            diskon = diskon.split('.').join('');
                            diskon_tampil = diskon_tampil;

                        }
                        if (item === 0) {
                            // let tableTampil = table(id, nama, diskon, diskon_tampil, nomor);
                            // $('#tableProduk').append(tableTampil);
                            diskon = $('#diskon').val();
                            let tableTampil = table(id, nama, diskon, diskon_tampil, nomor);
                            $('#tableProduk').append(tableTampil);
                            $("#diskon_produk_" + id).val(diskon);
                            $("#type_diskon_produk_" + id).val(type_diskon);
                        } else {
                            $("#diskon_tampil_" + id).text(diskon_tampil);
                            $("#diskon_produk_" + id).val(diskon);
                            $("#type_diskon_produk_" + id).val(type_diskon);
                        }

                    }

                }
            });
        });
    </script>
@endpush
