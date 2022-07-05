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
                                    <img src="{{ asset('img/kopsurat.png') }}" class="img-responsive" width="50%">
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
                                        <u> Nomor : {{ $tagihan->nomor_tagihan }}</u>
                                    </strong>
                                    <br>
                                </span>
                            </div>
                            <p style=' font-size: 20px;'>
                                <span style=''>Pada hari ini,
                                    {{ $now }}</span>
                            </p>
                            <p style=' font-size: 20px;'>
                                <span style=''>Kami masing-masing adalah
                                    :</span>
                            </p>
                            <ol style="font-size: 20px;">
                                @forelse ($tagihan->list_persetujuan as $index => $item)
                                    @if ($item->jabatan === 'Staf Pengawas' || str_contains($item->jabatan, 'Asisten Manajer Distribusi') || $item->jabatan === 'Asisten Manajer Pengawas' || $item->jabatan === 'Manajer Distribusi')
                                        <li> <span style=''>{{ ucfirst($item->nama) }}
                                                sebagai {{ $item->jabatan }}</span></li>
                                    @endif
                                @empty
                                @endforelse
                            </ol>
                            <div>
                                <p style='font-size: 20px;'>
                                    <span style=''>Telah mengadakan pemeriksaan pekerjaan, service kebocoran pipa
                                        periode pulan {{ $bulan }} tahun {{ date('Y') }} di wilayah
                                        {{ $wilayah }} Sebanyak
                                        {{ $total_lokasi }} lokasi. Dengan jumlah tagihan sebesar Rp.
                                        {{ format_uang($total_tagihan) }}.
                                        <i>
                                            ({{ strtoupper(terbilang($total_tagihan)) }} RUPIAH)
                                        </i>
                                    </span>
                                </p>
                            </div>

                            <p style=' font-size: 20px;'>
                                <span style=''>Pekerjaan tersebut
                                    dilaksanakan oleh :</span>
                            </p>
                            <p id="text">
                                <span class="child"> Nama &nbsp;&nbsp; :
                                </span>
                                <span class="child child2">
                                    {{ $tagihan->rekanan }}
                                </span>
                                <br>
                                <span class="child">
                                    Alamat :
                                </span>
                                <span class="child child2">
                                    {{ $tagihan->alamat_rekanan }}
                                </span>

                            </p>
                            <p>
                                <span style='font-size: 20px;'>Berdasarkan surat perintah pelaksanaan pekerjaan tersebut
                                    dari persyaratan-persyaratan, bahan-bahan maupun pelaksanaannya, dengan ini menyatakan
                                    bahwa
                                    pekerjaan (terlampir), telah selesai dikerjakan dan memenuhi prosedur serta persyaratan
                                    yang ditetapkan oleh Perumdam Tirta Kencana Kota Samarindaa.</span>
                            </p>
                            <p>
                                <span style='font-size: 20px;'>Demikian Berita Acara Pemeriksaan Pekerjaan ini dibuat dengan
                                    penuh tanggung jawab
                                    dan dapat dipergunakan sebagaimana mestinya..</span>
                            </p>
                            <div class="row">
                                <div style='font-size: 20px;' class="col-3">
                                    {{ $tagihan->rekanan }}
                                    <br>
                                    Samarinda, {{ $tanggal }}
                                    <br>
                                    @if ($tagihan->rekanan_url != null)
                                        <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(100)->generate($tagihan->rekanan_url),
) !!} " class="img-square">
                                    @else
                                        <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(100)->generate($tagihan->rekanan_url_tdd),
) !!} " class="img-square">
                                    @endif
                                    <br>
                                    {{ $tagihan->direktur }}
                                </div>

                                <div style='font-size: 20px;' class="text-left col">
                                    Samarinda, {{ $tanggal }}
                                    <br>
                                    Pemeriksa Pekerjaan,
                                    <br>
                                    <div class="d-flex justify-content-start">
                                        <div class="d mr-3">
                                            <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(100)->generate($tagihan->rekanan_url_tdd),
) !!} " class="img-square">
                                        </div>
                                        <div class="d">
                                            <ol style="">

                                                @forelse ($tagihan->list_persetujuan as $index => $item)
                                                    @if ($item->jabatan === 'Staf Pengawas' || $item->jabatan === 'Asisten Manajer Pengawas' || str_contains($item->jabatan, 'Asisten Manajer Distribusi') || $item->jabatan === 'Manajer Distribusi')
                                                        <li> <span style=''>{{ ucfirst($item->nama) }}
                                                                sebagai {{ $item->jabatan }}</span></li>
                                                    @endif
                                                @empty
                                                @endforelse
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <span style=' font-size: 20px;'>
                                    <p style=' font-size: 20px;'> Mengetahui, <br> Direktur Teknik
                                        <br> Perumdam Tirta Kencana Kota Samarinda
                                    </p>
                                    @forelse ($tagihan->list_persetujuan as $index => $item)
                                        @if ($item->jabatan === 'Direktur Teknik')
                                            <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(100)->generate($direktur),
) !!} " class="img-square">
                                        @endif
                                    @empty
                                    @endforelse

                                    <p> <strong style=' font-size: 20px;'> Ali Rachman AS, S.T.</strong></p>
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
        <btn class="btn btn-primary" id="word-export"><span class="nav-icon fa fa-file-pdf" aria-hidden="true"></span>
            Print Surat Berita Acara</btn>
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
