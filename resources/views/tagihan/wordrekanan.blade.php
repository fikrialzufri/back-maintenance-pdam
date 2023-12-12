@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div id="exportContent">

                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Nomor :
                                    {{ $tagihan->nomor_tagihan }}</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Tanggal :
                                    {{ $tanggal }}</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Lampiran : &nbsp;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Perihal&nbsp; &nbsp;
                                    &nbsp;
                                    &nbsp; &nbsp;
                                    &nbsp; &nbsp;: Permohonan Pembayaran Tagihan</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>&nbsp;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Kepada Yth,</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Direktur Utama</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>PERUMDAM Tirta Kencana
                                    Kota
                                    Samarinda</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Di &ndash;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>&nbsp; &nbsp; &nbsp;
                                    &nbsp;
                                    &nbsp;
                                    &nbsp;&nbsp;SAMARINDA</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span
                                    style='font-size:16px;line-height:107%;font-family:"Times New Roman",serif;'>&nbsp;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span
                                    style='font-size:16px;line-height:107%;font-family:"Times New Roman",serif;'>&nbsp;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:150%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                <span style='font-size:16px;line-height:150%;font-family:"Times New Roman",serif;'>Dengan
                                    Hormat,</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:150%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                <span style='font-size:16px;line-height:150%;font-family:"Times New Roman",serif;'>
                                    Pada hari ini, {{ $now }} </span>
                            </p>

                            <p>
                                <span class="child"
                                    style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                    Nama &nbsp;&nbsp;&nbsp;:
                                </span>
                                <span class="child child2"
                                    style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                    {{ $tagihan->rekanan }}
                                </span>
                                <br>
                                <span class="child"
                                    style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                    Alamat &nbsp;:
                                </span>
                                <span class="child child2"
                                    style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                    {{ $tagihan->alamat_rekanan }}
                                </span>
                                <br>
                                <span class="child"
                                    style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                    No Hp &nbsp;&nbsp;:
                                </span>
                                <span class="child child2"
                                    style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                    {{ $tagihan->no_hp_rekanan }}
                                </span>
                            </p>

                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:150%;font-size:15px;font-family:"Calibri",sans-serif;text-align:justify;'>
                                <span style='font-size:16px;line-height:150%;font-family:"Times New Roman",serif;'>
                                    Sehubungan dengan selesainya pekerjaan service kebocoran pipa PERUMDAM Tirta Kencana
                                    Samarinda Periode bulan {{ $bulan }} tahun
                                    {{ $tahun }} di wilayah {{ $wilayah }} sebanyak {{ $total_lokasi }} Lokasi,
                                    kami mengajukan Permohonan Pembayaran atas pekerjaan tersebut
                                    senilai Rp{{ format_uang($total_tagihan) }},00
                                    ({{ terbilang($total_tagihan) }} rupiah).

                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;line-height:107%;font-family:"Times New Roman",serif;'>Demikian
                                    Permohonan ini kami sampaikan. Atas perhatian dan kerjasamanya diucapkan terimakasih.
                                </span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span
                                    style='font-size:16px;line-height:107%;font-family:"Times New Roman",serif;'>&nbsp;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:8.0pt;margin-left:0cm;line-height:107%;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span
                                    style='font-size:16px;line-height:107%;font-family:"Times New Roman",serif;'>&nbsp;</span>
                            </p>
                            <table style="border: none;width:98.44%;border-collapse:collapse;">
                                <tbody>
                                    <tr>
                                        <td style="width: 58.48%;padding: 0cm 5.4pt;vertical-align: top;"><br></td>
                                        <td
                                            style="width: 41.52%;padding: 0cm 5.4pt;border-image: initial;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                Hormat Kami,</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="width: 58.48%;padding: 0cm 5.4pt;border-image: initial;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td style="width: 41.52%;padding: 0cm 5.4pt;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                Samarinda, {{ $nowRekanan }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 58.48%;padding: 0cm 1.4pt;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td style="width: 41.52%;padding: 0cm 1.4pt;vertical-align: top;">

                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                {{ ucwords($tagihan->rekanan_pimpinan) }}
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 58.48%;padding: 0cm 1pt;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td style="width: 41.52%;padding: 0cm 5.4pt;vertical-align: top;">
                                            <div
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;text-align:center;'>
                                                @if ($tagihan->rekanan_url != null)
                                                    {!! QrCode::size(100)->generate($tagihan->rekanan_url_tdd) !!}
                                                @else
                                                    @if ($tagihan->rekanan_url_tdd != null)
                                                        {!! QrCode::size(100)->generate($tagihan->rekanan_url_tdd) !!}
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="width: 58.48%;padding: 0cm 5.4pt;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td style="width: 41.52%;padding: 0cm 5.4pt;vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;text-align:center;'>
                                                {{ $tagihan->direktur }}</p>
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                &nbsp;</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="" class="btn btn-primary" id="word-export"><span class="nav-icon fa fa-file-word"
                                aria-hidden="true"></span>
                            Export Tagihan</a>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->

@stop

@push('scriptdinamis')
    <script src="{{ asset('js/FileSaver.js') }}"></script>
    <script src="{{ asset('js/jquery.wordexport.js') }}"></script>

    <script type="text/javascript">
        let title = "{{ $filename }}";
        $("#word-export").click(function(event) {
            $("#exportContent").wordExport(title);
        });
    </script>
@endpush
