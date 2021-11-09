<p class="back"><a href="#" onclick="javascript:window.history.back(-1);return false;"><img src="{{ asset('assets/admin/img/back.png') }}" alt="戻る"></a></p>
<!-- <h1 class="logo"><img src="img/logo.png" alt="UJ レンタル"></h1> -->
<p class="menu"><img src="{{ asset('assets/admin/img/menu.png') }}" alt="スマホメニュー"></p>
<div class="demo acc switch ">
    <ul id="nav">
        <a class="menu-trigger">
            <span></span>
            <span></span>
            <span></span>
        </a>

        <li>
            <a class="toggle menu">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon1.png') }}" alt=""></span>
                <span class="nav-text">商品管理</span>
            </a>
            <ul class="inner child child01">
                <li><a href="{{ route('admin.products.create', 'index_params') }}">商品登録</a></li>
                <li><a href="{{ route('admin.products.index', 'index_params') }}">商品ー覧</a></li>
            </ul>
            <p class="fukidashi">商品ー覧</p>
        </li>

        <li>
            <a class="toggle menu">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon13.png') }}" alt=""></span>
                <span class="nav-text">店舗管理</span>
            </a>
            <ul class="inner child child01">
                <li><a href="{{ route('admin.stores.create', 'index_params') }}">店舗登録</a></li>
                <li><a href="{{ route('admin.stores.index', 'index_params') }}">店舗ー覧</a></li>
            </ul>
            <p class="fukidashi">店舗ー覧</p>
        </li>

        <li>
            <a class="toggle menu">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon7.png') }}" alt=""></span>
                <span class="nav-text">ユーザー管理</span>
            </a>
            <ul class="inner child child01">
                <li><a href="{{ route('admin.users.create', 'index_params') }}">ユーザー登録</a></li>
               <li><a href="{{ route('admin.users.index', 'index_params') }}">ユーザーー覧</a></li>
            </ul>
            <p class="fukidashi">ユーザーー覧</p>
        </li>

        <li>
            <a class="toggle menu">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon8.png') }}" alt=""></span>
                <span class="nav-text">注文管理</span>
            </a>
            <ul class="inner child child01">
                <li><a href="{{ route('admin.orders.index') }}">注文管理</a></li>
            </ul>
            <p class="fukidashi">注文管理</p>
        </li>
        		
        <li>
            <a class="toggle menu">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon2.png') }}" alt=""></span>
                <span class="nav-text">売上管理</span>
            </a>
            <ul class="inner child child01">
                <li><a href="{{ route('admin.sales.index') }}">振込履歴管理</a></li>
                <li><a href="{{ route('admin.sales.transfer') }}">振込履歴状況</a></li>

            </ul>
            <p class="fukidashi">振込履歴管理</p>
        </li>

        <li>
            <a class="toggle menu">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon11.png') }}" alt=""></span>
                <span class="nav-text">マスター管理</span>
            </a>
            <ul class="inner child child01">
                <li><a href="{{ route('admin.category.index') }}">カテゴリー管理</a></li>
                <li><a href="{{ route('admin.configuration.edit') }}">設定管理</a></li>
            </ul>
            <p class="fukidashi">マスター管理</p>
        </li>

    </ul>
</div>
