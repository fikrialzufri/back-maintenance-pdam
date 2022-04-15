<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Absensi | PDAM</title>
    <meta name="description" content="">
    <meta name="keywords" content="PDAM">
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
    <div class="auth-wrapper bg" style="background-image: url({{ asset('img/BACKGROUNDLOGIN.jpg') }})">
        <div class="container-fluid h-100">
            <div class="row flex-row h-100">
                <div class="col-xl-4 col-lg-4 col-md-4 m-auto">
                    <div class="authentication-form bg-green mx-auto">
                        <div class="logo-centered">
                            <img height="100" src="{{ asset('img/logo.png') }}" alt="Absensi Karyawan">
                        </div>
                        <h1 class="text-center text-white">QR Code Absensi</h1>
                        <img src="data:image/png;base64, {!! base64_encode(
    QrCode::format('png')->size(300)->generate(route('absensi.store')),
) !!} " class="img-square">
                        {{-- <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate(env('APP_URL').'/PDAM/public/absensi-store'),) !!} " class="img-square"> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('src/js/vendor/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('plugins/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('plugins/screenfull/dist/screenfull.js') }}"></script>
</body>

</html>
