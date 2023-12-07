@extends('template.app')
@section('title', ucwords(str_replace([':', '_', '-', '*'], ' ', $title)))

@push('head')
    <style>
        @page {
            size: legal;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 215mm;
                max-width: 215mm;
                height: 355mm;
                margin: 5px;
            }
        }

        #text {
            white-space: nowrap;
            width: 80%;
            font-size: 20px;
            padding-left: 25px;
        }

        .child {
            display: inline-block;
            vertical-align: top;
            white-space: normal;
        }

        .child2 {
            width: 100%;
        }

        @media print {

            body,
            page[size="legal"] {
                background: white;
                width: 21cm;
                padding: 40px;
                height: 29.7cm;
                display: block;
                margin: 0 auto;
                font-family: "Times New Roman", serif;
                font-size: 20px;
                margin-bottom: 0.5cm;
                box-shadow: 0 0 0.5cm rgba(0, 0, 0, 0.5);
            }
        }
    </style>
@endpush
@section('content')

    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <page id="content" size="A4">
                            <div style=" d-flex justify-content-center align-items-center align-items-center">
                                <div class="text-center">
                                    <img src="{{ asset('img/kopsurat.png') }}" class="img-responsive" width="98%">
                                </div>
                            </div>
                            <div class="text-center" style="margin-bottom:24px;">
                                <span style=' font-size: 20px;'>
                                    <strong>
                                        <u>BERITA ACARA</u>
                                    </strong>
                                    <br>
                                    <strong>
                                        <u> PEMERIKSAAN
                                            PEKERJAAN</u>
                                    </strong>
                                    <br>
                                    <strong>
                                        <u> Nomor :
                                            602.694.2/{{ $tagihan->nomor_tagihan }}/01/{{ getRomawi($tagihan->list_persetujuan_direktur_teknik['created_at']->format('m')) }}/{{ $tagihan->list_persetujuan_direktur_teknik['created_at']->format('Y') }}
                                        </u>
                                    </strong>
                                    <br>
                                </span>
                            </div>

                            <ol style="font-size: 20px;">
                                @forelse ($tagihan->list_persetujuan as $index => $item)
                                    @if (
                                        $item->jabatan === 'Staf Pengawas' ||
                                            str_contains($item->jabatan, 'Asisten Manajer Distribusi') ||
                                            $item->jabatan === 'Asisten Manajer Pengawas' ||
                                            $item->jabatan === 'Manajer Distribusi')
                                        <li> <span style=''>{{ ucfirst($item->nama) }}
                                                sebagai {{ $item->jabatan }}</span></li>
                                    @endif
                                @empty
                                @endforelse
                            </ol>
                            <div>
                                <p style='font-size: 20px; text-align: justify;'>
                                    <span style=''>Pada hari ini, {{ $now }}.
                                    </span>
                                </p>
                                <p style='font-size: 20px; text-align: justify;'>
                                    <span style=''>
                                        Telah mengadakan pemeriksaan pekerjaan service kebocoran pipa periode bulan
                                        {{ $bulan }} tahun {{ $tahun }} di wilayah {{ $wilayah }}
                                        sebanyak {{ $total_lokasi }} lokasi, di Perumdam Tirta Kencana Kota Samarinda.
                                    </span>
                                </p>
                                <p style='font-size: 20px; text-align: justify;'>
                                    Pekerjaan perbaikan tersebut dilaksanakan oleh :
                                </p>
                            </div>

                            <p id="text">
                                <span class="child"> Nama &nbsp;&nbsp; :
                                </span>
                                <span class="child child2">
                                    {{ $tagihan->rekanan }}
                                </span>
                                <br>
                                <span class="child">
                                    Alamat &nbsp;:
                                </span>
                                <span class="child child2">
                                    {{ $tagihan->alamat_rekanan }}
                                </span>

                            </p>
                            <p style="text-align: justify;">
                                <span style='font-size: 20px;'>Berdasarkan surat perintah pelaksanaan pekerjaan tersebut dan
                                    persyaratan bahan maupun pelaksanaannya, dengan ini menyatakan bahwa pekerjaan tersebut
                                    telah selesai dikerjakan dan memenuhi prosedur serta persyaratan yang ditetapkan oleh
                                    Perumdam Tirta Kencana Kota Samarinda.</span>
                            </p>
                            <p style="text-align: justify;">
                                <span style='font-size: 20px;'>Demikian Berita Acara Pemeriksaan Pekerjaan ini dibuat dengan
                                    penuh tanggung jawab dan dapat dipergunakan sebagaimana mestinya.</span>
                            </p>
                            <div class="row">
                                <div style='font-size: 20px;' class="col-6 text-center">
                                    <br>
                                    {{ $tagihan->rekanan }}
                                    <br>
                                    Direktur
                                    <br>
                                    @if ($tagihan->rekanan_url != null)
                                        {!! QrCode::size(100)->generate($tagihan->rekanan_url_tdd) !!}
                                    @else
                                        @if ($tagihan->rekanan_url_tdd != null)
                                            {!! QrCode::size(100)->generate($tagihan->rekanan_url_tdd) !!}
                                        @endif
                                    @endif
                                    <br>
                                    {{ $tagihan->direktur }}
                                </div>

                                <div style='font-size: 20px;' class="col-6 text-center">
                                    Samarinda{{ $tanggal }}
                                    <br>
                                    Pemeriksa Pekerjaan,
                                    <br>
                                    @forelse ($tagihan->list_persetujuan as $index => $item)
                                        @if ($item->jabatan === 'Manajer Perencanaan')
                                            <span>{{ $item->jabatan }}</span>
                                            <br>
                                            @if ($item->url != null)
                                                <img src="data:image/png;base64, {!! base64_encode(
                                                    QrCode::format('png')->merge('https://pdam.borneocorner.com/img/logo-pdam.png', 0.3, true)->size(100)->generate($item->url),
                                                ) !!} ">
                                                {{-- {!! QrCode::size(100)->generate($item->url) !!} --}}
                                            @else
                                                @if ($item->tdd != null)
                                                    <img src="data:image/png;base64, {!! base64_encode(
                                                        QrCode::format('png')->merge('https://pdam.borneocorner.com/img/logo-pdam.png', 0.3, true)->size(100)->generate(url('tddkaryawan/' . $item->karyawan_id)),
                                                    ) !!} ">
                                                    {{-- {!! QrCode::size(100)->generate(url('tddkaryawan/' . $item->karyawan_id)) !!} --}}
                                                @endif
                                            @endif
                                        @endif
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                            <div class="text-center">
                                <span style=' font-size: 20px;'>
                                    @forelse ($tagihan->list_persetujuan as $index => $item)
                                        @if ($item->jabatan === 'Direktur Teknik')
                                            <p style=' font-size: 20px;'> Mengetahui, <br> Plt. {{ $item->jabatan }}
                                                <br> Perumdam Tirta Kencana Kota Samarinda
                                            </p>
                                            @if ($direktur)
                                                @if ($direktur->url)
                                                    <img src="data:image/png;base64, {!! base64_encode(
                                                        QrCode::format('png')->merge('https://pdam.borneocorner.com/img/logo-pdam.png', 0.3, true)->size(100)->generate($direktur->url),
                                                    ) !!} ">
                                                    {{-- {!! QrCode::size(100)->generate(url('tddkaryawan/' . $direktur->url)) !!} --}}
                                                @else
                                                    @if ($direktur->tdd)
                                                        <img src="data:image/png;base64, {!! base64_encode(
                                                            QrCode::format('png')->size(100)->merge('https://pdam.borneocorner.com/img/logo-pdam.png', 0.3, true)->generate(url('tddkaryawan/' . $direktur->id)),
                                                        ) !!} ">
                                                        {{-- {!! QrCode::size(100)->generate(url('tddkaryawan/' . $direktur->id)) !!} --}}
                                                    @endif
                                                @endif
                                            @endif
                                        @endif
                                    @empty
                                    @endforelse


                                </span>
                            </div>
                        </page>
                    </div>

                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->
            <!-- /.row (main row) -->
        </div><!-- /.container-fluid -->
    </div><!-- /.container-fluid -->
    <div class="card-footer">
        <button class="btn btn-primary" id="word-export"><span class="nav-icon fa fa-file-pdf" aria-hidden="true"></span>
            Print Surat Berita Acara</button>
    </div>
@stop

@push('scriptdinamis')
    {{-- <script src="{{ asset('js/FileSaver.js') }}"></script>
    <script src="{{ asset('js/jquery.wordexport.js') }}"></script> --}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.4/jspdf.min.js"></script>
    <script>
        // window.jsPDF = window.jspdf.jsPDF; // add this line of code

        // window.jsPDF = window.jspdf.jsPDF;
        function createPdf() {
            var printContents = document.getElementById('content').innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }

        $('#word-export').click(function() {
            createPdf()
        });

        window.onafterprint = function() {
            window.location.reload(true);
        };

        // let title = "{{ $filename }}";
        // $("#word-export").click(function(event) {
        //     $("#exportContent").wordExport(title);
        // });
    </script>
@endpush
