<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
    @auth
    <!-- ==================================== -->
    <!-- START -->
    @include('layouts.components.head')
    <!-- END -->
    @endauth
    <!-- ==================================== -->
    @guest
    @include('layouts.components.head')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @endguest
</head>

<body class="main-body app sidebar-mini">
    <!-- HEADER -->
    @auth

    @include('layouts.components.navbar')
    @include('layouts.components.menu')

    @endauth

    <div id="app">
        @auth
        <!--Main Content-->
        <div class="main-content px-0 app-content">
            <!--Main Content Container-->
            <div class="container-fluid pd-t-6">
                <!--Page Header-->
                @include('layouts.bread')
                <!--Page Header-->

                @include('layouts.components.sidebar')

                @yield('content')
            </div>
            <!--Main Content Container-->
        </div>
        <!--Main Content-->
        @endauth
        @guest
        @yield('content')
        @endguest
    </div>
    <!-- FOOTER -->
    @auth

    @include('layouts.components.sidebar')

    @include('layouts.components.footer')

    @endauth

    @auth
    <!-- START -->
    @include('layouts.components.script')
    <!-- END -->
    @endauth
    @include('sweetalert::alert')
    @guest
    @include('layouts.components.script')
    @endguest
</body>

</html>