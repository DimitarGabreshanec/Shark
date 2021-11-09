<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('user.layouts._head')

<body>
<header>
    @include('user.layouts._header')
</header>

@yield('content')

<footer>
    @include('user.layouts._footer')
</footer>

@include('user.share.css')
@include('user.share.js')

</body>

</html>
