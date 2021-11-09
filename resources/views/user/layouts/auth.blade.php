<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    @include('user.layouts._head')

    <body>
        <header>
            @yield('header')
        </header>

        <main>
            @yield('content')
        </main>
    </body>

</html>
