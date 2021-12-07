<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="#">
    <title>Simple Life</title>
    <link href="{{ asset('assets/frontend/assets/node_modules/calendar/dist/fullcalendar.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/frontend/assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/select2/css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/magnific-popup.css') }}"
          rel="stylesheet">
    <link href="{{ asset('assets/frontend/dist/css/user-card.css') }}" rel="stylesheet">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <link href="{{ asset('assets/frontend/css/main.css') }}" rel="stylesheet">
    @stack('css')
</head>

<body class="skin-default-dark fixed-layout">
<div class="preloader">
    <div class="loader">
        <div class="loader__figure"></div>
        <p class="loader__label">Simple Life Loading</p>
    </div>
</div>
<div id="main-wrapper">
    @include('Base::frontend.topbar')
    @include('Base::frontend.left_sidebar')
    <div class="page-wrapper">
        <div class="container-fluid">
            @yield('content')
        </div>
    </div>
</div>
<script src="{{ asset('assets/frontend/assets/node_modules/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/popper/popper.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/perfect-scrollbar.jquery.min.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/waves.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/sidebarmenu.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/sticky-kit-master/sticky-kit.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/sparkline/jquery.sparkline.min.js') }}"></script>
<script src="{{ asset('assets/frontend/dist/js/custom.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/calendar/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/moment/moment.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/calendar/dist/fullcalendar.min.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/calendar/dist/cal-init.js') }}"></script>
<script src="{{ asset('assets/frontend/assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
<script
    src="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup.min.js') }}"></script>
<script
    src="{{ asset('assets/frontend/assets/node_modules/Magnific-Popup-master/dist/jquery.magnific-popup-init.js') }}"></script>
<script src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
<script src="{{ asset('assets/frontend/js/main.js') }}"></script>
</body>
@if (session('success'))
    <script>
        $.toast({
            heading: "{{ trans('Success') }}",
            text: "{{ session('success') }}",
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'success',
            hideAfter: 6000,
            stack: 6
        });
    </script>
@endif
@if (session('error'))
    <script>
        $.toast({
            heading: "{{ trans('Error') }}",
            text: "{{ session('error') }}",
            position: 'top-right',
            loaderBg: '#ff6849',
            icon: 'error',
            hideAfter: 6000,
            stack: 6
        });
    </script>
@endif
@stack('js')
</html>
