<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">   
    <title>@yield('title')</title>
    <link rel="icon" href="{{ asset('client/03_images/logo.png') }}" type="image/x-icon">
    @include('client.layouts.partials.css')
    @yield('css')
</head>

<body class="dark-scheme">
    <div id="wrapper">
        <header class="header_center">
            @include('client.layouts.partials.header')
        </header>

        @yield('content')

        <footer>
            @include('client.layouts.partials.footer')
        </footer>

        <div id="preloader">
            <div class="preloader1"></div>
        </div>
    </div>
    @include('client.layouts.partials.js')
</body>
@yield('js')

</html>
