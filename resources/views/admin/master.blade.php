<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title')</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Meta -->

    <link rel="icon" href="{{asset('client/03_images/logo.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('adminn/assets/css/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('adminn/assets/fonts/bootstrap/bootstrap-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('adminn/assets/css/main.min.css') }}">
    @include('admin.layouts.css')

    <!-- Bootstrap 5 CSS (if needed) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">

    <!-- Vendor Css Files -->
    <link rel="stylesheet" href="{{ asset('adminn/assets/vendor/overlay-scroll/OverlayScrollbars.min.css') }}">
    @yield('css')
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="page-wrapper">
        <!-- Site wrapper -->
        <div class="main-container">

            @include('admin.layouts.load')


            @include('admin.layouts.header')
            @include('admin.layouts.messages')


            <!-- Left side column. contains the sidebar -->
            @include('admin.layouts.menu')

            <!-- Content Wrapper. Contains page content -->

            <!-- Content Header (Page header) -->
            <section class="content-header">

                <h4>@yield('title-page')</h4>

            </section>

            @yield('content')
            <!-- /.content -->

            <!-- /.content-wrapper -->

            @include('admin.layouts.footer')

        </div>
        <!-- ./wrapper -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- jQuery 3 -->
        <script src="{{ asset('adminn/assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('adminn/assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('adminn/assets/js/modernizr.js') }}"></script>
        <script src="{{ asset('adminn/assets/js/moment.js') }}"></script>

        <!-- Vendor Js Files -->
        <script src="{{ asset('adminn/assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('adminn/assets/vendor/overlay-scroll/custom-scrollbar.js') }}"></script>

        <!-- Apex Charts -->
        <script src="{{ asset('adminn/assets/vendor/apex/apexcharts.min.js') }}"></script>
        <script src="{{ asset('adminn/assets/vendor/apex/custom/sales/salesGraph.js') }}"></script>
        <script src="{{ asset('adminn/assets/vendor/apex/custom/sales/revenueGraph.js') }}"></script>
        <script src="{{ asset('adminn/assets/vendor/apex/custom/sales/taskGraph.js') }}"></script>


        <!-- Main Js Required -->
        <script src="{{ asset('adminn/assets/js/main.js') }}"></script>

        <!-- Popper.js and Bootstrap JS (if needed) -->
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>

        @yield('custom-js')
        @yield('scripts')
    </div>
</body>
{{-- <script src="https://code.jquery.com/jquery-3.7.1.js"></script> --}}

@isset($script)
    {{ $script }}
@endisset

</html>
