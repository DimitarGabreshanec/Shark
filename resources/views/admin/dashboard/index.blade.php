@extends('admin.layouts.app')

@section('content')
    <h2 class="title">Dashboard</h2>
    <ul class="top_nav">

        <li>
            <h1 class="nav_ttl">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon1.png') }}" alt=""></span>
                <span class="nav-text">商品管理</span>
            </h1>
            <p><a href="{{ route('admin.products.create') }}">商品登録</a></p>
            <p><a href="{{ route('admin.products.index') }}">商品ー覧</a></p>

        </li>

        <li>
            <h1 class="nav_ttl">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon13.png') }}" alt=""></span>
                <span class="nav-text">店舗管理</span>
            </h1>
            <p><a href="{{ route('admin.stores.create') }}">店舗登録</a></p>
            <p><a href="{{ route('admin.stores.index') }}">店舗ー覧</a></p>
        </li>

        <li>
            <h1 class="nav_ttl">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon7.png') }}" alt=""></span>
                <span class="nav-text">ユーザー管理</span>
            </h1>
            <p><a href="{{ route('admin.users.create') }}">ユーザー登録</a></p>
            <p><a href="{{ route('admin.users.index') }}">ユーザーー覧</a></p>
        </li>

        <li>
            <h1 class="nav_ttl">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon8.png') }}" alt=""></span>
                <span class="nav-text">注文管理</span>
            </h1>
            <p><a href="{{ route('admin.orders.index') }}">注文管理</a></p>
        </li>
        	
        <li>
	        <h1 class="nav_ttl">
	            <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon2.png') }}" alt=""></span>
	            <span class="nav-text">売上管理</span>
	        </h1>
            <p><a href="{{ route('admin.sales.index') }}">振込履歴管理</a></p>
            <p><a href="{{ route('admin.sales.transfer') }}">振込履歴状況</a></p>
	    </li>

        <li>
            <h1 class="nav_ttl">
                <span class="nav-icon"><img src="{{ asset('assets/admin/img/nav-icon11.png') }}" alt=""></span>
                <span class="nav-text">マスター管理</span>
            </h1>
            <p><a href="{{ route('admin.category.index') }}">カテゴリー管理</a></p>
            <p><a href="{{ route('admin.configuration.edit') }}">設定管理</a></p>
        </li>
    </ul>
@endsection
