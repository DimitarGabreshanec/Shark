<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('user.layouts._head')

<body>
    @yield('content')
</body>

</html>
