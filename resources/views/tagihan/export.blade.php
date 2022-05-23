<div class="col-md-12">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">

            <table>
                <thead>
                    <tr>
                        <th>
                            <strong>
                                Nomor Tagihan : {{ $tagihan->nomor_tagihan }}
                            </strong>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <strong>
                                Rekanan : {{ $tagihan->rekanan }}
                            </strong>
                        </th>
                    </tr>
                    <tr>
                        <th>
                            <strong>
                                Tanggal Tagihan : {{ $tagihan->tanggal_tagihan }}
                            </strong>
                        </th>
                    </tr>
                </thead>
            </table>
            <table style="border: 3px solid black;" class="table table-bordered table-responsive" width="100%"
                id="tableDokumentasi">
                <thead>
                    <tr>
                        <th width="5" style="border: 3px solid black; text-align:center;">#</th>
                        <th style="border: 3px solid black; text-align:center;" width="12">No Pekerjaan</th>
                        <th style="border: 3px solid black;" width="50">Uraian Rekanan</th>
                        <th style="border: 3px solid black;" width="50">Master Uraian</th>
                        <th style="border: 3px solid black; text-align:center;" width="13">Jenis Pekerjaan</th>
                        <th style="border: 3px solid black; text-align:center;" width="10">Jenis Harga</th>
                        <th style="border: 3px solid black;" width="18">Harga Rekanan</th>
                        <th style="border: 3px solid black;" width="18">Harga Master</th>
                        <th style="border: 3px solid black; text-align:center;" width="10" class="text-center">Jumlah
                        </th>
                        <th style="border: 3px solid black; text-align:center;" width="18">Total</th>
                        <th style="border: 3px solid black; text-align:center;" width="14">Tanggal Adjust</th>
                        <th style="border: 3px solid black; text-align:center;" width="18">Harga Adjust</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($data))
                        @forelse ($data as $key => $tagihanitem)
                            <tr>
                                <td style="border: 3px solid black; " class="text-center nomor_tagihan"
                                    data-index="{{ $key + 1 }}">
                                    {{ $key + 1 }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $tagihanitem->no_pekerjaan }}
                                </td>
                                <td style="border: 3px solid black;">
                                    {{ $tagihanitem->uraian }}
                                </td>
                                <td style="border: 3px solid black;">
                                    {{ $tagihanitem->master }}
                                </td>

                                <td style="border: 3px solid black;"
                                    id="jenis_{{ $tagihanitem->id }} text-align:center;">
                                    {{ $tagihanitem->item_jenis }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $tagihanitem->jenis_harga }}
                                </td>
                                <td style="border: 3px solid black;">
                                    {{ $tagihanitem->harga_uraian }}
                                </td>
                                <td style="border: 3px solid black;">
                                    {{ $tagihanitem->harga_master }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;"
                                    id="jumlah_{{ $tagihanitem->id }}" class="text-center">
                                    {{ $tagihanitem->jumlah }}
                                </td>
                                <td style="border: 3px solid black;" id="total_tagihan_{{ $tagihanitem->id }}">
                                    {{ $tagihanitem->grand_total }}
                                </td>
                                <td style="border: 3px solid black;" id="tanggal_adjust_{{ $tagihanitem->id }}">

                                    {{ $tagihanitem->tanggal_adjust_indo }}

                                </td>
                                <td style="border: 3px solid black;">

                                    {{ $tagihanitem->total_adjust }}
                                </td>

                            </tr>
                        @empty
                            <tr class="tagihanTidakAda">
                                <td style="border: 3px solid black;" colspan="10">Data tagihan tidak ada</td>
                            </tr>
                        @endforelse
                    @else
                        <tr class="tagihanTidakAda">
                            <td style="border: 3px solid black;" colspan="10">Data tagihan tidak ada</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    @if (isset($data))
                        <tr>
                            <th style="border: 3px solid black;" colspan="8" class="text-right">Grand Total
                            </th>
                            <th style="border: 3px solid black;" class="text-center">
                                {{ $data->sum('jumlah') }}
                            </th>
                            <th style="border: 3px solid black;">
                                <span id="grand_total_tagihan_tampil">
                                    Rp.
                                    {{ $data->sum('grand_total') }}
                                </span>
                                <input type="hidden" id="grand_total_tagihan_value" name="grand_total_tagihan"
                                    value="{{ $data->sum('grand_total') }}" class="grand_total_tagihan">
                            </th>

                        </tr>
                    @endif
                </tfoot>

            </table>
        </div>
        <div class="card-footer clearfix">
            <button type="button" class="btn btn-success">Export</button>
        </div>
    </div>
    <!-- ./col -->
</div>
