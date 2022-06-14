@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <form method="get" role="form" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">

                                    <div class="form-group">
                                        <div>
                                            <label for="Name" class=" form-control-label">Nama Rekaan / CV</label>
                                        </div>
                                        <div>
                                            <input type="text" name="name" placeholder="Name User" class="form-control "
                                                value="{{ $rekanan->nama }}" disabled id="nama">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">

                                    <div class="form-group">
                                        <div>
                                            <label for="no_hp" class=" form-control-label">No HP</label>
                                        </div>
                                        <div>
                                            <input type="text" name="no_hp" class="form-control" id="no_hp"
                                                value="{{ $rekanan->no_hp }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">

                                    <div class="form-group">
                                        <div>
                                            <label for="lokasi" class=" form-control-label">Total Lokasi</label>
                                        </div>
                                        <div>
                                            <input type="text" name="lokasi" class="form-control" id="lokasi"
                                                value="{{ $total_lokasi }}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">

                                    <div class="form-group">
                                        <div>
                                            <label for="nama_penangung_jawab" class=" form-control-label">Penanggung
                                                Jawab</label>
                                        </div>
                                        <div>
                                            <input type="text" name="nama_penangung_jawab" class="form-control"
                                                id="nama_penangung_jawab" value="{{ $rekanan->nama_penangung_jawab }}"
                                                disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-bordered table-responsive" width="100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Centang Semua <input type="checkbox"
                                                name="pelaksanaan_all" id="pelaksanaan_all" class="checkAll" value="">
                                        </th>
                                        <th>No.</th>
                                        <th>Nomor SPK</th>
                                        <th>Rekanan</th>
                                        <th>Tanggal Mulai</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Lokasi</th>
                                        <th>Total Tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penunjukan as $index => $item)
                                        <tr class="{{ $item->keterangan_barang != null ? 'bg-danger' : '' }} list_pelaksanaan"
                                            data-no_spk="{{ $item->no_spk }}" data-rekanan="{{ $item->rekanan }}"
                                            data-tanggal_mulai="{{ isset($item->tanggal_mulai) ? tanggal_indonesia($item->tanggal_mulai) : '' }}"
                                            data-lokasi="{{ $item->lokasi }}"
                                            data-total_pekerjaan="{{ format_uang($item->total_pekerjaan) }}"
                                            data-tanggal_selesai="{{ isset($item->tanggal_selesai) ? tanggal_indonesia($item->tanggal_selesai) : '' }}">
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
                                            <td>Rp. {{ format_uang($item->total_pekerjaan) }}</td>


                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11">Data Aduan tidak ada</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="7" class="text-right">Grand Total</th>
                                        <th>
                                            Rp. {{ format_uang($penunjukan->sum('total_pekerjaan')) }}
                                            <input type="hidden" name="total_pekerjaan" id="total_pekerjaan"
                                                value="Rp. {{ format_uang($penunjukan->sum('total_pekerjaan')) }}">
                                        </th>
                                    </tr>
                                </tfoot>
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
                            <button class="btn btn-success" id="kirimWa">Kirim via Whatsapp</button>
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


        $("#kirimWa").on('click', function(e) {
            e.preventDefault();
            let dataRekanan = '';
            $('.list_pelaksanaan').each(function(index, item) {

                let spk = $(this).attr('data-no_spk');
                let rekanan = $(this).attr('data-rekanan');
                let tanggal_mulai = $(this).attr('data-tanggal_mulai');
                let tanggal_selesai = $(this).attr('data-tanggal_selesai');
                let lokasi = $(this).attr('data-lokasi');
                let total_pekerjaan = $(this).attr('data-total_pekerjaan');

                dataRekanan +=
                    `\nNo SPK : ${spk}\nLokasi : ${lokasi}\nTanggal Mulai : ${tanggal_mulai}\nTanggal Selesai : ${tanggal_selesai}\nTotal : Rp. ${total_pekerjaan}\n\n`;

            });

            let namarekanan = $('#nama').val();
            let no_hp = $('#no_hp').val();
            let nama_penangung_jawab = $('#nama_penangung_jawab').val();
            let lokasi = $('#lokasi').val();
            let total_pekerjaan = $('#total_pekerjaan').val();
            let bulan = "{{ $bulan }}";

            let textMessage =
                `PERUMDAM TIRTA KENCANA SAMARIDA\n\nKepada yang terhormat Bapak/Ibu di Samarinda, berikut detail tagihan ${namarekanan} : \n\nBulan Tagihan : ${bulan} \nTotal Lokasi : ${lokasi}\nTotal Tagihan : ${total_pekerjaan}\n\nTTD\nASISTEN MANAGER PERENCANAAN`;

            window.open(`https://api.whatsapp.com/send?phone=62${no_hp}&text=` + encodeURI(
                textMessage));
        });
    </script>
@endpush
