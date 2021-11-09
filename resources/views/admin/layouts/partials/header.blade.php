<header>
    <h1 class="logo">
        <a href="{{  route('admin.dashboard.index') }}">
            {{--<img src="{{ asset('assets/admin/img/logo.png') }}" alt="Shark">--}}
        </a>
    </h1>
    <div id="r-head">
        <div id="r-head-right">
            <input type="button" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <p class="login-user"><span>管理者</span>様</p>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</header>
