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
                    <input type="text" name="tagihan_id" value="{{ $data->id }}">
                @endif
                <div class="col-md-12">
                    <div class="card">
                        <!-- /.card-header -->

                        <div class="card-body">
                            <table class="table table-bordered table-responsive" width="100%">
                                <thead>
                                    <tr>
                                        <th>Centang Semua <input type="checkbox" name="pelaksanaan_all" id="pelaksanaan_all"
                                                class="checkAll" value=""></th>
                                        <th>No.</th>
                                        <th>Nomor SPK</th>
                                        <th>Rekanan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penunjukan as $index => $item)
                                        <tr
                                            class="{{ $item->keterangan_barang != null ? 'bg-danger' : '' }} list_pelaksanaan">
                                            <td class="text-center">
                                                <input type="checkbox" name="pelaksanaan[]" id="pelaksanaan"
                                                    class="check" value="{{ $item->id }}" checked>
                                            </td>
                                            <td>{{ $index + 1 }}
                                            </td>
                                            <td>{{ $item->no_spk }}</td>
                                            <td>{{ $item->rekanan }}</td>
                                            <td>{{ isset($item->tanggal_mulai) ? tanggal_indonesia($item->tanggal_mulai) : '' }}
                                            </td>
                                            <td>{{ isset($item->tanggal_selesai) ? tanggal_indonesia($item->tanggal_selesai) : '' }}
                                            </td>
                                            <td>{{ $item->lokasi }}</td>


                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11">Data Aduan tidak ada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ./col -->
            </div>
            <div class="row">
                <div class="col-md-12">

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
    <script>
        $(".checkAll").on('change', function() {
            if ($(this).is(':checked')) {
                $(".check" + this.value).prop('checked', true);
            } else {
                $(".check" + this.value).prop('checked', false);
            }
        });

        var cekcountList = $('.list_pelaksanaan').length;
        var cekcountChecked = $('.check:checked').length;

        if (cekcountList == cekcountChecked) {
            $(".checkAll").prop('checked', true);
        } else {
            $(".checkAll").prop('checked', false);
        }

        $(".check").on('click', function() {
            var header = $(this).attr('class');
            var classParent = header.replace(" check", "");
            var countChecked = $('.' + classParent + ':checked').length;
            var countList = $('.list_pelaksanaan').length;
            if (countList == countChecked) {
                $(".checkAll").prop('checked', true);
            } else {
                $(".checkAll").prop('checked', false);
            }
        });
    </script>
@endpush
