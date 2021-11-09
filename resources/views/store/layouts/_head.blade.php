<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1">
    <title>@yield('title') | {{ config('app.name', 'shark') }}</title>
    <meta name="title" content="{{ config('app.name', 'shark') }}">
    <meta name="keywords" content="">
    <meta name="description" content="">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--OGP-->
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ config('app.name', 'shark') }}">
    <meta property="og:description" content="">
    <meta property="og:image" content="{{ asset('assets/store/img/ogg.jpg') }}">
    <meta property="og:url" content="/">
    <meta property="og:site_name" content="{{ config('app.name', 'shark') }}">
    <meta content="summary" name="twitter:card">
    <meta content="@twitter_acount" name="twitter:site">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/store/img/icon.png') }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/store/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/store/css/basic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/store/css/common.css') }}?20201123">
    <link rel="stylesheet" href="{{ asset('assets/store/css/modal_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/store/css/modal_contents.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/store/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/store/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/store/css/cursorMove.css') }}">

    <!-- Customize Styles -->
    @yield('page_css')

    <!--JS-->
    <script src="{{ asset('assets/store/js/app.js') }}"></script>
    <script src="{{ asset('assets/store/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/store/js/selectivizr-min.js') }}"></script>
    <script src="{{ asset('assets/store/js/device.js') }}"></script>
    <script src="{{ asset('assets/store/js/modal_nav.js') }}"></script>
    <script src="{{ asset('assets/store/js/modal_contents.js') }}"></script>
    <script src="{{ asset('assets/store/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/store/js/jquery.easeScroll.js') }}"></script>
    <script src="{{ asset('assets/store/js/jquery.inview.min.js') }}"></script>
    {{--<script src="{{ asset('assets/store/js/mouseMove.js') }}"></script>--}}
    <script src="{{ asset('assets/store/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/store/js/customize.js') }}"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

    <!-- Customize Scripts -->
    @yield('page_js')
</head>
