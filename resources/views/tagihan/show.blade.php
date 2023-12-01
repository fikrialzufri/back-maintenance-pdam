@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@push('head')
    <link rel="stylesheet" href="{{ asset('plugins/mohithg-switchery/dist/switchery.min.css') }}">
@endpush
@section('content')`
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->


        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <!-- /.card-header -->
                    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST" role="form"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        {{ method_field('PUT') }}
                        <div class="card-body">
                            <page id="content" size="A4">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="col-12">
                                            <h6>Detail Tagihan</h6>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="NoTagihan" class=" form-control-label">Nomor Tagihan
                                                    </label>
                                                </div>
                                                <div>
                                                    <input type="text" name="no_tagihan" id="No Tagihan"
                                                        placeholder="No Tagihan " class="form-control" readonly
                                                        value="{{ $nomor_tagihan }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="NoTagihan" class=" form-control-label">Total Lokasi</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="no_tagihan" id="No Tagihan"
                                                        placeholder="No Tagihan " class="form-control" readonly
                                                        value="{{ $total_lokasi }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="NoTagihan" class=" form-control-label">Tanggal
                                                        Tagihan</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="no_tagihan" id="No Tagihan"
                                                        placeholder="No Tagihan " class="form-control" readonly
                                                        value="{{ $tanggal_tagihan }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="rekanan" class=" form-control-label">Rekanan</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="rekanan" id="rekanan"
                                                        placeholder="Rekanan " class="form-control" readonly
                                                        value="{{ $rekanan }}">
                                                </div>
                                            </div>
                                        </div>

                                        {{-- nomor hp rekanan --}}
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="rekanan" class=" form-control-label">Hp Rekanan</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="no_hp_rekanan" id="no_hp_rekanan"
                                                        placeholder="no_hp_rekanan " class="form-control" readonly
                                                        value="{{ $tagihan->no_hp_rekanan }}">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="total_tagihan" class=" form-control-label">Total
                                                        Tagihan</label>
                                                </div>
                                                <div class="input-group mb-2 mr-sm-2">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">Rp.</div>
                                                    </div>
                                                    <input type="text" name="total_tagihan" id="total_tagihan_all"
                                                        placeholder="" class="form-control" readonly
                                                        value="{{ pembulatan($total) }}">
                                                </div>
                                            </div>
                                        </div>
                                        @if ($pkp == 'ya')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="ppn" class=" form-control-label">PPN 11%</label>
                                                    </div>
                                                    <div class="input-group mb-2 mr-sm-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">Rp.</div>
                                                        </div>
                                                        <input type="text" name="ppn" id="ppn_all" placeholder=""
                                                            class="form-control" readonly value="{{ format_uang($ppn) }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="grand_total" class=" form-control-label">Grandtotal
                                                            Tagihan</label>
                                                    </div>
                                                    <div class="input-group mb-2 mr-sm-2">
                                                        <div class="input-group-prepend">
                                                            <div class="input-group-text">Rp.</div>
                                                        </div>
                                                        <input type="text" name="grand_total" id="grand_total_all"
                                                            placeholder="" class="form-control" readonly
                                                            value="{{ format_uang($grand_total) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if ($tagihan->kode_vocher != '')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="voucher" class=" form-control-label">Nomor
                                                            Voucher</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" placeholder="voucher " class="form-control"
                                                            readonly value="{{ $tagihan->kode_vocher }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="voucher" class=" form-control-label">Tanggal
                                                            Voucher</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" placeholder="Tanggal Voucher "
                                                            class="form-control" readonly
                                                            value="{{ $tagihan->tanggal_vourcher }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="Bayar" class=" form-control-label">Tanggal
                                                            Bayar</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" placeholder="Tanggal Bayar "
                                                            class="form-control" readonly
                                                            value="{{ $tagihan->tanggal_bayar }}">
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="total_bayar" class=" form-control-label">Total
                                                            Bayar</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" placeholder="total_bayar "
                                                            class="form-control" readonly
                                                            value="Rp. {{ format_uang($tagihan->total_bayar) }}">
                                                    </div>
                                                </div>
                                            </div> --}}
                                        @endif
                                        {{-- @if ($tagihan->kode_anggaran != '')
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="kode_anggaran" class=" form-control-label">Kode
                                                            Anggaran</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" placeholder="kode_anggaran"
                                                            class="form-control" readonly
                                                            value="{{ $tagihan->kode_anggaran }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endif --}}
                                    </div>

                                    <div class="col-6 timeline">
                                        <h6>List Persetujuan Tagihan</h6>
                                        <ul>
                                            @forelse ($list_persetujuan as $item)
                                                @if ($item->jabatan == 'Direktur Teknik')
                                                    <li>
                                                        <div class="bullet bg-primary"></div>
                                                        <div class="time">{{ $item->tanggal_disetujui }}</div>
                                                        <div class="desc">
                                                            <h3 class="persetujuan_jabatan">Plt. {{ $item->jabatan }}</h3>
                                                            <h4 class="persetujuan_nama">{{ $item->nama }}</h4>
                                                        </div>
                                                    </li>
                                                @else
                                                    <li>
                                                        <div class="bullet bg-primary"></div>
                                                        <div class="time">{{ $item->tanggal_disetujui }}</div>
                                                        <div class="desc">
                                                            <h3 class="persetujuan_jabatan">{{ $item->jabatan }}</h3>
                                                            <h4 class="persetujuan_nama">{{ $item->nama }}</h4>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                            @endforelse


                                        </ul>
                                    </div>



                                </div>

                                <hr>
                                @if (isset($tagihan->hasPelaksanaanPekerjaan))

                                    @if (auth()->user()->hasRole('asisten-manajer-tata-usaha') ||
                                            auth()->user()->hasRole('manajer-umum-dan-kesekretariatan') ||
                                            auth()->user()->hasRole('direktur-umum') ||
                                            auth()->user()->hasRole('direktur-utama') ||
                                            auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') ||
                                            auth()->user()->hasRole('asisten-manajer-akuntansi') ||
                                            auth()->user()->hasRole('manajer-keuangan'))
                                        <div>
                                            <label for="rekanan" class=" form-control-label">
                                                <h3>Detail Pekerjaan {{ $tagihan->status }}</h3>
                                                </h3>
                                            </label>

                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="table table-bordered " width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th width="5">#</th>
                                                            <th>SPK</th>
                                                            <th>Total Harga</th>
                                                            @if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') ||
                                                                    auth()->user()->hasRole('direktur-umum') ||
                                                                    auth()->user()->hasRole('direktur-utama') ||
                                                                    auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') ||
                                                                    auth()->user()->hasRole('asisten-manajer-akuntansi') ||
                                                                    auth()->user()->hasRole('manajer-keuangan'))

                                                                <th>
                                                                    Kode Anggaran
                                                                </th>
                                                            @endif

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($tagihan->hasPelaksanaanPekerjaan as $index => $item)
                                                            <tr>
                                                                <td>{{ $index + 1 }}</td>
                                                                <td><a href="{{ route('penunjukan_pekerjaan.show', $item->hasAduan->slug) }}"
                                                                        target="_blank"> {{ $item->No_Spk }} </a></td>
                                                                <th>
                                                                    Rp. {{ format_uang($item->total_pekerjaan) }}
                                                                </th>
                                                                @if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') && $tagihan->status === 'disetujui dirut')
                                                                    <th>
                                                                        {{-- <input type="text" name="kode_anggaran[]"
                                                                            value="" placeholder="Kode Anggaran"
                                                                            class="form-control" required> --}}

                                                                        <select name="kode_anggaran[]"
                                                                            class="form-control" required>
                                                                            <option value="">Pilih Kode Anggaran
                                                                            </option>
                                                                            <option value="31.05.30">31.05.30</option>
                                                                            <option value="93.02.30">93.02.30</option>
                                                                            <option value="93.02.40">93.02.40</option>
                                                                        </select>
                                                                        <input type="hidden"
                                                                            name="pelaksanaan_pekerjaan_id[]"
                                                                            value="{{ $item->id }}">
                                                                    </th>
                                                                @else
                                                                    @if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') ||
                                                                            auth()->user()->hasRole('direktur-umum') ||
                                                                            auth()->user()->hasRole('direktur-utama') ||
                                                                            auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') ||
                                                                            auth()->user()->hasRole('asisten-manajer-akuntansi') ||
                                                                            auth()->user()->hasRole('manajer-keuangan'))
                                                                        <th>
                                                                            {{ $item->kode_anggaran }}
                                                                        </th>
                                                                    @endif
                                                                @endif
                                                            </tr>
                                                        @empty
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @else
                                        @foreach ($tagihan->hasPelaksanaanPekerjaan as $key => $item)
                                            <div>
                                                <label for="rekanan" class=" form-control-label">
                                                    <h3>Pekerjaan : <a
                                                            href="{{ route('penunjukan_pekerjaan.show', $item->hasAduan->slug) }}"
                                                            target="_blank"> {{ $item->No_Spk }} </a>
                                                    </h3>
                                                </label>

                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div>
                                                        <span>Daftar Pekerjaan</span>
                                                        <table class="table table-bordered " width="100%">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5">#</th>
                                                                    <th>Nama</th>
                                                                    <th>Jenis Pekerjaan</th>
                                                                    <th width="10">Volume</th>
                                                                    <th>Harga Satuan </th>

                                                                    <th>Total Harga</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @forelse ($item->hasItem as $nomor => $barang)
                                                                    <tr id="{{ $barang->slug }}_{{ $item->id }}"
                                                                        @if (isset($item->hasItemPerencanaanAdujst[$nomor]) && isset($item->hasItemAsmenPengawas[$nomor])) @if ($item->hasItemPerencanaanAdujst[$nomor]->volume != $item->hasItemAsmenPengawas[$nomor]->volume)
                                                                                class="table-danger"
                                                                                {{ $item->hasItemPerencanaanAdujst[$nomor]->pivot->qty }}
                                                                                {{ $item->hasItemAsmenPengawas[$nomor]->pivot->qty }}
                                                                                {{ $item->hasItemPerencanaan[$nomor]->pivot->harga }}
                                                                                {{ $item->hasItemPerencanaanAdujst[$nomor]->pivot->harga }}
                                                                            @elseif ($item->hasItemPerencanaanAdujst[$nomor]->pivot->harga != $item->hasItemAsmenPengawas[$nomor]->pivot->harga)
                                                                                class="table-danger" @endif
                                                                        @endif
                                                                        >
                                                                        <td>{{ $nomor + 1 }}
                                                                        </td>
                                                                        <td>{{ $barang->nama }}</td>
                                                                        <td>{{ $barang->jenis }}
                                                                        </td>

                                                                        @if ($item->status === 'diadjust')
                                                                            @if (isset($item->hasItemPerencanaanAdujst[$nomor]))
                                                                                <td>{{ $item->hasItemPerencanaanAdujst[$nomor]->pivot->qty }}
                                                                                </td>
                                                                                <td>
                                                                                    Rp.
                                                                                    {{ format_uang($item->hasItemPerencanaanAdujst[$nomor]->pivot->harga) }}
                                                                                </td>
                                                                            @else
                                                                                @if (isset($item->hasItemAsmenPengawas[$nomor]))
                                                                                    <td>{{ round($item->hasItemAsmenPengawas[$nomor]->pivot->qty, 3) }}
                                                                                    </td>
                                                                                    @if (isset($item->hasItemPerencanaan[$nomor]))
                                                                                        <td> Rp.
                                                                                            {{ format_uang($item->hasItemPerencanaan[$nomor]->pivot->harga) }}
                                                                                        </td>
                                                                                    @endif
                                                                                @else
                                                                                    <td>{{ round($barang->pivot->qty, 3) }}
                                                                                    </td>
                                                                                    <td>Rp.
                                                                                        {{ format_uang($barang->pivot->harga) }}
                                                                                    </td>
                                                                                @endif
                                                                            @endif
                                                                        @else
                                                                            @if (isset($item->hasItemAsmenPengawas[$nomor]))
                                                                                <td>{{ round($item->hasItemAsmenPengawas[$nomor]->pivot->qty, 3) }}
                                                                                </td>
                                                                                @if (isset($item->hasItemPerencanaan[$nomor]))
                                                                                    <td> Rp.
                                                                                        {{ format_uang($item->hasItemPerencanaan[$nomor]->pivot->harga) }}
                                                                                    </td>
                                                                                @endif
                                                                            @endif
                                                                        @endif

                                                                        <td>
                                                                            Rp.
                                                                            {{ format_uang($barang->pivot->total) }}
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
                                                                        <th colspan="3" class="text-right">Total
                                                                        </th>
                                                                        @if ($item->status === 'diadjust')
                                                                            <th>{{ round($item->hasItemPerencanaanAdujst->sum('pivot.qty'), 3) }}
                                                                            </th>
                                                                        @else
                                                                            <th>{{ round($item->hasItemAsmenPengawas->sum('pivot.qty'), 3) }}
                                                                            </th>
                                                                        @endif
                                                                        <th></th>
                                                                        <th>Rp.
                                                                            {{ format_uang($item->hasItem()->sum('total')) }}
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>

                                                            </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div>

                                                            <table class="table table-bordered " width="100%">
                                                                <thead>
                                                                    <tr>
                                                                        <th width="5">#</th>
                                                                        <th width="700">Galian</th>
                                                                        <th width="10">Panjang</th>
                                                                        <th width="10">Lebar</th>
                                                                        <th width="10">Dalam</th>
                                                                        <th width="10">Volume</th>
                                                                        <th width="200">Harga Satuan</th>
                                                                        <th width="250">Total Harga</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>

                                                                    @forelse ($item->hasGalianPekerjaan as $key => $galian)
                                                                        <tr
                                                                            @if ($item->status === 'diadjust') class="{{ $galian->volume_adjust != $galian->volume_asmen ? 'table-danger' : '' }} {{ $galian->galian_perencanaan_harga_satuan != $galian->galian_perencanaan_adjust_harga_satuan ? 'table-danger' : '' }}" @endif>
                                                                            <td>{{ $key + 1 }}
                                                                            </td>
                                                                            <td>
                                                                                {{ $galian->pekerjaan }}
                                                                            </td>

                                                                            @if ($item->status === 'diadjust')
                                                                                <td>{{ $galian->galian_perencanaan_adjust_panjang }}
                                                                                    m
                                                                                </td>
                                                                                <td>{{ $galian->galian_perencanaan_adjust_lebar }}
                                                                                    m
                                                                                </td>
                                                                                <td>{{ $galian->galian_perencanaan_adjust_dalam }}
                                                                                    m
                                                                                </td>
                                                                                <td>{{ round($galian->volume_adjust, 3) }}
                                                                                    m<sup>2
                                                                                </td>
                                                                                <td>Rp.
                                                                                    {{ format_uang($galian->galian_perencanaan_adjust_harga_satuan) }}
                                                                                </td>
                                                                            @else
                                                                                <td>
                                                                                    {{ $galian->galian_asmen_pengawas_panjang }}
                                                                                    m
                                                                                </td>
                                                                                <td>
                                                                                    {{ $galian->galian_asmen_pengawas_lebar }}
                                                                                    m
                                                                                </td>
                                                                                <td>
                                                                                    {{ $galian->galian_asmen_pengawas_dalam }}
                                                                                    m
                                                                                </td>
                                                                                <td>
                                                                                    {{ round($galian->volume_asmen, 3) }}
                                                                                    m<sup>2</sup>
                                                                                </td>
                                                                                <td>
                                                                                    Rp.
                                                                                    {{ format_uang($galian->galian_perencanaan_harga_satuan) }}
                                                                                </td>
                                                                            @endif


                                                                            </td>
                                                                            <td>Rp. {{ format_uang($galian->total) }}</td>

                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="10">Data Item Galian ada</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th colspan="7" class="text-right"> Total
                                                                        </th>

                                                                        {{-- @if ($item->status === 'diadjust')
                                                                <th>
                                                                    {{ $item->total_volume_galian }}
                                                                    m<sup>2</sup>
                                                                </th>
                                                            @else
                                                                <th>
                                                                    {{ $item->volume }} m<sup>2</sup>
                                                                </th>
                                                            @endif
                                                            <th></th> --}}
                                                                        <th>Rp.
                                                                            {{ format_uang($item->hasGalianPekerjaan->sum('total')) }}
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th colspan="7" class="text-right">
                                                                            Grand Total
                                                                        </th>
                                                                        <th>Rp.
                                                                            {{ format_uang($item->total_pekerjaan) }}
                                                                        </th>
                                                                    </tr>
                                                                </tfoot>

                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    @endif
                                    <div class="card-body p-0 table-border-style">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>

                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>
                                                            <label for="rekanan" class=" form-control-label">
                                                                <h4>Total Tagihan : Rp. {{ pembulatan($total) }} </h4>
                                                            </label>
                                                        </th>
                                                    </tr>
                                                    @if ($pkp == 'ya')
                                                        <tr>
                                                            <th>
                                                                <label for="rekanan" class=" form-control-label">
                                                                    <h4>PPN 11% : Rp. {{ format_uang($ppn) }} </h4>
                                                                </label>
                                                            </th>
                                                        </tr>
                                                        <tr>
                                                            <th>
                                                                <label for="rekanan" class=" form-control-label">
                                                                    <h4>Grandtotal Tagihan : Rp.
                                                                        {{ format_uang($grand_total) }}
                                                                    </h4>
                                                                </label>
                                                            </th>
                                                        </tr>
                                                    @endif

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </page>


                                @if (!auth()->user()->hasRole('rekanan'))
                                    @if (auth()->user()->hasRole('asisten-manajer-tata-usaha'))
                                        {{-- CheckBok Rekanan --}}
                                        <input type="hidden" name="no_kwitansi_check"
                                            value="{{ old('checkbok_no_kwitansi') ? 'ya' : '' }}" id="no_kwitansi_check">
                                        <input type="hidden" name="no_kwitansi_rekanan" value="{{ $tagihan->no_kwitansi }}"
                                            id="no_kwitansi_rekanan">
                                        <input type="hidden" name="no_faktur_pajak_check"
                                            value="{{ old('checkbok_no_faktur_pajak') ? 'ya' : '' }}"
                                            id="no_faktur_pajak_check">
                                        <input type="hidden" name="no_faktur_pajak_rekanan"
                                            value="{{ $tagihan->no_faktur_pajak }}" id="no_faktur_pajak_rekanan">
                                        <input type="hidden" name="e_billing_check"
                                            value="{{ old('checkbok_e_billing_check') ? 'ya' : '' }}" id="e_billing_check">
                                        <input type="hidden" name="e_billing_rekanan" value="{{ $tagihan->e_billing }}"
                                            id="e_billing_rekanan">
                                        <input type="hidden" name="bukti_pembayaran_check"
                                            value="{{ old('checkbok_bukti_pembayaran') ? 'ya' : '' }}"
                                            id="bukti_pembayaran_check">
                                        <input type="hidden" name="bukti_pembayaran_rekanan"
                                            value="{{ $tagihan->bukti_pembayaran }}" id="bukti_pembayaran_rekanan">
                                        <input type="hidden" name="e_spt_check"
                                            value="{{ old('checkbok_e_spt') ? 'ya' : '' }}" id="e_spt_check">
                                        <input type="hidden" name="e_spt_rekanan" value="{{ $tagihan->e_spt }}"
                                            id="e_spt_rekanan">

                                    @endif


                                    @if (auth()->user()->hasRole('asisten-manajer-akuntansi') && $tagihan->status === 'disetujui asmenanggaran')
                                        <div class="row mb-5">
                                            <div class="col-12">
                                                <div>
                                                    <label for="kode_voucher" class=" form-control-label">Nomor
                                                        Voucher</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="kode_voucher" placeholder="Nomor Voucher"
                                                        class="form-control  {{ $errors->has('kode_voucher') ? 'form-control is-invalid' : 'form-control' }}"
                                                        value="{{ old('kode_voucher') }}" required>
                                                </div>

                                                @if ($errors->has('kode_voucher'))
                                                    <span class="text-danger">
                                                        <strong id="textkk">{{ $errors->first('kode_voucher') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    @if ($bntSetuju === false)
                                        <div class="row">
                                            <div class="col-12">

                                                <div class="d-flex flex-row">
                                                    <div class="p-2">
                                                        <button class="btn btn-primary" id="word-export" type="button"><span
                                                                class="nav-icon fa fa-file-pdf" aria-hidden="true"></span>
                                                            Print Detail Tagihan</button>
                                                    </div>
                                                    <div class="p-2">
                                                        @if ($keuangan === true)
                                                            <button type="submit" class="btn btn-primary">Simpan
                                                                Pembayaran</button>
                                                        @else
                                                            <button type="submit" class="btn btn-primary" id="btn_setujui"
                                                                @if (auth()->user()->hasRole('asisten-manajer-tata-usaha')) disabled @endif>Setujui
                                                                Tagihan</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <br>
                                        <br>
                                    @endif
                                @endif



                                <!-- /.card-body -->
                                <div class="card-footer clearfix">
                                    <div class="row">
                                        @if ($tagihan)
                                            <div class="col-12">
                                                <div class="d-flex flex-row">

                                                    <div class="p-2">
                                                        <a href="{{ route('tagihan.word') }}?id={{ $tagihan->id }}&word=rekanan"
                                                            target="_blank" class="btn btn-primary"><span
                                                                class="nav-icon fa fa-file-word" aria-hidden="true"></span>
                                                            Privew Tagihan</a>
                                                    </div>

                                                    @if (!auth()->user()->hasRole('rekanan'))
                                                        <div class="p-2">
                                                            @if (
                                                                $tagihan->status === 'disetujui' ||
                                                                    $tagihan->status === 'dibayar' ||
                                                                    ($tagihan->status === 'disetujui dirut' ||
                                                                        $tagihan->status === 'disetujui asmentu' ||
                                                                        $tagihan->status === 'disetujui mu' ||
                                                                        $tagihan->status === 'disetujui dirum' ||
                                                                        $tagihan->status === 'disetujui dirut' ||
                                                                        $tagihan->status === 'disetujui asmenakuntan' ||
                                                                        $tagihan->status === 'disetujui asmenanggaran' ||
                                                                        $tagihan->status === 'disetujui mankeu'))
                                                                <a href="{{ route('tagihan.word') }}?id={{ $tagihan->id }}"
                                                                    target="_blank" class="btn btn-success"><span
                                                                        class="nav-icon fa fa-file-word"
                                                                        aria-hidden="true"></span>
                                                                    Privew Tagihan BAPP</a>
                                                            @endif
                                                        </div>
                                                        <div class="p-2">
                                                            {{-- @if ($tagihan->status === 'disetujui dirut') --}}
                                                            @if (
                                                                $tagihan->status === 'disetujui' ||
                                                                    $tagihan->status === 'dibayar' ||
                                                                    ($tagihan->status === 'disetujui dirut' ||
                                                                        $tagihan->status === 'disetujui asmentu' ||
                                                                        $tagihan->status === 'disetujui mu' ||
                                                                        $tagihan->status === 'disetujui dirum' ||
                                                                        $tagihan->status === 'disetujui dirut' ||
                                                                        $tagihan->status === 'disetujui asmenakuntan' ||
                                                                        $tagihan->status === 'disetujui asmenanggaran' ||
                                                                        $tagihan->status === 'disetujui mankeu'))
                                                                <a href="{{ route('tagihan.word') }}?id={{ $tagihan->id }}&word=bapp"
                                                                    target="_blank" class="btn btn-success"><span
                                                                        class="nav-icon fa fa-file-word"
                                                                        aria-hidden="true"></span>
                                                                    Privew Tagihan BAPP</a>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                    <div class="row mt-10">
                                        @if ($ktp != null)

                                            <div class="col-md-3">
                                                <div class="card">
                                                    <!-- /.card-header -->

                                                    <div class="card-body">
                                                        <h1>KTP Rekanaan</h1>
                                                        {{-- show image --}}
                                                        <img src="{{ asset('storage/rekanan/' . $ktp) }}"
                                                            alt="{{ $rekanan }}." class="card-img-top img-thumbnail">

                                                    </div>

                                                    <!-- /.card-body -->
                                                    <div class="card-footer clearfix">

                                                    </div>

                                                </div>
                                                <!-- ./col -->

                                            </div>
                                        @endif
                                        @if ($npwp != null)

                                            <div class="col-md-3">
                                                <div class="card">
                                                    <!-- /.card-header -->

                                                    <div class="card-body">
                                                        <h1>NPWP Rekanaan</h1>
                                                        {{-- show image --}}
                                                        <img src="{{ asset('storage/rekanan/' . $npwp) }}"
                                                            alt="{{ $rekanan }}." class="card-img-top float-start">

                                                    </div>

                                                    <!-- /.card-body -->
                                                    <div class="card-footer clearfix">

                                                    </div>

                                                </div>
                                                <!-- ./col -->

                                            </div>
                                        @endif
                                    </div>
                                    @if ($tagihan->status != 'disetujui' || $tagihan->status != 'dikirim' || $tagihan->status != 'disetujui')
                                        <div class="row">
                                            <div class="col-12">

                                                <h6>Dokumen Pembayaran</h6>
                                            </div>
                                            <div class="col-7">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="no_kwitansi_view" class=" form-control-label">Kwitansi
                                                            Tagihan
                                                        </label>
                                                    </div>
                                                    <div>

                                                        <div class="input-group input-group-button">
                                                            <div class="input-group-prepend">
                                                                <a class="btn btn-primary"
                                                                    href="{{ asset('storage/tagihan/' . $tagihan->no_kwitansi_image) }}"
                                                                    target="_blank">
                                                                    <i class="ik ik-arrow-down"></i> Download Kwitansi
                                                                </a>
                                                            </div>
                                                            <input type="text" class="form-control" placeholder=""
                                                                value="{{ $tagihan->no_kwitansi }}" readonly>
                                                            <div class="input-group-prepend">
                                                                <div class="input-group-text">
                                                                    <input type="checkbox" aria-label="Persyaratan Sesuai"
                                                                        id="checkbok_no_kwitansi"
                                                                        @if (!auth()->user()->hasRole('asisten-manajer-tata-usaha')) onclick="return false;" @endif
                                                                        name="checkbok_no_kwitansi"
                                                                        {{ $tagihan->no_kwitansi_check == 'ya' ? 'checked' : '' }}>
                                                                    <span class="pl-2">Persyaratan Sesuai
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="">
                                                            @if ($errors->has('no_kwitansi_rekanan'))
                                                                <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                    role="alert">
                                                                    Kwitansi tagihan tidak boleh kosong
                                                                    <button type="button" class="close"
                                                                        data-dismiss="alert" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @if ($pkp == 'ya')

                                                    <div class="form-group">
                                                        <div>
                                                            <label for="no_faktur_view" class=" form-control-label">No Faktur
                                                                Pajak</label>
                                                        </div>
                                                        <div>

                                                            <div class="input-group input-group-button">
                                                                <div class="input-group-prepend">
                                                                    <a class="btn btn-primary"
                                                                        href="{{ asset('storage/tagihan/' . $tagihan->no_faktur_pajak_image) }}"
                                                                        target="_blank">
                                                                        <i class="ik ik-arrow-down"></i> Download Faktur
                                                                        Pajak
                                                                    </a>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder=""
                                                                    value="{{ $tagihan->no_faktur_pajak }}" readonly>
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <input type="checkbox" aria-label="Persyaratan Sesuai"
                                                                            id="checkbok_no_faktur_pajak"
                                                                            @if (!auth()->user()->hasRole('asisten-manajer-tata-usaha')) onclick="return false;" @endif
                                                                            name="checkbok_no_faktur_pajak"
                                                                            {{ old('checkbok_no_faktur_pajak') ? 'checked' : '' }}
                                                                            {{ $tagihan->no_faktur_pajak_check == 'ya' ? 'checked' : '' }}>
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                @if ($errors->has('no_faktur_pajak_rekanan'))
                                                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                        role="alert">
                                                                        Faktur Pajak tidak boleh kosong
                                                                        <button type="button" class="close"
                                                                            data-dismiss="alert" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div>
                                                            <label for="e_billing_view"
                                                                class=" form-control-label">E-Billing</label>
                                                        </div>
                                                        <div>

                                                            <div class="input-group input-group-button">
                                                                <div class="input-group-prepend">
                                                                    <a class="btn btn-success"
                                                                        href="{{ asset('storage/tagihan/' . $tagihan->e_billing_image) }}"
                                                                        target="_blank">
                                                                        <i class="ik ik-arrow-down"></i> Download E-Billing
                                                                    </a>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder=""
                                                                    value="{{ $tagihan->e_billing }}" readonly>
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <input type="checkbox" aria-label="Persyaratan Sesuai"
                                                                            id="checkbok_e_billing"
                                                                            @if (!auth()->user()->hasRole('asisten-manajer-tata-usaha')) onclick="return false;" @endif
                                                                            name="checkbok_e_billing"
                                                                            {{ old('checkbok_e_billing') ? 'checked' : '' }}
                                                                            {{ $tagihan->e_billing_check == 'ya' ? 'checked' : '' }}>
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                @if ($errors->has('e_billing_rekanan'))
                                                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                        role="alert">
                                                                        E-Billing tidak boleh kosong
                                                                        <button type="button" class="close"
                                                                            data-dismiss="alert" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div>
                                                            <label for="bukti_pembayaran_view"
                                                                class=" form-control-label">Bukti
                                                                Pembayaraan
                                                                PPN atas tagihan</label>
                                                        </div>
                                                        <div>

                                                            <div class="input-group input-group-button">
                                                                <div class="input-group-prepend">
                                                                    <a class="btn btn-danger"
                                                                        href="{{ asset('storage/tagihan/' . $tagihan->bukti_pembayaran_image) }}"
                                                                        target="_blank">
                                                                        <i class="ik ik-arrow-down"></i> Download Bukti
                                                                        Pembayaraan
                                                                    </a>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder=""
                                                                    value="{{ $tagihan->bukti_pembayaran }}" readonly>
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <input type="checkbox" aria-label="Persyaratan Sesuai"
                                                                            id="checkbok_bukti_pembayaran"
                                                                            @if (!auth()->user()->hasRole('asisten-manajer-tata-usaha')) onclick="return false;" @endif
                                                                            name="checkbok_bukti_pembayaran"
                                                                            {{ old('checkbok_bukti_pembayaran') ? 'checked' : '' }}
                                                                            {{ $tagihan->bukti_pembayaran_check == 'ya' ? 'checked' : '' }}>
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                @if ($errors->has('bukti_pembayaran_rekanan'))
                                                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                        role="alert">
                                                                        Bukti Pembayaran tidak boleh kosong
                                                                        <button type="button" class="close"
                                                                            data-dismiss="alert" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div>
                                                            <label for="e_spt_view" class=" form-control-label">E-SPT
                                                                PPN</label>
                                                        </div>
                                                        <div>

                                                            <div class="input-group input-group-button">
                                                                <div class="input-group-prepend">
                                                                    <a class="btn btn-warning"
                                                                        href="{{ asset('storage/tagihan/' . $tagihan->e_spt_image) }}"
                                                                        target="_blank">
                                                                        <i class="ik ik-arrow-down"></i> Download E-SPT PPN
                                                                    </a>
                                                                </div>
                                                                <input type="text" class="form-control" placeholder=""
                                                                    value="{{ $tagihan->e_spt }}" readonly>
                                                                <div class="input-group-prepend">
                                                                    <div class="input-group-text">
                                                                        <input type="checkbox" aria-label="Persyaratan Sesuai"
                                                                            id="checkbok_e_spt"
                                                                            @if (!auth()->user()->hasRole('asisten-manajer-tata-usaha')) onclick="return false;" @endif
                                                                            name="checkbok_e_spt"
                                                                            {{ old('checkbok_e_spt') ? 'checked' : '' }}
                                                                            {{ $tagihan->e_spt_check == 'ya' ? 'checked' : '' }}>
                                                                        <span class="pl-2">Persyaratan Sesuai
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="">
                                                                @if ($errors->has('e_spt_rekanan'))
                                                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                        role="alert">
                                                                        E-SPT PPN tidak boleh kosong
                                                                        <button type="button" class="close"
                                                                            data-dismiss="alert" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                            <div class="col-12">
                                                <button class="btn btn-success" id="btnkirimwa" type="button">Kirim ke
                                                    Rekanan</button>
                                            </div>
                                        </div>
                                    @endif
                                </div>


                            </div>
                        </form>
                        <!-- ./col -->
                    </div>
                    @if (
                        $tagihan->status == 'disetujui' &&
                            auth()->user()->hasRole('rekanan'))
                        <form action="{{ route('tagihan.dokumen', $tagihan->id) }}" method="POST" role="form"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}
                            <div class="card">
                                {{-- Upload Ebiling --}}
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <h6>Upload Dokument</h6>
                                            <br>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <div>
                                                    <label for="no_kwitansi" class=" form-control-label">No
                                                        Kwitansi Tagihan</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="no_kwitansi" id="no_kwitansi"
                                                        placeholder="Contoh 301123 - Terdiri dari tanggal bulan dan tahun "
                                                        class="form-control" value="{{ $tagihan->no_kwitansi }}">
                                                </div>
                                                @if ($errors->has('no_kwitansi'))
                                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                        role="alert">
                                                        {{ $errors->first('no_kwitansi') }}
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif

                                                <div class="">
                                                    <input type="file" value="no_kwitansi_image" name="no_kwitansi_image"
                                                        placeholder="" id="" class="form-control">
                                                    <br>

                                                    <div id="preview_no_kwitansi_image"></div>
                                                </div>
                                                @if ($errors->has('no_kwitansi_image'))
                                                    <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                        role="alert">
                                                        {{ $errors->first('no_kwitansi_image') }}
                                                        <button type="button" class="close" data-dismiss="alert"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($pkp == 'ya')

                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="no_faktur_pajak" class=" form-control-label">No Faktur
                                                            Pajak</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="no_faktur_pajak" id=" Faktur Pajak"
                                                            placeholder="Faktur Pajak " class="form-control"
                                                            value="{{ $tagihan->no_faktur_pajak }}">
                                                    </div>
                                                    @if ($errors->has('no_faktur_pajak'))
                                                        <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                            role="alert">
                                                            {{ $errors->first('no_faktur_pajak') }}
                                                            <button type="button" class="close" data-dismiss="alert"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                    <div class="">
                                                        <input type="file" value="no_faktur_pajak_image"
                                                            name="no_faktur_pajak_image" placeholder="" id=""
                                                            class="form-control">
                                                        <br>

                                                        <div id="preview_no_faktur_pajak_image"></div>
                                                    </div>
                                                    @if ($errors->has('no_faktur_pajak_image'))
                                                        <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                            role="alert">
                                                            {{ $errors->first('no_faktur_pajak_image') }}
                                                            <button type="button" class="close" data-dismiss="alert"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="e_billing" class=" form-control-label">E-Billing</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="e_billing" id="e_billing"
                                                            placeholder="E-Billing " class="form-control"
                                                            value="{{ $tagihan->e_billing }}">
                                                        @if ($errors->has('e_billing'))
                                                            <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                role="alert">
                                                                {{ $errors->first('e_billing') }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="">
                                                        <input type="file" value="e_billing_image" name="e_billing_image"
                                                            placeholder="" id="e_billing_image" class="form-control">
                                                        <br>
                                                        @if ($errors->has('e_billing_image'))
                                                            <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                role="alert">
                                                                {{ $errors->first('e_billing_image') }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                        <div id="preview_e_billing_image"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="bukti_pembayaran" class=" form-control-label">Bukti
                                                            Pembayaraan
                                                            PPN atas tagihan</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="bukti_pembayaran" id="bukti_pembayaran"
                                                            placeholder="Bukti Pembayaran" placeholder="Bukti Pembayaran "
                                                            class="form-control" value="{{ $tagihan->bukti_pembayaran }}">
                                                        @if ($errors->has('bukti_pembayaran'))
                                                            <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                role="alert">
                                                                {{ $errors->first('bukti_pembayaran') }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="">
                                                        <input type="file" value="bukti_pembayaran_image"
                                                            name="bukti_pembayaran_image" placeholder=""
                                                            id="bukti_pembayaran_image" class="form-control">
                                                        <br>
                                                        @if ($errors->has('bukti_pembayaran_image'))
                                                            <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                role="alert">
                                                                {{ $errors->first('bukti_pembayaran_image') }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                        <div id="preview_bukti_pembayaran_image"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div>
                                                        <label for="e_spt" class=" form-control-label">E-SPT
                                                            PPN</label>
                                                    </div>
                                                    <div>
                                                        <input type="text" name="e_spt" id="e_spt"
                                                            placeholder="e-spt " class="form-control"
                                                            value="{{ $tagihan->e_spt }}">
                                                        @if ($errors->has('e_spt'))
                                                            <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                role="alert">
                                                                {{ $errors->first('e_spt') }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="">
                                                        <input type="file" value="e_spt_image" name="e_spt_image"
                                                            placeholder="" id="e_spt_image" class="form-control">
                                                        <br>
                                                        @if ($errors->has('e_spt_image'))
                                                            <div class=" container-fluid alert alert-warning alert-dismissible fade show"
                                                                role="alert">
                                                                {{ $errors->first('e_spt_image') }}
                                                                <button type="button" class="close" data-dismiss="alert"
                                                                    aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                        @endif
                                                        <div id="preview_e_spt_image"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Upload Dokument Pembayaran</button>
                                </div>
                            </div>
                        </form>
                    @endif
                    @if (!auth()->user()->hasRole('rekanan'))
                        @if (auth()->user()->hasRole('asisten-manajer-tata-usaha'))
                            <form action="{{ route('tagihan.dokumen', $tagihan->id) }}" method="POST" role="form"
                                id="form-kirim-wa">
                                {{ csrf_field() }}
                                {{ method_field('PUT') }}
                                <input type="hidden" name="kirim_wa" value="kirim">
                                <input type="hidden" name="no_kwitansi_kirim" value="" id="no_kwitansi_kirim">

                                <input type="hidden" name="no_faktur_pajak_kirim" value=""
                                    id="no_faktur_pajak_kirim">

                                <input type="hidden" name="e_billing_kirim" value="" id="e_billing_kirim">

                                <input type="hidden" name="bukti_pembayaran_kirim" value=""
                                    id="bukti_pembayaran_kirim">

                                <input type="hidden" name="e_spt_kirim" value="" id="e_spt_kirim">

                            </form>
                        @endif
                    @endif

                    <!-- /.row -->
                    <!-- Main row  dataitem-->
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
            </div><!-- /.container-fluid -->
        </div><!-- /.container-fluid -->

    @stop

    @push('script')
        <style>
            [role=button] {
                cursor: pointer;
            }
        </style>
    @endpush
    @push('script')
        <script>
            $("#total_bayar").on("input", function() {

                let val = formatRupiahTanpaRp(this.value, '')
                $("#total_bayar").val(val)
            });

            function send_handle() {

                let no_hp_rekanan = $('#no_hp_rekanan').val();

                no_hp_rekanan = no_hp_rekanan.replace(/^0+/, '');
                console.log(no_hp_rekanan);
                let namarekanan = $('#rekanan').val();

                let urlTagihan = "{{ route('tagihan.show', $tagihan->id) }}";

                var no_kwitanasi_check = $('#checkbok_no_kwitansi').is(':checked') ? 'ya' : 'tidak';
                var no_faktur_pajak_check = $('#checkbok_no_faktur_pajak').is(':checked') ? 'ya' : 'tidak';
                var e_billing_check = $('#checkbok_e_billing').is(':checked') ? 'ya' : 'tidak';
                var bukti_pembayaran_check = $('#checkbok_bukti_pembayaran').is(':checked') ? 'ya' : 'tidak';
                var e_spt_check = $('#checkbok_e_spt').is(':checked') ? 'ya' : 'tidak';

                let textMessage =
                    `Kepada yang terhormat Bapak/Ibu direktur ${namarekanan} \n\nBerikut kami kirimkan link tagihan ${urlTagihan} \n\nTerima Kasih \n\n
                    Persyaratan yang harus dipenuhi : \n\n\
                    1. Kwitansi Tagihan : ${no_kwitanasi_check} \n\
                    2. Faktur Pajak : ${no_faktur_pajak_check} \n\
                    3. E-Billing : ${e_billing_check} \n\
                    4. Bukti Pembayaran PPN atas tagihan : ${bukti_pembayaran_check} \n\
                    5. E-SPT PPN : ${e_spt_check} \n\
                    `;
                window.open(`https://api.whatsapp.com/send?phone=62${no_hp_rekanan}&text=` + encodeURI(textMessage));
                // win.focus();
            }

            // if session wa makan send wa
            @if (Session::has('wa'))
                console.log("apa");
                send_handle();
            @endif

            function formatRupiahTanpaRp(angka, prefix) {
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
                return prefix == undefined ? rupiah : (rupiah ? '' + rupiah : '');
            }

            function createPdf() {
                var printContents = document.getElementById('content').innerHTML;
                var originalContents = document.body.innerHTML;

                document.body.innerHTML = printContents;

                window.print();

                document.body.innerHTML = originalContents;
            }

            $('#word-export').click(function() {

                $('.desc h3').replaceWith(function() {
                    return $("<span>", {
                        class: this.className,
                        html: $(this).html() + '<br>'
                    });
                });
                $('.desc h4').replaceWith(function() {
                    return $("<span>", {
                        class: this.className,
                        html: $(this).html()
                    });
                });
                createPdf();

            });

            window.onafterprint = function() {
                window.location.reload(true);
            };

            // check checkbok_no_kwitansi
            @if (auth()->user()->hasRole('asisten-manajer-tata-usaha'))
                // btnkirimwa
                $('#btnkirimwa').on('click', function() {
                    var no_kwitanasi_check = $('#checkbok_no_kwitansi').is(':checked') ? 'ya' : 'tidak';
                    var no_faktur_pajak_check = $('#checkbok_no_faktur_pajak').is(':checked') ? 'ya' : 'tidak';
                    var e_billing_check = $('#checkbok_e_billing').is(':checked') ? 'ya' : 'tidak';
                    var bukti_pembayaran_check = $('#checkbok_bukti_pembayaran').is(':checked') ? 'ya' : 'tidak';
                    var e_spt_check = $('#checkbok_e_spt').is(':checked') ? 'ya' : 'tidak';

                    $('#no_kwitansi_kirim').val(no_kwitanasi_check);
                    $('#no_faktur_pajak_kirim').val(no_faktur_pajak_check);
                    $('#e_billing_kirim').val(e_billing_check);
                    $('#bukti_pembayaran_kirim').val(bukti_pembayaran_check);
                    $('#e_spt_kirim').val(e_spt_check);

                    $('#form-kirim-wa').submit();

                });
                @if ($pkp == 'ya')

                    $('#checkbok_no_kwitansi').on('click', function() {

                        if ($(this).is(':checked')) {
                            $('#no_kwitansi_check').val('ya');
                        } else {
                            $('#no_kwitansi_check').val('tidak');
                        }
                        if ($('#checkbok_e_billing').is(':checked') && $('#checkbok_no_faktur_pajak').is(':checked') &&
                            $(
                                '#checkbok_bukti_pembayaran').is(':checked') && $('#checkbok_e_spt').is(':checked')) {
                            if ($(this).is(':checked')) {
                                $('#btn_setujui').prop('disabled', false);
                            } else {
                                $('#btn_setujui').prop('disabled', true);

                            }
                        } else {
                            $('#btn_setujui').prop('disabled', true);
                        }
                    });
                    // check checkbok_e_billing
                    $('#checkbok_e_billing').on('click', function() {
                        if ($(this).is(':checked')) {
                            $('#e_billing_check').val('ya');
                        } else {
                            $('#e_billing_check').val('tidak');
                        }
                        if ($('#checkbok_no_kwitansi').is(':checked') && $('#checkbok_no_faktur_pajak').is(
                                ':checked') && $(
                                '#checkbok_bukti_pembayaran').is(':checked') && $('#checkbok_e_spt').is(':checked')) {
                            if ($(this).is(':checked')) {
                                $('#btn_setujui').prop('disabled', false);
                            } else {
                                $('#btn_setujui').prop('disabled', true);

                            }
                        } else {
                            $('#btn_setujui').prop('disabled', true);
                        }
                    });
                    // check checkbok_no_faktur_pajak
                    $('#checkbok_no_faktur_pajak').on('click', function() {
                        if ($(this).is(':checked')) {
                            $('#no_faktur_pajak_check').val('ya');
                        } else {
                            $('#no_faktur_pajak_check').val('tidak');
                        }
                        if ($('#checkbok_no_kwitansi').is(':checked') && $('#checkbok_e_billing').is(':checked') && $(
                                '#checkbok_bukti_pembayaran').is(':checked') && $('#checkbok_e_spt').is(':checked')) {
                            if ($(this).is(':checked')) {
                                $('#btn_setujui').prop('disabled', false);
                            } else {
                                $('#btn_setujui').prop('disabled', true);

                            }
                        } else {
                            $('#btn_setujui').prop('disabled', true);
                        }
                    });
                    // check checkbok_bukti_pembayaran
                    $('#checkbok_bukti_pembayaran').on('click', function() {
                        if ($(this).is(':checked')) {
                            $('#bukti_pembayaran_check').val('ya');
                        } else {
                            $('#bukti_pembayaran_check').val('tidak');
                        }
                        if ($('#checkbok_no_kwitansi').is(':checked') && $('#checkbok_e_billing').is(':checked') && $(
                                '#checkbok_no_faktur_pajak').is(':checked') && $('#checkbok_e_spt').is(':checked')) {
                            if ($(this).is(':checked')) {
                                $('#btn_setujui').prop('disabled', false);
                            } else {
                                $('#btn_setujui').prop('disabled', true);

                            }
                        } else {
                            $('#btn_setujui').prop('disabled', true);
                        }
                    });
                    // check checkbok_e_spt
                    $('#checkbok_e_spt').on('click', function() {
                        if ($(this).is(':checked')) {
                            $('#e_spt_check').val('ya');
                        } else {
                            $('#e_spt_check').val('tidak');
                        }
                        if ($('#checkbok_no_kwitansi').is(':checked') && $('#checkbok_e_billing').is(':checked') && $(
                                '#checkbok_no_faktur_pajak').is(':checked') && $('#checkbok_bukti_pembayaran').is(
                                ':checked')) {
                            if ($(this).is(':checked')) {
                                $('#btn_setujui').prop('disabled', false);
                            } else {
                                $('#btn_setujui').prop('disabled', true);

                            }
                        } else {
                            $('#btn_setujui').prop('disabled', true);
                        }
                    });
                @else
                    $('#checkbok_no_kwitansi').on('click', function() {
                        if ($(this).is(':checked')) {
                            $('#no_kwitansi_check').val('ya');
                            $('#btn_setujui').prop('disabled', false);
                        } else {
                            $('#no_kwitansi_check').val('tidak');
                            $('#btn_setujui').prop('disabled', true);

                        }
                    });
                @endif
            @endif
        </script>
    @endpush
