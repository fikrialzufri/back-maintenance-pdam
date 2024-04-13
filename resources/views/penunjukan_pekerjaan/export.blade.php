

<div class="col-md-12">
    <div class="card">
        <!-- /.card-header -->
        <div class="card-body">



            @foreach ($data  as $key => $pekerjaan)
                <table style="border: 3px solid black;" class="table table-bordered table-responsive" width="100%">
                    <thead>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black; text-align:left;">No Urut Spk</th>

                            <th width="50" colspan="5" style="border: 3px solid black; text-align:left;">{{$key+1}}</th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nomor SPK</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->no_spk}}</b></th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nomor Tiket</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->no_tiket}}</b></th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Keterangan</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->keterangan_aduan}}</b></th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Lokasi Pekerjaan</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->lokasi}}</b></th>

                        </tr>
                        <tr>
                            <th width="15" colspan="2" style="border: 3px solid black;">Nama Pekerja</th>

                            <th width="50" colspan="5" style="border: 3px solid black;">
                                <b>{{$pekerjaan->rekanan}}</b></th>

                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th></th>
                        </tr>
                        <tr>
                            <th width="50" style="border: 3px solid black; text-align:center;">Pekerjaan</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Jenis</th>
                            <th width="35" style="border: 3px solid black; text-align:center;">Pengguna</th>
                            <th style="border: 3px solid black; text-align:center;">Jumlah</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Harga Satuan</th>
                            <th width="50" style="border: 3px solid black; text-align:center;">Keterangan</th>
                            <th width="25" style="border: 3px solid black; text-align:center;">Total</th>
                        </tr>
                    </thead>
                    <tbody>

                        @forelse ($pekerjaan->hasItem as $key => $item)
                            <tr>
                                <td style="border: 3px solid black; vertical-align: middle;" rowspan="5">{{$item->nama}}</td>
                                <td style="border: 3px solid black; vertical-align: middle;" rowspan="5">{{$item->jenis}}</td>
                                <td style="border: 3px solid black; ">Rekanan</td>
                                <td style="border: 3px solid black; text-align:center;">{{$item->pivot->qty}}</td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.{{ format_uang($item->pivot->qty ) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">{{$item->pivot->keterangan}}</td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.{{ format_uang($item->pivot->qty * $item->pivot->harga) }}
                                </td>
                            </tr>
                            <tr>
                                <td style="border: 3px solid black; ">Pengawas</td>
                                @if (isset($pekerjaan->hasItemPengawas[$key]))
                                    <td style="border: 3px solid black; text-align:center;">
                                        {{ $pekerjaan->hasItemPengawas[$key]->pivot->qty }}
                                    </td>

                                    <td style="border: 3px solid black; text-align:center;">
                                        Rp.
                                        {{ format_uang(
                                        $pekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                    </td>
                                    <td style="border: 3px solid black; text-align:center;">
                                        {{ $pekerjaan->hasItemPengawas[$key]->pivot->keterangan }}
                                    </td>
                                    <td style="border: 3px solid black; text-align:center;">
                                        Rp.
                                        {{ format_uang($pekerjaan->hasItemPengawas[$key]->pivot->qty *
                                        $pekerjaan->hasItemPengawas[$key]->pivot->harga) }}
                                    </td>
                                @else
                                    <td style="border: 3px solid black; text-align:center;">-</td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                    <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                            <tr>
                                <td style="border: 3px solid black; ">Asisten Manajer Pengawas</td>
                                @if (isset($pekerjaan->hasItemAsmenPengawas[$key]))
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty }}
                                </td>

                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang(
                                    $pekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemAsmenPengawas[$key]->pivot->keterangan }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang($pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty *
                                    $pekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                </td>
                                @else
                                <td style="border: 3px solid black; text-align:center;">-</td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                            <tr>
                                <td style="border: 3px solid black; vertical-align: middle;" rowspan="2">Perencanaan</td>
                                @if (isset($pekerjaan->hasItemPerencanaan[$key]))
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty }}
                                </td>

                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang(
                                    $pekerjaan->hasItemAsmenPengawas[$key]->pivot->harga) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemPerencanaan[$key]->pivot->keterangan }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang($pekerjaan->hasItemAsmenPengawas[$key]->pivot->qty *
                                    $pekerjaan->hasItemPerencanaan[$key]->pivot->harga) }}
                                </td>
                                @else
                                <td style="border: 3px solid black; text-align:center;">-</td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                            <tr>
                                {{-- <td style="border: 3px solid black; text-align:center;">Perencanaan</td> --}}
                                @if (isset($pekerjaan->hasItemPerencanaanAdujst[$key]))
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->qty }}
                                </td>

                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang(
                                    $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->harga) }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    {{ $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->keterangan }}
                                </td>
                                <td style="border: 3px solid black; text-align:center;">
                                    Rp.
                                    {{ format_uang($pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->qty *
                                    $pekerjaan->hasItemPerencanaanAdujst[$key]->pivot->harga) }}
                                </td>
                                @else
                                <td style="border: 3px solid black; text-align:center;">

                                -</td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                <td style="border: 3px solid black; text-align:center;"></td>
                                @endif
                            </tr>
                        @empty

                        @endforelse

                    </tbody>

                </table>
            @endforeach

        </div>
    </div>
    <!-- ./col -->
</div>

