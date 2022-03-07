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

    <!-- initiate scripts-->
    @include('template.script')

</body>

</html>
