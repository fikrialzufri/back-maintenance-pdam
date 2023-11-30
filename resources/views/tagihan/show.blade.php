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
                                                    <input type="text" name="no_hp_rekanan" id="Hp Rekanan"
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
                                                <h3>Detail Pekerjaan </h3>
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
                                    @endif

                                    @if (auth()->user()->hasRole('asisten-manajer-perencanaan-keuangan') && $tagihan->status === 'disetujui dirut')
                                        {{-- <div class="row mb-5">
                                            <div class="col-12">
                                                <div>
                                                    <label for="kode_anggaran" class=" form-control-label">Kode
                                                        anggaran</label>
                                                </div>
                                                <div>
                                                    <input type="text" name="kode_anggaran" placeholder="Kode anggaran"
                                                        class="form-control  {{ $errors->has('kode_anggaran') ? 'form-control is-invalid' : 'form-control' }}"
                                                        value="{{ old('kode_anggaran') }}" required>
                                                </div>

                                                @if ($errors->has('kode_anggaran'))
                                                    <span class="text-danger">
                                                        <strong id="textkk">{{ $errors->first('kode_anggaran') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div> --}}
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
                                                                        name="checkbok_no_kwitansi">
                                                                    <span class="pl-2">Persyaratan Sesuai</span>
                                                                </div>
                                                            </div>
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
                                                                            name="checkbok_no_faktur_pajak">
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
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
                                                                            name="checkbok_e_billing">
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
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
                                                                            name="checkbok_bukti_pembayaran">
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
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
                                                                            name="checkbok_e_spt">
                                                                        <span class="pl-2">Persyaratan Sesuai</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

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

                    <!-- /.row -->
                    <!-- Main row  dataitem-->
                    <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
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
            @if (auth()->user()->hasRole('asisten-manajer-tata-usaha') && $pkp == 'ya')

                $('#checkbok_no_kwitansi').on('click', function() {
                    if ($('#checkbok_e_billing').is(':checked') && $('#checkbok_no_faktur_pajak').is(':checked') && $(
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
                    if ($('#checkbok_no_kwitansi').is(':checked') && $('#checkbok_no_faktur_pajak').is(':checked') && $(
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
            @elseif (auth()->user()->hasRole('asisten-manajer-tata-usaha'))
                $('#checkbok_no_kwitansi').on('click', function() {
                    if ($(this).is(':checked')) {
                        $('#btn_setujui').prop('disabled', false);
                    } else {
                        $('#btn_setujui').prop('disabled', true);

                    }
                });
            @endif
        </script>
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

                var $rows = $('#tableItem tr');
                $('#search').keyup(function() {
                    var val = $.trim($(this).val()).replace(/ +/g, ' ').toLowerCase();

                    $rows.show().filter(function() {
                        var text = $(this).text().replace(/\s+/g, ' ').toLowerCase();
                        return !~text.indexOf(val);
                    }).hide();
                });

                $("#tableDokumentasi tr td").click(function() {
                    //get <td> element values here!!??
                });
                $(".ubah_pekerjaan").click(function() {
                    // let item_id = $(this).val();
                    let item_id = $(this).data('item_id');
                    let id = $(this).data('tagihan_id');
                    let jumlah = $(this).data('jumlah');
                    let master = $(this).data('master');
                    let jenis_harga = $(this).data('jenis_harga');

                    let harga = $('#harga_adjus_' + id).val();
                    $('#master_nama').text($(this).data('master'));

                    $('#list_item_modal').modal('toggle');
                    $('#tagihan_id_ganti').val(id);
                    $('#pekerjaan_label').val(master);

                    $(".ganti_pekerjaan").attr('data-tagihan', id);
                    $(".ganti_pekerjaan").attr('data-item_id', item_id);
                    $(".ganti_pekerjaan").attr('data-jumlah', jumlah);
                    $(".ganti_pekerjaan").attr('data-jenis_harga',
                        jenis_harga);

                });

                $(".btn_adjust").on("click", function(e) {
                    let item_id = $(this).data('item_id');;
                    let id = $(this).data('tagihan_id');
                    let jumlah = $(this).data('jumlah');
                    let jenis_harga = $(this).data('jenis_harga');
                    let harga = $('#harga_adjus_' + id).val();


                    $.when($.ajax({
                        type: 'POST',
                        url: "{{ route('tagihan.adjust') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id,
                            item_id,
                            jumlah,
                            jenis_harga,
                            harga,
                        },
                        success: function(data) {

                            const {
                                id,
                                nama,
                                harga,
                                harga_malam,
                                tanggal,
                                grand_total
                            } = data.data;

                            $.toast({
                                heading: 'Success',
                                text: "Mengubahan pekerjaan",
                                showHideTransition: 'slide',
                                icon: 'success',
                                loaderBg: '#f2a654',
                                position: 'top-right'
                            })


                            $('#listtagihan_' + id).removeClass('bg-danger');
                            $("#tanggal_adjust_" + id).text(tanggal);

                            $("#total_tagihan_" + id).text("Rp. " + formatRupiah(
                                grand_total.toString(),
                                ' '));

                            $("#total_tagihan_value_" + id).val(grand_total);
                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Oops...',
                                text: "gagal mengubah tagihan ",
                                footer: '<a href="">terdapat data yang kosong</a>'
                            })
                        }
                    })).then(function(data, textStatus, jqXHR) {
                        // totalHarga(modul)
                        const {
                            id,
                            nama,
                            harga,
                            harga_malam,
                        } = data.data;
                        $('#nama_master_tagihan_' + id).text(nama);

                        let sumTotal = 0;

                        $('.total_tagihan_value').each(function() {
                            sumTotal += parseFloat($(this)
                                .val());
                        });
                        $('#grand_total_tagihan_value_' + id).val(sumTotal);
                        $('#grand_total_tagihan_tampil_' + id).text(formatRupiah(
                            Math
                            .floor(
                                sumTotal).toString(), 'Rp. '));

                        $('#total_tagihan_all').val(formatRupiah(
                            Math
                            .floor(
                                sumTotal).toString(), 'Rp. '));

                        $('#grand_total_tagihan_tampil').text('Rp. ' + formatRupiah(
                            Math
                            .floor(
                                sumTotal).toString(), 'Rp. '));
                        $('#grand_total_tagihan_value').val(sumTotal);
                    });
                });

                $(".ganti_pekerjaan").on("click", function(e) {
                    e.stopPropagation();
                    let id = $(this).data('item');
                    let master = $(this).data('master');
                    let tagihan_id = $(this).data('tagihan');
                    let jumlah = $(this).data('jumlah');
                    let jenis_harga = $(this).data('jenis_harga');
                    let totalharga = 0;
                    $.when($.ajax({
                        type: 'GET',
                        url: "{{ route('item.detail') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id,
                            jenis_harga,
                        },
                        success: function(data) {

                            const {
                                id,
                                nama,
                                harga,
                                jenis,
                                harga_malam,
                            } = data.data;

                            if (jenis_harga == 'malam') {
                                $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga_malam
                                    .toString(),
                                    ' '));

                                totalharga = jumlah * harga_malam;
                            } else {
                                $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga
                                    .toString(),
                                    ' '));

                                totalharga = jumlah * harga;
                            }

                            $("#total_tagihan_" + tagihan_id).text("Rp. " + formatRupiah(
                                totalharga
                                .toString(),
                                ' '));

                            $("#total_tagihan_value_" + tagihan_id).val(totalharga);

                            $("#tagihan_master_" + tagihan_id).attr('data-tagihan', tagihan_id);
                            $("#tagihan_master_" + tagihan_id).attr('data-jumlah', jumlah);
                            $("#tagihan_master_" + tagihan_id).attr('data-item_id', id);
                            $("#tagihan_master_" + tagihan_id).attr('data-jenis_harga',
                                jenis_harga);

                            $("#btn_adjust_" + tagihan_id).attr('data-tagihan', tagihan_id);
                            $("#btn_adjust_" + tagihan_id).attr('data-jumlah', jumlah);
                            $("#btn_adjust_" + tagihan_id).attr('data-item_id', id);
                            $("#btn_adjust_" + tagihan_id).attr('data-jenis_harga',
                                jenis_harga);
                            $("#jenis_" + tagihan_id).text(jenis);



                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Oops...',
                                text: "gagal Mengahapus " +
                                    modul,
                                footer: '<a href="">terdapat data yang kosong</a>'
                            })
                        }
                    })).then(function(data, textStatus, jqXHR) {
                        // totalHarga(modul)
                        const {
                            id,
                            nama,
                            harga,
                            harga_malam,
                        } = data.data;
                        $('#nama_master_tagihan_' + tagihan_id).text(nama);
                        $('#list_item_modal').modal('toggle');

                        let sumTotal = 0;

                        $('.total_tagihan_value').each(function() {
                            sumTotal += parseFloat($(this)
                                .val());
                        });
                        $('#grand_total_tagihan_value_' + tagihan_id).val(sumTotal);
                        $('#grand_total_tagihan_tampil_' + tagihan_id).text(formatRupiah(
                            Math
                            .floor(
                                sumTotal).toString(), 'Rp. '));

                        $('#total_tagihan_all').val(formatRupiah(
                            Math
                            .floor(
                                sumTotal).toString(), 'Rp. '));

                        $('#grand_total_tagihan_tampil').text('Rp. ' + formatRupiah(
                            Math
                            .floor(
                                sumTotal).toString(), 'Rp. '));
                        $('#grand_total_tagihan_value').val(sumTotal);
                    });

                });

                function totalharga(tagihan_id) {
                    let sumTotal = 0;

                    $('.total_tagihan_value').each(function() {
                        sumTotal += parseFloat($(this)
                            .val());
                    });
                    $('#grand_total_tagihan_value_' + tagihan_id).val(sumTotal);
                    $('#grand_total_tagihan_tampil_' + tagihan_id).text(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));

                    $('#total_tagihan_all').val(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));

                    $('#grand_total_tagihan_tampil').text(formatRupiah(
                        Math
                        .floor(
                            sumTotal).toString(), 'Rp. '));
                    $('#grand_total_tagihan_value').val(sumTotal);
                }

                // $('.prevSpan').on('click', function() {
                //     $(".prevSpan").text("«");
                // });
                $(".cmbItem").on("change", function(e) {
                    let item_id = $(this).val();
                    let tagihan_id = $(this).data('tagihan_id');
                    let jenis_harga = $(this).data('jenis_harga');
                    let getharga = 0;
                    let total_harga = 0;
                    $.when($.ajax({
                        type: 'GET',
                        url: "{{ route('item.detail') }}",
                        data: {
                            "_token": "{{ csrf_token() }}",
                            id: item_id,
                            jenis_harga,
                        },
                        success: function(data) {

                            const {
                                harga,
                                harga_malam,
                            } = data.data;

                            if (jenis_harga == 'malam') {
                                $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga_malam
                                    .toString(),
                                    ' '));
                            } else {
                                $("#harga_adjus_" + tagihan_id).val(formatRupiah(harga
                                    .toString(),
                                    ' '));
                            }

                        },
                        error: function(data) {
                            Swal.fire({
                                title: 'Oops...',
                                text: "gagal Mengahapus " +
                                    modul,
                                footer: '<a href="">terdapat data yang kosong</a>'
                            })
                        }
                    })).then(function(data, textStatus, jqXHR) {
                        // totalHarga(modul)
                    });
                });
            });
        </script>
    @endpush
