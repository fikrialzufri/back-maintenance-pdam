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
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Nama Rekanan :
                                    {{ $tagihan->rekanan }}</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Lapiran : &nbsp;</span>
                            </p>
                            <p
                                style='margin-top:0cm;margin-right:0cm;margin-bottom:4.0pt;margin-left:0cm;line-height:normal;font-size:15px;font-family:"Calibri",sans-serif;'>
                                <span style='font-size:16px;font-family:"Times New Roman",serif;'>Perihal&nbsp; &nbsp;
                                    &nbsp;
                                    &nbsp; &nbsp;
                                    &nbsp; &nbsp;: Permohonan Pembayaran</span>
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
                                <span
                                    style='font-size:16px;line-height:150%;font-family:"Times New Roman",serif;'>Dehubungan
                                    dengan
                                    selesainya
                                    pekerjaan service Kebocoran, Rehab Pipa dan Pemasangan Pipa Sekunder PERUMDAM Tirta
                                    Kencana
                                    Samarinda
                                    Periode bulan, {{ $bulan }} {{ date('Y') }} di wilayah {{ $wilayah }}
                                    sebanyak
                                    {{ $total_lokasi }}
                                    Lokasi, kami mengajukan Permohonan
                                    Pembayaran atas pekerjaan tersebut senilai Rp. {{ format_uang($total_tagihan) }}.
                                    <i>

                                        ({{ strtoupper(terbilang($total_tagihan)) }} RUPIAH)
                                    </i>
                                    Demikian
                                    Permohonan
                                    ini
                                    kami
                                    sampaikan.
                                    Atas perhatian dan kerjasamanya diucapkan terimakasih.</span>
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
                                                Samarinda,{{ $now }}</p>
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
                                                &nbsp;</p>
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
                                                &nbsp;</p>
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
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:105%;'>
                                                Direktur</p>
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
