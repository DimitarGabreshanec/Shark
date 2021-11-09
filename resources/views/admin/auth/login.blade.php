@extends('admin.layouts.auth')

@section('content')
    <div id="login-wrap">
        <h1 class="logo-top">{{--<img src="{{ asset('assets/admin/img/logo.png') }}" alt="Shark">--}}Shark管理</h1>
        <div id="login">
            <form method="POST" action="{{ route('admin.login') }}">
                @csrf
                <h2>ログインＩＤ</h2>
                <div class="form-content">
                    <input type="text" id="login_id" name="login_id" value="{{ old('login_id') }}" required autocomplete="email" autofocus>
                    @error('login_id')
                        <span  class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <h2>パスワード</h2>
                <div class="form-content">
                    <input id="password" type="password" name="password" required autocomplete="current-password">
                    @error('password')
                        <span  class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

               {{-- <div class="form-content">
                    <input type="checkbox" id="learn">
                    <label for="learn">IDとパスワードを記憶する</label>
                </div>--}}

                <div class="form-content">
                    <input type="submit" value="ログイン">
                </div>
            </form>
        </div>
    </div>
@endsection

@section('page_css')
    <style>
        h1.logo-top img {
            width: 200px;
        }
    </style>
@endsection


