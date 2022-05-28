<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ $title }} | PDAM SAMARINDA</title>
    <meta name="description" content="">
    <meta name="keywords" content="PDAM SAMARINDA">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/x-icon" />

    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('plugins/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/ionicons/dist/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icon-kit/dist/css/iconkit.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/theme.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('src/js/vendor/modernizr-2.8.3.min.js') }}"></script>
    <style>
        body,
        html {
            height: 100%;
        }

        .bg {
            /* The image used */
            background-image: url("img/PDAM-BG.jpg");

            /* Full height */
            height: 100%;

            /* Center and scale the image nicely */
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

    </style>
</head>

<body>
    <!--[if lt IE 8]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
    <div class="auth-wrapper bg">
        <div class="wrapper">
            <!-- initiate header-->
            <!-- initiate sidebar-->

            <div class="main-content">
                <div class="container-fluid">
                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <page id="content">
                                        <div
                                            style=" d-flex justify-content-center align-items-center align-items-center">
                                            <div class="text-center">
                                                <img src="{{ asset('img/kopsurat.png') }}" class="img-responsive"
                                                    width="70%">
                                            </div>
                                        </div>
                                        <div class="text-center" style="margin-bottom:24px;">
                                            <span>
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
                                        <p>
                                            <span style=''>Pada Hari ini,
                                                {{ $now }}</span>
                                        </p>
                                        <p>
                                            <span style=''>Kami Masing-Masing Adalah
                                                :</span>
                                        </p>
                                        <ol style="">
                                            @forelse ($tagihan->list_persetujuan as $index => $item)
                                                @if ($item->jabatan !== 'Direktur Teknik' && $item->jabatan !== 'Staf Perencanaan')
                                                    <li> <span style=''>{{ $item->nama }}
                                                            Sebagai {{ $item->jabatan }}</span></li>
                                                @endif
                                            @empty
                                            @endforelse
                                        </ol>
                                        <p>
                                            <span style=''>Telah Mengadakan
                                                Pemeriksaan Pekerjaan,</span>
                                        </p>
                                        <div>
                                            <p>
                                                <span style=''>Service Kebocoran
                                                    Pipa Dinas, Tersier, Sekunder, dan Interkoneksi Pipa Periode
                                                    Bulan
                                                    {{ $bulan }}
                                                    Tahun {{ date('Y') }} di Wilayah {{ $wilayah }}
                                                    Sebanyak
                                                    {{ $total_lokasi }}
                                                    Lokasi. Perumdam Tirta Kencana
                                                    Kota Samarinda.</span>
                                            </p>
                                        </div>

                                        <p>
                                            <span style=''>Pekerjaan Tersebut
                                                Dilaksanakan Oleh :</span>
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
                                            <span>Berdasarkan Surat Perintah
                                                Pelaksanaan untuk pekerjaan tersebut dan persyaratan-persyaratan
                                                bahan-bahan
                                                maupun
                                                pelaksanaannya, dengan ini menyatakan bahwa pekerjaan (terlampir)
                                                dari perumdam,
                                                telah
                                                selesai dikerjakan dan memenuhi prosedur dan syarat-syarat yang
                                                ditetapkan oleh
                                                Perumdam
                                                Tirta Kencana Kota Samarinda.</span>
                                        </p>
                                        <p>
                                            <span>Demikian Berita Acara
                                                Pemeriksaan Pekerjaan ini dibuat dengan penuh tanggung jawab
                                                sebagaimana
                                                mestinya.</span>
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6 mb-20">
                                                {{ $tagihan->rekanan }}
                                                <br>
                                                Samarinda, {{ $tanggal }}
                                                <br>
                                                <img src="{{ asset('storage/rekanan/' . $tagihan->tdd_rekanan) }}"
                                                    alt="" width="10%">
                                                <br>
                                                {{ $tagihan->direktur }}
                                            </div>

                                            <div class="text-left col-md-6 ">
                                                Samarinda, {{ $tanggal }}
                                                <br>
                                                Pemeriksa Pekerjaan,
                                                <br>
                                                <ol style="">

                                                    @forelse ($tagihan->list_persetujuan_tanda_tangan as $index => $item)
                                                        @if ($item->jabatan !== 'Direktur Teknik' && $item->jabatan !== 'Staf Perencanaan')
                                                            <li> <img
                                                                    src="{{ asset('storage/karyawan/' . $item->tdd) }}"
                                                                    alt="" width="5%"> <span
                                                                    style=''>{{ $item->nama }}
                                                                    Sebagai {{ $item->jabatan }}</span>
                                                            </li>
                                                        @endif
                                                    @empty
                                                    @endforelse
                                                </ol>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <span>
                                                <p> Mengetahui, <br> Direktur Teknik
                                                    PEKERJAAN <br> Perumdam Tirta Kencana Kota Samarinda</p>
                                                @forelse ($tagihan->list_persetujuan_tanda_tangan as $index => $item)
                                                    @if ($item->jabatan === 'Direktur Teknik')
                                                        <img src="{{ asset('storage/karyawan/' . $item->tdd) }}"
                                                            alt="" width="8%">
                                                    @endif
                                                @empty
                                                @endforelse

                                                <p> <strong> Ali Rachman AS, S.T.</strong>
                                                </p>
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
            </div>
            {{-- <!-- initiate chat section-->
            @include('template.chat') --}}


            <!-- initiate footer section-->
        </div>

    </div>

    <script src="{{ asset('src/js/vendor/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('plugins/screenfull/dist/screenfull.js') }}"></script>
    {{-- Firebase --}}
    <script type="module">
        // Import the functions you need from the SDKs you need
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/9.6.9/firebase-app.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyAiIdOVXPc1C90tWcDrpG984rzidIgU9Kk",
            authDomain: "pdam-work-order.firebaseapp.com",
            projectId: "pdam-work-order",
            storageBucket: "pdam-work-order.appspot.com",
            messagingSenderId: "167105139450",
            appId: "1:167105139450:web:cf92428440b90382686f43"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
    </script>
</body>

</html>
