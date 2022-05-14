<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <title>@yield('title','') | PDAM</title>
    <!-- initiate head with meta tags, css and script -->
    @include('template.head')

</head>

<body id="app">
    <div class="wrapper">
        <!-- initiate header-->
        @include('template.header')
        <div class="page-wrap">
            <!-- initiate sidebar-->
            @include('template.menu')

            <div class="main-content">
                @include('template.breadcrumb')
                <!-- yeild contents here -->

                @yield('content')
            </div>
            {{-- <!-- initiate chat section-->
            @include('template.chat') --}}


            <!-- initiate footer section-->
            @include('template.footer')

        </div>
    </div>

    <!-- initiate modal menu section-->
    @include('template.modalmenu')
    <script>
        /* Fungsi formatRupiah */
        function formatRupiah(angka, prefix) {
            var number_string = angka.replace(/[^,\d]/g, '').toString(),
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
            return prefix == undefined ? rupiah : (rupiah ? rupiah : '');
        }
    </script>
    <!-- initiate scripts-->
    @include('template.script')

</body>

</html>
