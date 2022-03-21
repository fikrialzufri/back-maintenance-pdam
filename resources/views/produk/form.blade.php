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
                                    <div class="form-group form-textinput">
                                        <div class="form-group form-textinput">

                                            <div>
                                                <label for="" class=" form-control-label">
                                                    Harga Jual
                                                </label>

                                                <table class="table table-bordered " id="example">
                                                    <thead>
                                                        <tr>
                                                            <th>Nama Kategori Harga</th>
                                                            <th>Harga Jual</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($dataKategoriHargaJual as $index => $item)
                                                            <tr>
                                                                <td>{{ $item->nama }}</td>
                                                                <td>
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">Rp.</div>
                                                                        </div>
                                                                        @if ($store == 'update')
                                                                            @if (isset($KategoriHargaJual))
                                                                                <input type="text"
                                                                                    name="kategori_harga[{{ $item->id }}]"
                                                                                    id="{{ $item->slug }}"
                                                                                    @foreach ($KategoriHargaJual as $val) @if ($item->id == $val->pivot->kategori_harga_jual_id)
                                                                                    value="{{ format_uang($val->pivot->harga_jual) }}" @endif
                                                                                    @endforeach
                                                                                placeholder="Harga {{ $item->nama }}"
                                                                                class="form-control {{ $errors->has($item->id) ? 'is-invalid' : '' }}">
                                                                            @endif
                                                                        @else
                                                                            <input type="text"
                                                                                name="kategori_harga[{{ $item->id }}]"
                                                                                id="{{ $item->slug }}"
                                                                                value="{{ old('kategori_harga')[$item->id] ?? '' }}"
                                                                                placeholder="Harga {{ $item->nama }}"
                                                                                class="form-control {{ $errors->has($item->id) ? 'is-invalid' : '' }}">
                                                                        @endif
                                                                        <input type="hidden"
                                                                            name="id_kategori_harga[{{ $item->id }}]"
                                                                            value="{{ $item->id }}">
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="row" id="produk-tampil">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <select class="form-control select2" id="cmbProduk">
                                            <option selected="selected" value="">Pilih Produk
                                            </option>
                                            @foreach ($dataProduk as $i => $item)
                                                <option value="{{ $item->id }}"
                                                    data-select2-id="produk_{{ $item->id }}"
                                                    id="produk_{{ $item->id }}">
                                                    {{ $item->nama }}
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
                                                    <th class="" width="20%">Qty</th>
                                                    <th class="" width="13%">Satuan</th>
                                                    <th class=" text-center" width="5%">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="body-table">
                                                @if ($store == 'update')
                                                    @isset($data)
                                                        @foreach ($data->hasBahan()->get() as $index => $item)
                                                            <tr id="item_{{ $item->id }}" class="list-produk">
                                                                <td class=" text-center" width="5%">{{ $index + 1 }}</td>
                                                                <td>{{ $item->nama }}
                                                                    <input type="hidden" class="iditem" name="iditem[]"
                                                                        id="iditem{{ $item->id }}"
                                                                        value="{{ $item->id }}" />
                                                                </td>
                                                                <td>
                                                                    <div class="input-group input-group-xs ">
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-success" type="button"
                                                                                onclick="kurangjumlah('{{ $item->id }}')">
                                                                                <i class="glyphicon glyphicon-plus">-</i>
                                                                            </button>
                                                                        </span>
                                                                        <input type="text"
                                                                            class="form-control inputqty text-right"
                                                                            id="jumlah_item_{{ $item->id }}" name="qty[]"
                                                                            value="{{ $item->pivot->qty }}" readonly />
                                                                        <span class="input-group-btn">
                                                                            <button class="btn btn-danger" type="button"
                                                                                onclick="tambahjumlah('{{ $item->id }}')">
                                                                                <i class="glyphicon glyphicon-minus">+</i>
                                                                            </button>
                                                                        </span>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $item->satuan }}</td>
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
                                                    <tr id="itemtidakada">
                                                        <td colspan="4">Belum ada produk</td>
                                                    </tr>
                                                @endif
                                                {{-- <tr>
                                                        <th class=" text-center" width="5%">2</th>
                                                        <th class="" width="25%">Kopi </th>
                                                        <th class="" width="13%">Rp. 1000</th>
                                                        <th class=" text-center" width="5%">Action</th>
                                                    </tr> --}}
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
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
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        let rupiah = document.getElementsByClassName('rupiah');
        @foreach ($dataKategoriHargaJual as $index => $item)
            $('#{{ $item->slug }}').on("input", function() {

            let val = formatRupiah(this.value, '')
            $('#{{ $item->slug }}').val(val)
            });
        @endforeach

        /* Fungsi   */
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

        $('#cmbProduk').select2({
            width: '100%'
        });

        var inc_bahan = $("input[name='inc_bahan']:checked").val();
        if (inc_bahan === 'ya') {
            $('#produk-tampil').show();
        } else {
            $('.list-produk').remove();
            $('#produk-tampil').hide();
        }

        $('input[name="inc_bahan"]').click(function() {
            var inc_bahan = $("input[name='inc_bahan']:checked").val();

            if (inc_bahan === 'ya') {
                $('#produk-tampil').show();
            } else {
                $('.list-produk').remove();
                $('#produk-tampil').hide();
            }
        });
        $('input[name="inc_bahan"]').click(function() {
            var inc_bahan = $("input[name='inc_bahan']:checked").val();

            if (inc_bahan === 'ya') {
                $('#produk-tampil').show();
            } else {
                $('.list-produk').remove();
                $('#produk-tampil').hide();
            }
        });

        function tambahjumlah(id) {
            let jumlah = parseInt($('#jumlah_item_' + id).val());
            $('#jumlah_item_' + id).val(parseInt(jumlah) + 1);
        }

        function kurangjumlah(id) {
            let jumlah = parseInt($('#jumlah_item_' + id).val());
            if (jumlah >= 2) {
                $('#jumlah_item_' + id).val(parseInt(jumlah) - 1);
            }
        }

        function hapusitem(id) {
            let item = $('#item_' + id).length;
            console.log(item);
            if (item > 0) {
                $('#item_' + id).remove();
            }

            $('.nomor_item').each(function(index, item) {
                console.log(index);
                if (parseInt($(item).data('index')) > 2) {
                    $(item).text(index + 1);
                }
            });

        }

        function table(id, nama, jumlah, satuan, nomor = null) {
            const tombolAction = `<td class="text-center">
                                <button type="button" class="btn btn-warning btn-xs text-center" onclick="hapusitem('${id}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>`;

            const tombolQty = `<div class="input-group input-group-xs ">
                                    <span class="input-group-btn">
                                        <button class="btn btn-success" type="button" onclick="kurangjumlah('${id}')">
                                            <i class="glyphicon glyphicon-plus" >-</i>
                                        </button>
                                    </span>
                                    <input type="text" class="form-control inputqty text-right"  id="jumlah_item_${id}" name="qty[]" value="${jumlah}" readonly />
                                    <span class="input-group-btn">
                                        <button class="btn btn-danger" type="button"  onclick="tambahjumlah('${id}')">
                                            <i class="glyphicon glyphicon-minus">+</i>
                                        </button>
                                    </span>
                                </div>`;


            let content = `<tr id="item_${id}" class="list-produk">
                            <td  class="text-center nomor_item" data-index="${nomor}">
                                ${nomor}
                            </td>
                            <td>${nama}
                            <input type="hidden" class="iditem" name="iditem[]" id="iditem${id}" value="${id}"/>
                            </td>
                            <td>
                                ${tombolQty}
                            </td>
                            <td>
                                ${satuan}
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
                        satuan,
                    } = data.data;

                    //check diskon kosong
                    $('#itemtidakada').remove();
                    let nomorTeakhir = $('#tableProduk > tbody > tr').length;
                    let item = $('#item_' + id).length;
                    let iditem = $('#iditem_no_karyawan' + id).length;
                    let nomor = parseInt(nomorTeakhir) + 1;
                    let jumlah = 1;
                    if (item === 0) {
                        // let tableTampil = table(id, nama, diskon, diskon_tampil, nomor);
                        // $('#tableProduk').append(tableTampil);
                        diskon = $('#diskon').val();
                        let tableTampil = table(id, nama, jumlah, satuan, nomor);
                        $('#tableProduk').append(tableTampil);
                    }
                }
            });
        });
    </script>
@endpush
