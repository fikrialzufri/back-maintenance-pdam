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
                                    Periode bulan, {{ $bulan }} {{ date('Y') }} di wilayah I,II,III sebanyak
                                    {{ $tagihan->total_lokasi }}
                                    Lokasi, kami mengajukan Permohonan
                                    Pembayaran atas pekerjaan tersebut senilai Rp. {{ format_uang($total_tagihan) }}.
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
                            <table style="border-collapse:collapse;border:none;">
                                <tbody>
                                    <tr>
                                        <td style="width: 48.472%; border: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:  0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;line-height:106%;'>
                                            </p>
                                        </td>
                                        <td
                                            style="width: 51.2957%; border-top: none; border-right: none; border-bottom: none; border-image: initial; border-left: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                Hormat Kami,</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="width: 48.472%; border-right: none; border-bottom: none; border-left: none; border-image: initial; border-top: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td
                                            style="width: 51.2957%; border-top: none; border-left: none; border-bottom: none; border-right: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                Samarinda, {{ $now }}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="width: 48.472%; border-right: none; border-bottom: none; border-left: none; border-image: initial; border-top: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td
                                            style="width: 51.2957%; border-top: none; border-left: none; border-bottom: none; border-right: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td
                                            style="width: 48.472%; border-right: none; border-bottom: none; border-left: none; border-image: initial; border-top: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td
                                            style="width: 51.2957%; border-top: none; border-left: none; border-bottom: none; border-right: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 48.472%; border: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td style="width: 51.4119%; border: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                Direktur</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 48.472%; border: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                        <td style="width: 51.4119%; border: none; padding: 0cm 5.4pt; vertical-align: top;">
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;text-align:center;'>
                                                <span>{{ $tagihan->direktur }}</span>
                                            </p>
                                            <p
                                                style='margin-right:0cm;margin-left:0cm;font-size:16px;font-family:"Times New Roman",serif;margin:0cm;margin-top:0cm;margin-bottom:8.0pt;text-align:center;line-height:106%;'>
                                                &nbsp;</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="#" class="btn btn-primary" id="word-export"><span class="nav-icon fa fa-file-word"
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
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script
        src="https://www.jqueryscript.net/demo/Export-Html-To-Word-Document-With-Images-Using-jQuery-Word-Export-Plugin/FileSaver.js">
    </script>
    <script
        src="https://www.jqueryscript.net/demo/Export-Html-To-Word-Document-With-Images-Using-jQuery-Word-Export-Plugin/jquery.wordexport.js">
    </script>

    <script type="text/javascript">
        let title = "{{ $filename }}";
        console.log(title);
        $("#word-export").click(function(event) {
            $("#exportContent").wordExport(title);
        });
    </script>
@endpush
