<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('user.layouts._head')

<body>
<div id="splash">
    <div class="main"></div>
    <h1 id="logo">
        <img src="{{ asset('assets/user/img/logo.png') }}i" alt="">
    </h1>
    <p class="btn"><a href="{{ route('user.login.before') }}">はじめる</a></p>
</div>
</body>

</html>
