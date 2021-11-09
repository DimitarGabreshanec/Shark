@extends('user.layouts.auth')

@section('title', 'ログイン')

@section('header')
    <h1 id="title">ログイン</h1>
@endsection

@section('content')
    <div id="wrapper">
        <div class="firsttime">
            <h2 class="title">初めてご利用の方</h2>
            <p class="btn"><a href="{{ route('user.register.before') }}">会員登録</a></p>
        </div>
    </div>
    <div class="account">
        <h2 class="title">アカウントをお持ちの方</h2>
        <ul class="account-btns">
            <li class="google"><input type="button" value="Googleでログイン" onClick="location.href='{{ route('social.login', ['provider'=>'google']) }}'; return false;"></li>
            <li class="facebook"><input type="button" value="Facebookでログイン" onClick="location.href='{{ route('social.login', ['provider'=>'facebook']) }}'; return false;"></li>
            <li class="apple"><input type="button" value="Appleでログイン" onClick="location.href='{{ route('social.login', ['provider'=>'apple']) }}'; return false;"></li>
            <li class="mail"><input type="button" value="メールアドレスでログイン" onClick="location.href='{{ route('user.login.form') }}'; return false;"></li>
            <li class="line"><input type="button" value="LINEでログイン" onClick="location.href='{{ route('social.login', ['provider'=>'line']) }}'; return false;"></li>
        </ul>
    </div>
    <aside class="plus">登録またはログインすることで、<a href="{{ route('term_use') }}" target="_blank">利用規約</a>と<a href="{{ route('privacy') }}" target="_blank">プライバシーポリシー</a>に同意したものとみなされます。</aside>
@endsection
