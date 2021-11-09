<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Shark管理画面'))</title>

    <!-- Styles -->
    <link href="{{ asset('assets/admin/css/basic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/photoswipe.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/default-skin.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/common.css') }}?20201008" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/modal_contents.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/jquery.fs.tipper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/sweetalert2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">

    <!-- Customize Styles -->
    @yield('page_css')

    <!-- Scripts -->
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.js') }}"></script>
    <script src="{{ asset('assets/admin/js/photoswipe.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/photoswipe-ui-default.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/photoswipe.js') }}"></script>
    <script src="{{ asset('assets/admin/js/autosize.js') }}"></script>
    <script src="{{ asset('assets/admin/js/floatthead.js') }}"></script>
    <script src="{{ asset('assets/admin/js/modal_contents.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.fs.tipper.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.cookie.js') }}"></script>
    <script src="{{ asset('assets/admin/js/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.js') }}"></script>

    <script src="{{ asset('assets/admin/js/customize.js') }}"></script>
    <script src="{{ asset('assets/admin/js/helper.js') }}"></script>
    <script src="{{ asset('assets/admin/js/input-comma.js') }}"></script>
    <script src="https://ajaxzip3.github.io/ajaxzip3.js" charset="UTF-8"></script>

    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>

    <!-- ページスクロール -->
    <script src="{{ asset('assets/admin/js/scroll.js') }}"></script>

    <!-- フォームの背景色を変更 -->
    <script src="{{ asset('assets/admin/js/input-backcolor.js') }}"></script>

    <!-- チェックシートのナビゲーションメニュー -->
    <script src="{{ asset('assets/admin/js/checksheet-nav.js') }}"></script>

    <!-- lightbox -->
    <link href="{{ asset('assets/vendor/lightbox/css/lightbox.css') }}" rel="stylesheet">

    <!-- Customize Scripts -->
    @yield('page_js')

    <script>
        $(document).ready(function(){
            $('.datepicker').datepicker({ 
                dateFormat: 'yy-mm-dd',
                showOn:"both",
                buttonImage:"{{ asset('assets/admin/img/calender.png') }}",
                buttonImageOnly:true
            });
            $(".calendar-wrap span img").css('display', 'none');
            $(".calendar-wrap img").css('padding', '3px 0');
            $(".calendar-datetime img").css('display', 'inline');

            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true
            })
        });
    </script>

</head>

<body>

    @include('admin.layouts.partials.header')

    <div id="maincontents">

        <div id="leftside" class="side-menu">
            @include('admin.layouts.partials.left')
        </div>
        <div id="rightside">
            <div id="wrapper">
                <div id="r-content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    @include('admin.layouts.partials.footer')

    <div id="dialog-confirm" style="display:none">
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>
            <span id="confirm_text"></span></p>
    </div>
    <script src="{{ asset('assets/vendor/lightbox/js/lightbox.js') }}"></script>

</body>

</html>
