<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('store.layouts._head')

<body>
<header>
    @include('store.layouts._header')
</header>

@yield('content')

<footer>
    @include('store.layouts._footer')
</footer>

@include('store.share.css')
@include('store.share.js')

</body>

</html>
