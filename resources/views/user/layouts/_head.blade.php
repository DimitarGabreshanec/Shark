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
    <meta property="og:image" content="{{ asset('assets/user/img/ogg.jpg') }}">
    <meta property="og:url" content="/">
    <meta property="og:site_name" content="{{ config('app.name', 'shark') }}">
    <meta content="summary" name="twitter:card">
    <meta content="@twitter_acount" name="twitter:site">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/user/img/icon.png') }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('assets/user/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/css/basic.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/css/common.css') }}?20201116">
    <link rel="stylesheet" href="{{ asset('assets/user/css/modal_nav.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/css/modal_contents.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/user/css/cursorMove.css') }}">


    <!-- Customize Styles -->
    @yield('page_css')

    <!--JS-->
    <script src="{{ asset('assets/user/js/app.js') }}"></script>
    <script src="{{ asset('assets/user/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/user/js/selectivizr-min.js') }}"></script>
    <script src="{{ asset('assets/user/js/device.js') }}"></script>
    <script src="{{ asset('assets/user/js/modal_nav.js') }}"></script>
    <script src="{{ asset('assets/user/js/modal_contents.js') }}"></script>
    <script src="{{ asset('assets/user/js/slick.min.js') }}"></script>
    <script src="{{ asset('assets/user/js/jquery.easeScroll.js') }}"></script>
    <script src="{{ asset('assets/user/js/jquery.inview.min.js') }}"></script>
    {{--<script src="{{ asset('assets/user/js/mouseMove.js') }}"></script>--}}
    <script src="{{ asset('assets/user/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('assets/user/js/customize.js') }}"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

    <!-- Customize Scripts -->
    @yield('page_js')
</head>
