@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <form action="{{ $action }}" method="post" role="form" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3">
                                    <div class="form-group">
                                        <div>
                                            <label for="NoTagihan" class=" form-control-label">Nomor Tagihan </label>
                                        </div>
                                        <div>
                                            <input type="text" name="no_tagihan" id="No Tagihan" placeholder="No Tagihan "
                                                class="form-control" readonly value="{{ $tagihan->nomor_tagihan }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <div>
                                            <label for="NoTagihan" class=" form-control-label">Tanggal Tagihan</label>
                                        </div>
                                        <div>
                                            <input type="text" name="no_tagihan" id="No Tagihan" placeholder="No Tagihan "
                                                class="form-control" readonly
                                                value="{{ tanggal_indonesia($tagihan->created_at) }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                        <div>
                                            <label for="rekanan" class=" form-control-label">Rekanan</label>
                                        </div>
                                        <div>
                                            <input type="text" name="rekanan" id="rekanan" placeholder="Rekanan "
                                                class="form-control" readonly value="{{ $tagihan->rekanan }}">
                                        </div>
                                    </div>
                                </div>




                            </div>
                            @foreach ($tagihan->hasPelaksanaanPekerjaan as $key => $item)
                                <div>
                                    <label for="rekanan" class=" form-control-label">
                                        <h3>Pekerjaan :
                                            {{ $item->No_Spk }}</h3>
                                    </label>
                                </div>
                                <div class="row">
                                    <div class="col-md-7">
                                        <div>
                                            <span>Daftar Item</span>
                                            <table class="table table-bordered " width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5">#</th>
                                                        <th>Nama</th>
                                                        <th>Jenis barang</th>
                                                        <th width="10">Jumlah</th>
                                                        <th>Harga</th>
                                                        <th>Total Harga</th>
                                                        <th class="text-center" width="10">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($item->hasItem as $nomor => $barang)
                                                        <tr class="{{ $barang->pivot->harga == 0 ? 'bg-danger' : '' }}"
                                                            id="{{ $barang->slug }}_{{ $item->id }}">
                                                            <td>{{ $nomor + 1 }}
                                                            </td>
                                                            <td>{{ $barang->nama }}</td>
                                                            <td>{{ $barang->jenis }}</td>
                                                            <td id="qty_{{ $barang->slug }}_{{ $item->id }}">
                                                                {{ $barang->pivot->qty }}</td>
                                                            @if ($barang->pivot->harga == 0)
                                                                <td>
                                                                    <div class="input-group mb-2 mr-sm-2">
                                                                        <div class="input-group-prepend">
                                                                            <div class="input-group-text">Rp.</div>
                                                                        </div>
                                                                        <input type="text"
                                                                            name="harga_{{ $barang->slug }}_{{ $item->id }}"
                                                                            id="harga_{{ $barang->slug }}_{{ $item->id }}"
                                                                            placeholder="" class="form-control">
                                                                    </div>
                                                                </td>
                                                                <td
                                                                    id="total_harga_{{ $barang->slug }}_{{ $item->id }}">
                                                                    Rp. 0</td>
                                                                @push('script')
                                                                    <script>
                                                                        $('#harga_{{ $barang->slug }}_{{ $item->id }}').on("input", function() {

                                                                            let val = formatRupiah(this.value, '');
                                                                            $('#harga_{{ $barang->slug }}_{{ $item->id }}').val(val);
                                                                            let qty = parseInt($('#qty_{{ $barang->slug }}_{{ $item->id }}').text());
                                                                            let totalHarga = val.replace(".", "") * qty;

                                                                            console.log(totalHarga);
                                                                            $('#total_harga_{{ $barang->slug }}_{{ $item->id }}').text("Rp. " + formatRupiah(totalHarga
                                                                                .toString(), ''));

                                                                            $('#{{ $barang->slug }}_{{ $item->id }}').removeClass('bg-danger');
                                                                        });
                                                                    </script>
                                                                @endpush
                                                            @else
                                                                <td>
                                                                    Rp. {{ format_uang($barang->pivot->harga) }}</td>

                                                                <td>
                                                                    Rp.
                                                                    {{ format_uang($barang->pivot->harga * $barang->pivot->qty) }}
                                                                </td>
                                                            @endif

                                                            <td class="text-center">
                                                                @if ($barang->pivot->harga == 0)
                                                                    <button class="btn btn-warning btn-sm"
                                                                        data-toggle="tooltip" data-placement="top"
                                                                        title="Ubah">
                                                                        <i class="nav-icon fas fa-edit"></i> Ubah
                                                                    </button>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="10">Data Item tidak ada</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3">Grand Total
                                                        </th>
                                                        <th>{{ $item->hasItem->sum('pivot.qty') }}</th>
                                                        <th>{{ format_uang($item->hasItem->sum('pivot.harga')) }}</th>
                                                        <th>{{ format_uang($item->total_harga) }}

                                                    </tr>
                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div>
                                            <span>Daftar Galian</span>
                                            <table class="table table-bordered " width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5">#</th>
                                                        <th width="10">Panjang</th>
                                                        <th width="10">Lebar</th>
                                                        <th width="10">Dalam</th>
                                                        <th>Total Harga</th>
                                                        <th>Bongkaran</th>
                                                        <th>Keterangan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($item->hasGalianPekerjaan as $key => $value)
                                                        <tr>
                                                            <td>{{ $key + 1 }}
                                                            </td>
                                                            <td>{{ $value->panjang }} m</td>
                                                            <td>{{ $value->lebar }} m</td>
                                                            <td>{{ $value->dalam }} m</td>
                                                            <td>Rp. {{ format_uang($value->total) }}</td>
                                                            <td>{{ $value->bongkaran }}</td>
                                                            <td>{{ $value->keterangan }}</td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="10">Data Item Galian ada</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

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
