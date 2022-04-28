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
                                    <label for="rekanan" class=" form-control-label">Pekerjaan :
                                        {{ $item->nomor_pelaksanaan_pekerjaan }}</label>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div>
                                            <span>Daftar Item</span>
                                            <table class="table table-bordered " width="100%">
                                                <thead>
                                                    <tr>
                                                        <th width="5">#</th>
                                                        <th>Nama</th>
                                                        <th width="10">Jumlah</th>
                                                        <th>Harga</th>
                                                        <th>Total Harga</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($item->hasItem as $index => $item)
                                                        <tr>
                                                            <td>{{ $index + 1 }}
                                                            </td>
                                                            <td>{{ $item->nama }}</td>
                                                            <td>{{ $item->jenis }}</td>
                                                            <td>{{ $item->pivot->qty }}</td>
                                                            <td>Rp. {{ format_uang($item->pivot->harga) }}</td>
                                                            <td>Rp.
                                                                {{ format_uang($item->pivot->harga * $item->pivot->qty) }}
                                                            </td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="10">Data Item tidak ada</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="col-6">
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
                                                    {{-- @forelse ($galianPekerjaan as $index => $item)
                                                        <tr>
                                                            <td>{{ $index + 1 }}
                                                            </td>
                                                            <td>{{ $item->panjang }}</td>
                                                            <td>{{ $item->lebar }}</td>
                                                            <td>{{ $item->dalam }}</td>
                                                            <td>Rp. {{ format_uang($item->total) }}</td>
                                                            <td>{{ $item->bongkaran }}</td>
                                                            <td>{{ $item->keterangan }}</td>

                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="10">Data Item Galian ada</td>
                                                        </tr>
                                                    @endforelse --}}
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
