@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">

                    <div class="col-md-3 pr-0">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-10 pr-0">
                                            <label>Pelanggan</label>

                                            <select class="form-control select2" id="listpelanggan">
                                                <option selected="selected" value="">Pilih Pelanggan
                                                </option>
                                                @foreach ($dataPelanggan as $pelanggan)
                                                    <option value="{{ $pelanggan->id }}"
                                                        id="pelanggan_{{ $pelanggan->id }}">
                                                        {{ ucwords($pelanggan->nama) }} | No HP :
                                                        {{ $pelanggan->no_hp }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-sm-2 pl-1 pt-1">
                                            <button type="button" class="mt-4 btn btn-sm btn-primary" data-toggle="modal"
                                                data-target="#CustomerAdd">+</button>
                                        </div>

                                    </div>

                                </div>
                                <div class="form-group">
                                    <label>No Nota</label>
                                    <input type="text" class="form-control" placeholder="is No Nota" id="no_nota"
                                        name="no_nota" value="{{ $noNota }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Tanggal</label>
                                    <input type="text" class="form-control" placeholder="Tanggal" id="tanggal"
                                        name="tanggal" value="{{ $today }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Diskon</label>
                                    <div class="form-radio">
                                        <div class="radio radiofill radio-inline">
                                            <label>
                                                <input type="radio" name="type_diskon" class="type_diskon"
                                                    value="persen">
                                                <i class="helper"></i> Persen
                                            </label>
                                        </div>
                                        <div class="radio radiofill radio-inline">
                                            <label>
                                                <input type="radio" name="type_diskon" class="type_diskon"
                                                    value="Nominal" checked>
                                                <i class="helper"></i> Nominal
                                            </label>
                                        </div>
                                    </div>
                                    <input type="text" name="diskon" class="form-control" id="diskon_input"
                                        placeholder="Isi Diskon" value="">
                                </div>
                                <div class="form-group">
                                    <label>Catatan</label>
                                    <textarea class="form-control h-123" name="catatan" placeholder="Isi Catatan" id="catatan">Kepada yang terhormat Bapak/Ibu , terimakasih telah melakukan transaksi di {{ $nama_toko }}, berikut nota transaksi Bapak/Ibu 🙏
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Toko</label>
                                    <input type="text" id="nama_toko" class="form-control" value="{{ $nama_toko }}"
                                        readonly>
                                    <input type="hidden" id="no_hp_toko" class="form-control" value="{{ $no_hp }}">
                                    <textarea style="display: none" name="alamat" id="alamat">{{ $alamat }}
                                    </textarea>
                                </div>
                                <div class="form-group">
                                    <label>Kasir</label>
                                    <input type="text" class="form-control" value="{{ $nama_karyawan }}" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Promosi</label>
                                    <input type="text" class="form-control" placeholder="" name="Promosi" readonly
                                        value="{{ $namaPromosi }}" id="promosi_tampil">
                                    <input type="hidden" placeholder="" name="promosi_today" value="{{ $idPromosi }}"
                                        id="promosi_today">
                                    <input type="hidden" placeholder="" name="promosi_pelanggan" id="promosi_pelanggan"
                                        value="">
                                </div>
                                <div class="form-group">
                                    <label>Syarat dan ketentuan Promosi</label>
                                    <textarea name="keterangan" id="keterangan_promosi" readonly class="form-control">{{ $keterangan }}
                                    </textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card mb-0">
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
                                                        {{ $item->nama }} | Jenis : {{ $item->jenis }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="salestable ">
                                    <table class="table-responsive table table-head-fixed  table-bordered " id="tableProduk"
                                        style="height:400px">
                                        <thead>
                                            <tr>
                                                <th class=" text-center" width="5%">No.</th>
                                                <th class="" width="25%">Produk</th>
                                                <th class="" width="12%">Harga</th>
                                                <th class="" width="13%">Diskon</th>
                                                <th class=" text-center" width="18%">Qty</th>
                                                <th class="" width="13%">Sub Total</th>
                                                <th class="" width="20%">Karyawan</th>
                                                <th class=" text-center" width="5%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="itemtidakada">
                                                <td colspan="8">Belum ada daftar belanja</td>
                                            </tr>

                                        </tbody>

                                    </table>
                                </div>
                                <div class="salestable">
                                    <table class="table table-head-fixed table-bordered table-hover">
                                        <tbody>

                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="border-1" colspan="4" width="70%"></th>
                                                <th>Total Qty</th>
                                                <th class="text-right" id="totalqtytampil">0</th>
                                            </tr>
                                            <tr>
                                                <th class="border-1" colspan="4"></th>
                                                <th>Total Harga</th>
                                                <th class="text-right">
                                                    <span id="totalbayartampil">
                                                        Rp. 0
                                                    </span>
                                                    <input type="hidden" name="total" id="totalbayar">
                                                </th>
                                            </tr>
                                            <tr>
                                                <td class="border-1" colspan="4"></td>
                                                <td>Discount</td>
                                                <td class="text-right">
                                                    <span id="diskon_tampil">
                                                        Rp. 0
                                                    </span>
                                                    <input type="hidden" name="diskon" id="diskon">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="border-1" colspan="4"></th>
                                                <th>Grand Total</th>
                                                <th class="text-right">
                                                    <span id="grandtotalbayartampil">
                                                        Rp. 0
                                                    </span>
                                                    <input type="hidden" name="grandtotal" id="grandtotalbayar">
                                                </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class=""></div>
                                    <div class="">
                                        <button type="button" disabled class="btn btn-primary"
                                            id="notaPembayaran">Simpan</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
    <div class="modal fade edit-layout-modal pr-0 " id="CustomerAdd" role="dialog" aria-labelledby="CustomerAddLabel"
        aria-hidden="true">
        <div class="modal-dialog w-150" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="CustomerAddLabel">Add Pelanggan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form id="formPelanggan">
                        <div class="form-group">
                            <label class="d-block">Nama Pelanggan</label>
                            <input type="text" name="nama" id="nama" class="form-control" placeholder="isi Nama Pelanggan"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="d-block">No HP</label>
                            <input type="number" name="no_hp" id="no_hp" class="form-control" placeholder="isi No HP"
                                required>
                        </div>
                        <div class="form-group">
                            <label class="d-block">Email</label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="isi Email">
                        </div>
                        <div class="form-group">
                            <label class="d-block">Alamat</label>
                            <textarea name="alamat" id="alamat" cols="30" rows="10" class="form-control"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary" id="submintPelanggan" type="submit" name="Tambah Pelanggan">
                                Tambah Pelanggan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- modal pembayaran --}}
    <div class="modal fade" id="addPembayaran" role="dialog" aria-labelledby="addPembayaranLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg mt-0 mb-0" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPembayaranLabel">Nota Pembayaran | Kasir : {{ $nama_karyawan }}
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                </div>
                <form id="formPembayaran"
                    @if ($store == 'update') action="{{ route($route . '.' . $store, $data->id) }}" @else action="{{ route($route . '.' . $store) }}" @endif
                    method="post" role="form" enctype="multipart/form-data">
                    @if ($store == 'update')
                        {{ method_field('PUT') }}
                    @endif
                    @csrf

                    <div class="modal-body">
                        <table class="table-responsive table table-head-fixed  table-bordered " id="tableProdukPembayaran"
                            style="height:400px">
                            <thead>
                                <tr>
                                    <th class="" width="5%">No.</th>
                                    <th class="" width="30%">Produk</th>
                                    <th class="" width="15%">Harga</th>
                                    <th class="" width="15%">Diskon</th>
                                    <th class=" text-center" width="4%">Qty</th>
                                    <th class="" width="15%">Sub Total</th>
                                    <th class="" width="20%">Karyawan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="itemtidakadaPembayaran">
                                    <td colspan="6">Belum ada daftar belanja</td>
                                </tr>

                            </tbody>

                        </table>
                        <div class="salestable">
                            <table class="table table-head-fixed table-bordered table-hover">
                                <tbody>

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="border-1" colspan="4" width="50%">
                                            <select class="form-control select2" id="listMetode">
                                                <option selected="selected" value="">Pilih Metode
                                                </option>
                                                @foreach ($dataMetodePembayaran as $metode)
                                                    <option value="{{ $metode->id }}" id="metode_{{ $metode->id }}">
                                                        {{ ucwords($metode->nama) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </th>
                                        <th class="align-middle text-left">Total Qty</th>
                                        <th class="align-middle text-right" id="totalqtytampilPembayaran">0</th>
                                    </tr>
                                    <tr>
                                        <th class="border-1" colspan="4">
                                            <select class="form-control select2" id="listbank" name="bank_id">
                                                <option selected="selected" value="">Pilih Bank
                                                </option>
                                            </select>
                                        </th>
                                        <th class="align-middle text-left">Total Harga</th>
                                        <th class="text-right align-middle text-right">
                                            <span id="totalbayartampilPembayaran">
                                                Rp. 0
                                            </span>
                                            <input type="hidden" name="totalbayarPembayaran" id="totalbayarPembayaran">
                                            <input type="hidden" name="nota_pembyaran" id="nota_pembyaran"
                                                value="{{ $noNota }}">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border-1" colspan="4">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">Uang Bayar
                                                    </div>
                                                </div>
                                                <input type="text" name="uang_bayar_tampil" id="uang_bayar_tampil"
                                                    placeholder="" class="form-control" readonly value="">
                                                <input type="hidden" name="uang_bayar" id="uang_bayar">
                                            </div>
                                        </th>
                                        <th class="align-middle">Discount</th>
                                        <th class="align-middle text-right" id="">
                                            <span id="diskonPembayaranTampil">
                                                Rp. 0
                                            </span>
                                            <input type="hidden" name="diskonPembayaran" id="diskonPembayaran" value="">
                                        </th>
                                    </tr>
                                    <tr>
                                        <td class="border-1" colspan="4">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" id="no_ref_tampil">No Ref
                                                    </div>
                                                </div>
                                                <input type="text" name="no_ref" id="no_ref" placeholder=""
                                                    class="form-control" readonly value="">
                                            </div>
                                        </td>
                                        <th class="align-middle">Grand Total</th>
                                        <th class="text-right align-middle">
                                            <span id="grandtotalbayartampilPembayaran">
                                                Rp. 0
                                            </span>
                                            <input type="hidden" name="grandtotal" id="grandtotalbayarPembayaran">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border-1" colspan="4">
                                            <div class="input-group mb-2 mr-sm-2">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text" id="uang_angsul_label">Uang Angsul
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control" id="uang_angsul_tampil" readonly
                                                    value="">
                                                <input type="hidden" name="uang_angsul" id="uang_angsul" value="">
                                            </div>
                                        </th>
                                        <th class="align-middle">Pelanggan</th>
                                        <th class="text-right align-middle">
                                            <span id="pelangganTampilPembayaran">

                                            </span>
                                            <input type="hidden" name="id_pelanggan" id="pelangganPembayaran">
                                            <input type="hidden" name="no_hp_pembayaran" id="no_hp_pembayaran">
                                            <input type="hidden" name="toko_id" id="toko_id" class="form-control"
                                                value="{{ $toko_id }}">
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="border-1" colspan="4">
                                            Catatan :
                                            <span id="catatan_pembayaran_tampil">

                                            </span>
                                            <input type="hidden" name="catatan_pembayaran" id="catatan_pembayaran" value="">
                                        </th>

                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between">
                            <div class=""></div>
                            <div class="">
                                <button type="button" disabled class="btn btn-primary" id="btn_pembayaran">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script script src="{{ asset('plugins/select2/dist/js/select2.min.js') }}"> </script>
    <script>
        $('.select2').select2({
            width: '100%'
        });
        $('#listpelanggan').select2({
            width: '100%'
        });
        $('#btn_pembayaran').prop("disabled", true);
        $('#formPelanggan').on('submit', function(e) {
            e.preventDefault();

            let nama = $('#nama').val();
            let email = $('#email').val();
            let no_hp = $('#no_hp').val();
            let alamat = $('#alamat').val();

            $.ajax({
                type: 'POST',
                url: '/simpanpelanggan',
                data: {
                    "_token": "{{ csrf_token() }}",
                    nama: nama,
                    email: email,
                    no_hp: no_hp,
                    alamat: alamat,
                },
                success: function(data) {
                    const {
                        id,
                        nama,
                        no_hp
                    } = data.data;
                    $('#pelangganTampilPembayaran').text(nama);
                    $('#pelangganPembayaran').val(id);

                    $('#nama').val('');
                    $('#email').val('');
                    $('#no_hp').val('');
                    $('#alamat').val('');
                    $('#CustomerAdd').modal('hide');
                    $('#listpelanggan').append($('<option>', {
                        value: id,
                        text: `${nama} | No HP : ${no_hp}`
                    }));
                    $("#listpelanggan option[value=" + id + "]").prop("selected", true);
                    $('#notaPembayaran').prop("disabled", false);

                    // promosi
                    let promositoday = $('#promosi_today').val();
                    $('#no_hp_pembayaran').val(no_hp);
                    $('#promosi_tampil').val(promositoday);
                    $('#promosi_pelanggan').val(promositoday);
                },
                error: function(data) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: "No Hp sudah ada",
                        footer: '<a href="">Why do I have this issue?</a>'
                    })
                }
            });
        });

        let daftarItem = [];

        function Item(id, nama, jumlah, harga, jenis, karyawan, nomor, item_id, diskon_produk, type_diskon_promosi) {
            this.id = id;
            this.nama = nama;
            this.jumlah = jumlah;
            this.harga = harga;
            this.jenis = jenis;
            this.karyawan = karyawan;
            this.nomor = nomor;
            this.item_id = item_id;
            this.diskon_produk = diskon_produk;
            this.type_diskon_promosi = type_diskon_promosi;
        }

        function setObject(key, obj) {
            localStorage.setItem(key, JSON.stringify(obj));
        }

        function getObject(key) {
            let storage = localStorage.getItem(key);
            daftarItem = JSON.parse(storage);

            if (!daftarItem) {
                daftarItem = [];
            }
            return daftarItem;
        }

        function removeLocalStorage(id) {
            let index = daftarItem.findIndex(function(o) {
                return o.item_id === id;
            })
            if (index !== -1) daftarItem.splice(index, 1);

            localStorage.setItem('daftar-item', JSON.stringify(daftarItem));
        }


        function addItemLocalStorage(id, tambah = false, nama = null, jumlah = null, harga = null, jenis = null, karyawan =
            null, nomor = null, item_id = null, diskon_produk = null, type_diskon_promosi = null) {

            const storage = getObject('daftar-item');
            if (storage != null) {
                let newItem = true;
                for (let i = 0; i < daftarItem.length; i++) {
                    if (karyawan !== 'ya') {
                        if (item_id === daftarItem[i].item_id) {
                            //sama id
                            if (tambah != false) {
                                daftarItem[i].jumlah += 1; //tambah 1
                            } else {
                                daftarItem[i].jumlah -= 1;
                            }
                            newItem = false;
                            break; //exit
                        }
                    }
                }
                if (newItem) {
                    const item = new Item(id, nama, jumlah, harga, jenis, karyawan, nomor, item_id, diskon_produk,
                        type_diskon_promosi);
                    daftarItem.push(item);

                }
                localStorage.setItem('daftar-item', JSON.stringify(daftarItem));
            } else {
                const item = new Item(id, nama, jumlah, harga, jenis, karyawan, nomor, item_id, diskon_produk,
                    type_diskon_promosi);
                daftarItem.push(item);
                setObject('daftar-item', daftarItem);
            }


        }

        function table(id, nama, jumlah, harga_jual, harga_jual_tampil, totalharga, jenis, karyawan = null, nomor = null,
            nota = null, item_id = null, diskon_produk = null, type_diskon_promosi = null, btn_disable = null) {

            const listKaryawan = `<select class="form-control select2 cmbkaryawan" id="cmbkaryawan_${nomor}_${item_id}" name="karyawan_${nomor}[${item_id}]" >
                <option selected="selected" value="" >Pilih Karyawan</option>
                @foreach ($datakaryawan as $i => $item)
                    <option value="{{ $item->id }}">
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>`;

            const item = nota === null ? 'item' : 'itemPembayaran';

            const subTotalitem = `<td>
                                <span id="subtotal_item_${item_id}" class="subtotal_item_tampil subtotal_item_tampil_${id}">
                                ${formatRupiah(totalharga.toString(), 'Rp. ')}
                                </span>
                                <input type="hidden" class="subtotal_item totalharga total_harga_${id}" name="totalharga[]" id="total_harga_${item_id}" value="${totalharga}"/>
                            </td>`;

            const subTotalitemPembayaran = `<td>
                                <span id="subtotal_item_pembayaran_${item_id}" class="subtotal_item_tampil_pembayaran subtotal_item_tampil_pembayaran_${id}">
                                ${formatRupiah(totalharga.toString(), 'Rp. ')}
                                </span>
                                <input type="hidden" class="subtotal_item_pembayaran totalharga_pembayaran totalharga_pembayaran_${id}" name="totalharga_pembayaran[]" id="totalharga_pembayaran_${item_id}" value="${totalharga}"/>
                            </td>`;

            const subTotalTampil = nota === null ? subTotalitem : subTotalitemPembayaran;

            const tombolQty = `<div class="input-group input-group-xs ">
                                    <span class="input-group-btn" id="btn-tambah-${item_id}">
                                        <button class="btn btn-success" type="button" onclick="kurangjumlah('${item_id}')">
                                            <i class="glyphicon glyphicon-plus" >-</i>
                                        </button>
                                    </span>
                                    <input type="text" class="form-control inputqty text-right"  id="jumlah_item_${item_id}" name="qty[]" value="${jumlah}" readonly />
                                    <span class="input-group-btn" id="btn-kurang-${item_id}">
                                        <button class="btn btn-danger" type="button"  onclick="tambahjumlah('${item_id}')">
                                            <i class="glyphicon glyphicon-minus">+</i>
                                        </button>
                                    </span>
                                </div>`;

            const jumlahTampil =
                `<span id="jumlah_item__pembayaran_tampil_${item_id}"> ${jumlah}</span> <input type="hidden" name="jumlah_item__pembayaran_[]" id="jumlah_item__pembayaran_${item_id}" value="${jumlah}"/>`;
            const karyawanTampil =
                `<span id="karyawan_pembayaran_${item_id}"> ${jumlah}</span>`;

            const tombolAction = `<td class="text-center">
                                <button type="button" class="btn btn-warning btn-xs text-center" onclick="hapusitem('${item_id}')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>`;



            let tampilAction = nota === null ? tombolAction : '';

            let tampiDiskon = '';
            let tampilQty = '';

            if (type_diskon_promosi != null) {
                if (diskon_produk > 0) {
                    if (type_diskon_promosi === "persen") {
                        tampiDiskon = diskon_produk + " %";
                    }
                    if (type_diskon_promosi === "nominal") {
                        tampiDiskon = formatRupiah(diskon_produk.toString(), 'Rp. ');
                    }
                }
            }
            tampilQty = tombolQty;
            let dataKaryawan = '';
            if (nota === null) {
                if (karyawan === 'ya') {
                    dataKaryawan = listKaryawan;
                }
            } else {
                tampilQty = jumlahTampil;
                dataKaryawan =
                    `<span class="karyawan_pembayaran" id="karyawan_pembayaran_${item_id}"></span> <input type="hidden" class="karyawan_pembayaran_" name="karyawan_pembayaran_id[]" id="karyawan_pembayaran_id_${item_id}" value=""/>`;
            }
            let tampilDiskonElement =
                ` <span id="diskon_tampil${item_id}" class="diskon_tampil_${id}">
                                    ${tampiDiskon}
                                </span>
                                <input type="hidden" class="diskon_produk diskon_produk_id_${id}" name="diskon_produk_item[]" id="diskon_produk_${item_id}" value="${diskon_produk}"/>
                                <input type="hidden" class="type_diskon_promosi" name="type_diskon_promosi[]" id="type_diskon_promosi_${item_id}" value="${type_diskon_promosi}"/>`;

            let tampilDiskonPembyararan =
                ` <span id="diskon_tampil_pembayaran_${item_id}" class="diskon_tampil_pembayaran_${id}">
                                    ${tampiDiskon}
                                </span>
                                <input type="hidden" class="diskon_produk_pembayaran diskon_produk_pembayaran_id_${id}" name="diskon_produk[]" id="diskon_produk_pembayaran_${item_id}" value="${diskon_produk}"/>
                                <input type="hidden" class="type_diskon_promosi_pembayaran" name="type_diskon_promosi_pembayaran[]" id="type_diskon_promosi_pembayaran_${item_id}" value="${type_diskon_promosi}"/>`;

            let tampilanDiskon = '';
            if (nota === null) {
                tampilanDiskon = tampilDiskonElement;
            } else {
                tampilanDiskon = tampilDiskonPembyararan;

            }

            if (btn_disable !== null) {
                tampilQty =
                    `<input type="text" class="form-control inputqty text-right"  id="jumlah_item_${item_id}" name="qty[]" value="${jumlah}" readonly />`;
            }

            let content = `<tr id="${item}${item_id}" class="table_item_${id}">
                            <td  class="text-center nomor_${item}" data-index="${nomor}">
                                ${nomor}
                            </td>
                            <td>
                                <span class="nama_produk">${nama}</span>
                            <input type="hidden" class="hargaitem" name="harga[]" id="hargaitem${item_id}" value="${harga_jual}"/>
                            <input type="hidden" class="iditem" name="iditem[]" id="iditem${item_id}" value="${id}"/>
                            <input type="hidden" class="iditem" name="iditem_no__karyawan[]" id="iditem_no_karyawan${id}" value="${id}"/>
                            </td>
                            <td id="harga_itemtampil_${item_id}">
                                ${formatRupiah(harga_jual_tampil.toString(), 'Rp. ')}
                                <input type="hidden" class="harga_jual harga_jual_id_${id}" name="harga_jual[]" id="harga_jual_${item_id}" value="${harga_jual}"/>
                            </td>
                            <td >
                                ${tampilanDiskon}
                            </td>
                            <td  class="text-center" >
                                ${tampilQty}
                            </td>
                            ${subTotalTampil}

                            <td id="karyawan_produk_${item_id}">
                            ${dataKaryawan}
                            </td>
                            ${tampilAction}
                            </tr>`;
            return content;
        }

        function deletIt(id) {
            var msg = '';

            let deleteId = $.ajax({
                type: 'get',
                url: "/produk/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    deleteId = id;
                },
                error: function(jqXHR, exception) {
                    if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    }
                }
            });

            return msg;
        }

        function listItem() {
            let content = '';
            let contentPembayaran = '';
            let deleteId = '';
            // let produkAda = [];

            var cmbProduk = $('#cmbProduk option');
            var values = [];

            $('#cmbProduk option').each(function(val) {
                if ($(this).val() !== '') {
                    // values.push(['id' =>]);
                    values.push({
                        id: $(this).val(),
                    });
                }
            });

            let produkAda = daftarItem.filter(function(item) {
                return values.filter(function(val) {
                    return val.id == item.id;
                }).length == 1
            });
            $.each(produkAda, function(i, val) {
                let id = val.id;
                let nama = val.nama;
                let jumlah = val.jumlah;
                let harga = val.harga;
                let jenis = val.jenis;
                let karyawan = val.karyawan;
                let diskon_produk = val.diskon_produk;
                let type_diskon_promosi = val.type_diskon_promosi;

                let totalharga = harga * jumlah;
                if (type_diskon_promosi == 'persen') {
                    totalharga = (harga - ((diskon_produk / 100) * harga)) * jumlah;
                }
                if (type_diskon_promosi == 'nominal') {
                    totalharga = (harga - diskon_produk) * jumlah;
                }
                let item_id = val.item_id;
                let hargatampil = harga;
                let nomor = parseInt(i) + 1;
                let nota = true;

                let tableTampil = table(id, nama, jumlah, harga, hargatampil, totalharga, jenis, karyawan,
                    nomor, null,
                    item_id, diskon_produk, type_diskon_promosi);
                let tableTampilPembayaran = table(id, nama, jumlah, harga, hargatampil, totalharga, jenis, null,
                    nomor,
                    nota, item_id, diskon_produk, type_diskon_promosi);
                content += tableTampil;
                contentPembayaran += tableTampilPembayaran;
                $('#itemtidakada').remove();
                $('#itemtidakadaPembayaran').remove();

            });

            if (produkAda != "") {
                $('#tableProduk').append(content);
                $('#tableProdukPembayaran').append(contentPembayaran);
                for (let i in produkAda) {
                    let nomor = parseInt(i) + 1;
                    let data = produkAda[i];
                    let id = data.id;
                    let item_id = data.item_id;
                    $("#cmbkaryawan_" + nomor + "_" + item_id).select2({
                        width: '100%'
                    });
                };
            }

            localStorage.setItem('daftar-item', JSON.stringify(produkAda));
        }

        function totalHarga() {
            let qty = 0;
            let sum = 0;
            let qtyPembayaran = 0;
            let sumPembayaran = 0;

            let type_diskon = $('input[name="type_diskon"]:checked').val();

            $('.totalharga').each(function() {
                sum += parseFloat($(this).val()); // Or this.innerHTML, this.innerText
            });

            $('.inputqty').each(function() {
                qty += parseFloat($(this).val()); // Or this.innerHTML, this.innerText
            });

            $('.totalharga_pembayaran').each(function() {
                sumPembayaran += parseFloat($(this).val()); // Or this.innerHTML, this.innerText
            });
            $('.inputqty_pembayaran').each(function() {
                qtyPembayaran += parseFloat($(this).val()); // Or this.innerHTML, this.innerText
            });

            let grandTotal = sum;
            let grandTotalPembayaran = sumPembayaran;
            let diskon_input = $('#diskon_input').val();

            if (type_diskon === 'persen') {
                let persenDiskon = (sum * diskon_input) / 100;
                grandTotal = sum - persenDiskon;
                sumPembayaran = sum - persenDiskon;
            } else {
                diskon_input = diskon_input.replace(/Rp/g, "");
                diskon_input = diskon_input.replace(" ", "");
                diskon_input = diskon_input.split('.').join('');

                grandTotal = sum - diskon_input;
                sumPembayaran = sum - diskon_input;
            }



            $('#totalqtytampil').text(qty);
            $('#totalbayartampil').text(formatRupiah(sum.toString(), 'Rp. '));

            $('#totalbayar').val(sum);
            $('#grandtotalbayartampil').text(formatRupiah(grandTotal.toString(), 'Rp. '));
            $('#grandtotalbayar').val(grandTotal);

            $('#totalqtytampilPembayaran').text(qty);
            $('#totalbayartampilPembayaran').text(formatRupiah(sum.toString(), 'Rp. '));
            $('#totalbayarPembayaran').val(sum);

            $('#grandtotalbayartampilPembayaran').text(formatRupiah(sumPembayaran.toString(), 'Rp. '));
            $('#grandtotalbayarPembayaran').val(sumPembayaran);

        }

        function totalSubHarga(id) {
            let jumlahBaru = parseInt($('#jumlah_item_' + id).val());

            let harga = $('#hargaitem' + id).val();
            let type_diskon_promosi = $('#type_diskon_promosi_' + id).val();
            let diskon_produk = $('#diskon_produk_' + id).val();

            let subTotalHarga = harga * jumlahBaru;
            if (type_diskon_promosi == 'persen') {
                subTotalHarga = (harga - ((diskon_produk / 100) * harga)) * jumlahBaru;
            }
            if (type_diskon_promosi == 'nominal') {
                subTotalHarga = (harga - diskon_produk) * jumlahBaru;
            }

            let Rupiah = formatRupiah(subTotalHarga.toString(), 'Rp. ');
            $('#subtotal_item_' + id).text(Rupiah);
            $('#total_harga_' + id).val(subTotalHarga);
            $('#subtotal_item_pembayaran_' + id).text(Rupiah);
            $('#totalharga_pembayaran_' + id).val(subTotalHarga);
            $('#jumlah_item__pembayaran_tampil_' + id).text(jumlahBaru);
            $('#jumlah_item__pembayaran_' + id).val(jumlahBaru);


        }

        function tambahjumlah(id) {
            let jumlah = parseInt($('#jumlah_item_' + id).val());
            $('#jumlah_item_' + id).val(parseInt(jumlah) + 1);

            let jumlahPembayaran = parseInt($('#jumlah_item__pembayaran_' + id).val());
            $('#jumlah_item__pembayaran_tampil_' + id).text(parseInt(jumlah) + 1);
            $('#jumlah_item__pembayaran_' + id).val(parseInt(jumlah) + 1);
            totalSubHarga(id);
            addItemLocalStorage(null, true, null, null, null, null, null, null, id);
            totalHarga();
        }

        function kurangjumlah(id) {
            let jumlah = parseInt($('#jumlah_item_' + id).val());
            if (jumlah >= 2) {
                $('#jumlah_item_' + id).val(parseInt(jumlah) - 1);
                let harga = $('#hargaitem' + id).val();
                addItemLocalStorage(null, false, null, null, null, null, null, null, id);
                totalSubHarga(id)

                // let totalharga = harga * (parseInt(jumlah) - 1);
            }
            totalHarga();
        }

        function itemEmpty() {
            return `<tr id="itemtidakada"><td colspan="8">Belum ada daftar belanja</td></tr>`;
        }

        function itemEmptyPembayaran() {
            return `<tr id="itemtidakadaPembayaran"><td colspan="7">Belum ada daftar belanja</td></tr>`;
        }

        function hapusitem(id) {
            let content = '';
            let contentPembayaran = '';
            let item = $('#item' + id).length;
            if (item > 0) {
                $('#item' + id).remove();
            }
            let tableCount = $('#tableProduk  > tbody > tr').length;
            if (tableCount == 0) {
                content += itemEmpty();
                $('#tableProduk').append(content);
            }

            let itemPembayaran = $('#itemPembayaran' + id).length;
            if (itemPembayaran > 0) {
                $('#itemPembayaran' + id).remove();
            }
            let tableProdukPembayaran = $('#tableProdukPembayaran  > tbody > tr').length;
            if (tableProdukPembayaran == 0) {
                contentPembayaran += itemEmptyPembayaran();
                $('#tableProdukPembayaran').append(contentPembayaran);
            }

            totalHarga();
            $('.nomor_item').each(function(index, item) {
                if (parseInt($(item).data('index')) > 0) {
                    $(item).text(index + 1);
                }
            });
            $('.nomor_itemPembayaran').each(function(index, item) {
                if (parseInt($(item).data('index')) > 0) {
                    $(item).text(index + 1);
                }
            });


            removeLocalStorage(id);

        }

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

        //running localStorage
        getObject('daftar-item');
        listItem();

        totalHarga();

        //tambah produk
        $("#cmbProduk").on("change", function(e) {
            let selected = [];
            selected = $(e.currentTarget).val();
            let content = '';
            let contentPembayaran = '';
            let idPromosiPelanggan = "";
            let idproduk = "";
            if ($('#promosi_pelanggan').val()) {
                idPromosiPelanggan = "?promosi=" + $('#promosi_pelanggan').val();
            }
            if (idPromosiPelanggan != '') {
                if ($("input[name='iditem[]']").val()) {
                    idproduk = "&produk_promosi=" + $("input[name='iditem[]']").val();
                }
            } else {
                if ($("input[name='iditem[]']").val()) {
                    idproduk = "?produk_promosi=" + $("input[name='iditem[]']").val();
                }
            }
            $("#cmbProduk option[value=" + selected + "]").prop("selected", false);
            $.ajax({
                type: 'get',
                url: "/produk/" + selected + idPromosiPelanggan + idproduk,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#itemtidakada').remove();
                    $('#itemtidakadaPembayaran').remove();
                    const {
                        id,
                        nama,
                        jenis,
                        harga_jual,
                        diskon_produk,
                        type_diskon_promosi,
                        karyawan,
                        btn_disable,
                        harga_jual_tampil,
                    } = data.data;
                    let nomorTeakhir = $('#tableProduk > tbody > tr').length;
                    let nomorTeakhirPembayaran = $('#tableProdukPembayaran > tbody > tr').length;

                    let item = $('#item' + id).length;
                    let iditem = $('#iditem_no_karyawan' + id).length;
                    let nomor = parseInt(nomorTeakhir) + 1;
                    let nota = true;
                    let item_id = id + "-" + Math.floor(Math.random() * 5) + Date.now();
                    let hargatampil = formatRupiah(harga_jual.toString(), 'Rp. ');

                    if (karyawan === 'ya') {
                        let tableTampil = table(
                            id, nama, 1, harga_jual, harga_jual_tampil,
                            harga_jual, jenis, karyawan, nomor, null, item_id, diskon_produk,
                            type_diskon_promosi, btn_disable);


                        content += tableTampil;
                        $('#tableProduk').append(content);
                        $("#cmbkaryawan_" + nomor + "_" + item_id).select2({
                            width: '100%'
                        });

                        // namnbah ke local storage
                        addItemLocalStorage(id, true, nama, 1, harga_jual, jenis, karyawan, nomor,
                            item_id, diskon_produk, type_diskon_promosi);

                        let tableTampilPembayaran = table(
                            id, nama, 1, harga_jual, harga_jual_tampil,
                            harga_jual, jenis, null, nomor, nota, item_id, diskon_produk,
                            type_diskon_promosi);

                        contentPembayaran += tableTampilPembayaran;


                        $('#tableProdukPembayaran').append(contentPembayaran);
                    }

                    if (karyawan === 'tidak') {
                        if (iditem === 0) {
                            let tableTampil = table(
                                id, nama, 1, harga_jual, harga_jual_tampil,
                                harga_jual, jenis, null, nomor, null, item_id, diskon_produk,
                                type_diskon_promosi, btn_disable);

                            content += tableTampil;
                            $('#tableProduk').append(content);

                            addItemLocalStorage(id, true, nama, 1, harga_jual, jenis, null, nomor,
                                item_id, diskon_produk, type_diskon_promosi);

                            let tableTampilPembayaran = table(
                                id, nama, 1, harga_jual, harga_jual_tampil,
                                harga_jual, jenis, null, nomor, nota, item_id, diskon_produk,
                                type_diskon_promosi);

                            contentPembayaran += tableTampilPembayaran;
                            $('#tableProdukPembayaran').append(contentPembayaran);

                            $("#cmbkaryawan_" + nomor + "_" + item_id).select2({
                                width: '100%'
                            });
                        } else {

                            let idParent = $('#iditem_no_karyawan' + id).closest('tr').attr('id');
                            let jumlahId = $('#' + idParent).find('.inputqty').attr('id');
                            let haragaId = $('#' + idParent).find('.hargaitem').attr('id');
                            let haragaTampilId = $('#' + idParent).find('.subtotal_item_tampil').attr(
                                'id');
                            let subTotalId = $('#' + idParent).find('.subtotal_item').attr('id');
                            item_id = idParent.replace(/item/g, '');
                            let jumlah = parseInt($('#' + jumlahId).val());
                            $('#' + jumlahId).val(parseInt(jumlah) + 1);
                            let jumlahBaru = $('#' + jumlahId).val();
                            let harga = parseInt($('#' + haragaId).val());
                            let totalHarga = parseInt(jumlahBaru) * parseInt(harga);

                            let rupiah = formatRupiah(totalHarga.toString(), 'Rp. ')
                            $('#' + jumlahId).val(parseInt(jumlah) + 1);
                            $('#' + haragaTampilId).text(rupiah);
                            $('#' + subTotalId).val(totalHarga);

                            $('#subtotal_item_pembayaran_' + item_id).text(rupiah);
                            $('#totalharga_pembayaran_' + item_id).val(totalHarga);

                            addItemLocalStorage(id, true, nama, jumlah, harga_jual, jenis, null, nomor,
                                item_id, diskon_produk, type_diskon_promosi);
                        }
                    }
                    totalSubHarga(item_id);
                    changeKaryawan();
                    totalHarga();
                }
            });


        });

        //run ganti karyawan
        changeKaryawan();

        function changeKaryawan() {
            $(".cmbkaryawan").on("select2:select", function(e) {

                var select_val = $(e.currentTarget).val();
                var parentId = $(this).closest('tr').attr('id');
                $("#cmbkaryawan option:selected").text();
                let id = parentId.replace(/item/g, "");
                let karyawanIdTampil = "#karyawan_pembayaran_" + id;
                let karyawanId = "#karyawan_pembayaran_id_" + id;
                let parentIsInvalid = "#karyawan_produk_" + id;
                if (karyawanId === '') {
                    $(parentIsInvalid).addClass("form-control");
                    $(parentIsInvalid).addClass("is-invalid");
                    Swal.fire({
                        title: 'Oops...',
                        text: "mohon isi pilih lagi karyawan",
                    })
                }
                $(parentIsInvalid).removeClass("form-control");
                $(parentIsInvalid).removeClass("is-invalid");
                let idSelect = $(this).attr('id');
                let namaKaryawan = $('#' + idSelect + " option:selected").text();
                let value = $('#' + idSelect + " option:selected").val();
                $(karyawanIdTampil).text(namaKaryawan.trim());
                $(karyawanId).val(value);
            });
        }

        //list pelanggan
        $("#listpelanggan").on("change", function(e) {
            let selected = [];
            selected = $(e.currentTarget).val();
            $('#notaPembayaran').prop("disabled", false);


            $.ajax({
                type: 'get',
                url: "/pelanggan/" + selected,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    const {
                        id,
                        nama,
                        promosi_id,
                        nama_promosi,
                        keterangan,
                        btn,
                        no_hp,
                        produk
                    } = data.data;

                    $('#pelangganTampilPembayaran').text(nama);
                    $('#pelangganPembayaran').val(id);
                    $('#pelangganTampil').text(nama_promosi);

                    // ganti promosi
                    let listItemPelanggan = "";
                    let listItemPelangganPembyaran = "";
                    let getIdItem = "";
                    let parentId = "";
                    let itemId = "";
                    let qtyList = "";
                    if (btn == 'disable') {
                        $('#promosi_pelanggan').val(promosi_id);
                    } else {
                        $('#promosi_pelanggan').val("");
                    }
                    $('#no_hp_pembayaran').val(no_hp);
                    if (nama_promosi != "") {
                        if (produk) {
                            $.each(produk, function(index, val) {
                                let tampiDiskon = "";
                                if (val.type_diskon === "persen") {
                                    tampiDiskon = val.diskon + " %";
                                }
                                if (val.type_diskon === "nominal") {
                                    tampiDiskon = formatRupiah(val.diskon.toString(),
                                        'Rp. ');
                                }
                                $('#promosi_tampil').val(nama_promosi);
                                $('#promosi_tampil_pembayaran').text(
                                    nama_promosi);
                                $('#keterangan_promosi').text(
                                    keterangan);

                                $(".table_item_" + val.id).each(function(i, value) {
                                    if (i == 0) {
                                        listlistItemPelanggan = $(this).attr("id");
                                        itemId = listlistItemPelanggan.replace('item',
                                            '');

                                        $("#diskon_produk_" + itemId).val(val.diskon);
                                        $("#diskon_tampil" + itemId).text(tampiDiskon);
                                        $("#jumlah_item_" + itemId).val(1);
                                        $("#jumlah_item__pembayaran_tampil_" + itemId)
                                            .text(1);
                                        $("#jumlah_item__pembayaran_" + itemId)
                                            .val(1);

                                        qtyList = $("#jumlah_item_" + itemId).val();
                                        harga = $("#harga_jual_" + itemId).val();
                                        diskon = $("#diskon_produk_" + itemId).val();
                                        $("#type_diskon_promosi_" + itemId).val(val
                                            .type_diskon);
                                        if (btn) {
                                            $('#btn-tambah-' + itemId).hide()
                                            $('#btn-kurang-' + itemId).hide()
                                        }
                                        if (btn === "") {
                                            $('#btn-tambah-' + itemId).show()
                                            $('#btn-kurang-' + itemId).show()
                                        }

                                        let total = 0;
                                        if (diskon) {
                                            if (val.type_diskon === "persen") {
                                                total = (harga - ((diskon / 100) *
                                                    harga)) * qtyList;
                                            } else {
                                                total = (harga - diskon) * qtyList;
                                            }
                                        }
                                        $("#total_harga_" + itemId).val(total);

                                        let totalTampil = formatRupiah(total.toString(),
                                            'Rp. ');
                                        $("#subtotal_item_" + itemId).text(
                                            totalTampil);

                                        $("#totalharga_pembayaran_" + itemId).val(
                                            total);

                                        $("#subtotal_item_pembayaran_" + itemId).text(
                                            formatRupiah(total.toString(),
                                                'Rp. '));

                                        $("#diskon_produk_pembayaran_" + itemId).val(
                                            val.diskon);
                                        $("#diskon_tampil_pembayaran_" + itemId).text(
                                            tampiDiskon);
                                    }

                                });
                            });
                        }
                        totalHarga();

                    }
                }
            });
        });

        $("#listMetode").on("change", function(e) {
            var id = $(e.currentTarget).val();
            $('#no_ref').prop("readonly", true);
            $.ajax({
                type: 'get',
                url: "/metode-bank/" + id,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data) {
                    $('#btn_pembayaran').prop("disabled", true);
                    $("#listbank").html("");
                    $("#listbank").append('<option value="">Pilih Bank</option>')
                    $.each(data.data, function() {
                        $("#listbank").append(
                            `<option value="${this.id}">${this.nama}</option>`
                        );
                        if (this.nama === 'Cash') {
                            $("#listbank").val(this.id);
                        }
                    });
                    let text = $("#listMetode option:selected").text();
                    if (text.includes('Cash')) {
                        let bank = $("#listbank").text();
                        $('#no_ref').val("");
                        $('#uang_bayar_tampil').prop("readonly", false);
                        $('#no_ref').prop("readonly", true);
                    } else {
                        $('#uang_angsul_tampil').val("");
                        $('#uang_angsul').val("");

                        $('#uang_bayar_tampil').prop("readonly", true);
                        $('#uang_bayar_tampil').val("");
                        $('#uang_bayar').val("");

                        $('#no_ref').prop("readonly", true);

                    }
                }
            });
        });
        $("#listbank").on("change", function(e) {
            let text = $("select#listbank option:selected").text();
            if (text == 'Cash') {
                $('#uang_bayar_tampil').prop("readonly", false);
                $('#no_ref').prop("readonly", false);
            } else {
                $('#no_ref').prop("readonly", false);
            }
            $('#btn_pembayaran').prop("disabled", true);
        });
        $('#uang_bayar_tampil').prop("readonly", true);

        $('input[name="type_diskon"]').click(function() {
            var type_diskon = $("input[name='type_diskon']:checked").val();

            if (type_diskon === 'persen') {
                $('#diskon_input').prop("type", "number");
                $('#diskon_input').attr('max', '100');
                $('#diskon_input').attr('min', '10');
                $('#diskon_input').attr('maxlength', '100');
            } else {
                $('#diskon_input').prop("type", "text");
            }
            $('#diskon_input').val("");
        });

        $('#diskon_input').bind("input keyup paste ", function() {
            let text = this.value.replace(/[^0-9]/g, '');
            $('#diskon_tampil').val(text);

            let type_diskon = $('input[name="type_diskon"]:checked').val();
            let sum = 0;
            $('.totalharga').each(function() {
                sum += parseFloat($(this).val()); // Or this.innerHTML, this.innerText
            });

            let grandTotal = sum;
            let diskon_input = $('#diskon_input').val();

            if (type_diskon === 'persen') {
                diskon_input = this.value.replace(/[^0-9]/g, '');
                value = $(this).val();
                if ((value !== '') && (value.indexOf('.') === -1)) {
                    $(this).val(Math.max(Math.min(value, 100), -100));
                    let persenDiskon = (sum * diskon_input) / 100;
                    $('#diskonPembayaran').val(text);
                    $('#diskon_tampil').text(formatRupiah(persenDiskon.toString(), ' '));
                    $('#diskonPembayaranTampil').text(formatRupiah(persenDiskon.toString(), ' '));
                }
            } else {
                diskon_input = diskon_input.replace(/Rp/g, "");
                diskon_input = diskon_input.replace(" ", "");
                diskon_input = diskon_input.split('.').join('');

                $('#diskon_input').val(formatRupiah(diskon_input.toString(), ' '));
                $('#diskon_tampil').text(formatRupiah(diskon_input.toString(), ' '));
                $('#diskonPembayaran').val(diskon_input);
                $('#diskonPembayaranTampil').text(formatRupiah(diskon_input.toString(), ' '));

            }
            totalHarga()
        });

        $('textarea#catatan').on("input keyup paste ", function() {
            $('#catatan_pembayaran_tampil').text(this.value);
            $('#catatan_pembayaran').val(this.value);
        });


        $('#no_ref').on("input keyup paste", function() {
            $('#btn_pembayaran').prop("disabled", false);
        });

        $('#uang_bayar_tampil').on("input keyup paste", function() {
            let val = formatRupiah(this.value, ' ');
            $('#uang_bayar_tampil').val(val);
            let grandtotal = $('#grandtotalbayarPembayaran').val();

            let uangbayar = $('#uang_bayar_tampil').val();
            uangbayar = uangbayar.replace(/Rp/g, "");
            uangbayar = uangbayar.replace(" ", "");
            uangbayar = uangbayar.split('.').join('');
            let uangangsul = parseInt(uangbayar) - parseInt(grandtotal);
            if (parseInt(uangbayar) < parseInt(grandtotal)) {
                $('#btn_pembayaran').prop("disabled", true);

                rupiahUangAngsul = "- " + formatRupiah(uangangsul.toString(), ' Rp. -');
            }

            if (parseInt(uangbayar) >= parseInt(grandtotal)) {
                //lebih
                rupiahUangAngsul = formatRupiah(uangangsul.toString(), ' Rp. -');
                $('#btn_pembayaran').prop("disabled", false);
            }
            // if (parseInt(uangbayar) = parseInt(grandtotal)) {
            //     //lebih
            //     rupiahUangAngsul = formatRupiah(uangangsul.toString(), ' Rp. -');
            //     $('#btn_pembayaran').prop("disabled", false);
            // }

            $('#uang_angsul_tampil').val(rupiahUangAngsul);
            $('#uang_angsul').val(uangangsul);
            $('#uang_bayar').val(uangbayar);
        });

        // bikin nota pembayaran

        let catatan = $('#catatan').val();
        $('#catatan_pembayaran_tampil').text(catatan);
        $('#catatan_pembayaran').text(catatan);

        $('#btn_pembayaran').on("click", function() {
            let no_hp_pembayaran = $('#no_hp_pembayaran').val();
            let no_hp_toko = $('#no_hp_toko').val();
            let nama_toko = $('#nama_toko').val();
            let alamat_toko = $('#alamat').val();
            let no_nota = $('#no_nota').val();
            let tanggal = $('#tanggal').val();
            let diskonPembayaranTampil = $('#diskonPembayaranTampil').text();
            let totalbayartampilPembayaran = $('#totalbayartampilPembayaran').text();
            let totalqtytampilPembayaran = $('#totalqtytampilPembayaran').text();
            let nama_pelanggan = $('#pelangganTampilPembayaran').text();
            let catatan = $('#catatan_pembayaran_tampil').text();
            let grandtotalbayartampilPembayaran = $('#grandtotalbayartampilPembayaran').text();

            let produkListLayanan = '';
            let produkListProduk = '';
            // Detail Layanan :*Gunting Men  1(satuan) x80000=1
            $.each(daftarItem, function(i, val) {
                let harga = formatRupiah(val.harga.toString(), 'Rp. ');
                if (val.karyawan == 'ya') {
                    let karyawan = $('#karyawan_pembayaran_' + val.item_id).text();
                    produkListLayanan +=
                        `\n${val.nama}\nJumlah : ${val.jumlah}\nHarga= ${harga}\nYang Melayani : ${karyawan}\n`;
                } else {
                    produkListProduk +=
                        `\n${val.nama}\nJumlah : ${val.jumlah}\nHarga= ${harga}\n`;
                }
            });
            let produkLayanan = `Detail Layanan :\n ${produkListLayanan}`;
            let produkList = produkListProduk != '' ?
                `\n-------------------------------------------------\nDetail Produk :\n ${produkListProduk}` : '';
            let textMessage =
                `Kepada yang terhormat Bapak/Ibu , terimakasih telah melakukan transaksi di ${nama_toko}, berikut nota transaksi Bapak/Ibu 🙏 :\n\n${nama_toko}\n${no_hp_toko}\n${alamat_toko}\nBuka Setiap Hari\n-------------------------------------------------\nPelanggan : Tuan/Nyonya ${nama_pelanggan}\nNo. Telp : ${no_hp_pembayaran}\n-------------------------------------------------\nStatus : LUNAS\nNo. Nota : ${no_nota}\nTgl. Transaksi : ${tanggal}\n-------------------------------------------------\n${produkLayanan}${produkList}\n-------------------------------------------------\nSUBTOTAL :${totalbayartampilPembayaran}\nDISKON :${diskonPembayaranTampil.trim()}\nTOTAL : ${grandtotalbayartampilPembayaran}\n-------------------------------------------------\nCatatan : ${catatan}\n-------------------------------------------------\n`;
            console.log(textMessage);
            window.open(`https://api.whatsapp.com/send?phone=62${no_hp_pembayaran}&text=` + encodeURI(textMessage));
            localStorage.removeItem("daftar-item");
            $("#formPembayaran").submit();
            $("#addPembayaran").hide();
        });

        $('#notaPembayaran').on("click", function() {
            let idKaryawan = [];
            let value = [];
            let selectKaryawan = [];
            let listKaryawan = $(".cmbkaryawan").val();
            let openModal = 1;
            let checkArray = [];
            let pelangganId = $("#listpelanggan").val();

            if (listKaryawan !== undefined) {
                $(".cmbkaryawan").each(function(index, val) {
                    idKaryawan[index] = $(this).attr('id');
                })
                $(idKaryawan).each(function(index, val) {
                    value[index] = $('#' + val + " option:selected").val();
                    if (value[index] === "") {
                        // ganti parent class
                        $('#' + this).parent().addClass("form-control");
                        $('#' + this).parent().addClass("is-invalid");

                        checkArray.push(1)
                    }
                })
                let index = checkArray.findIndex(function(i) {
                    return i === 1;
                })
                if (index !== -1) {
                    Swal.fire({
                        title: 'Oops...',
                        text: "Ada karyawan yang belum di pilih",
                    })
                } else {
                    $('#addPembayaran').modal('toggle');
                    $("#listpelanggan option[value=" + pelangganId + "]").prop("selected", false);
                }

                // $('#addPembayaran').modal('toggle');
            } else {
                $('#addPembayaran').modal('toggle');
                $("#listpelanggan option[value=" + pelangganId + "]").prop("selected", false);
            }

        });

        // kirim ke wa
        // $('#btn_pembayaran').on("click", function() {

        // });
    </script>
@endpush
