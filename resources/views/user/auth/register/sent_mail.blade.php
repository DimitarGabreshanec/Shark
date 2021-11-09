@extends('user.layouts.auth')

@section('title', '会員登録')

@section('header')
    <h1 id="title">会員登録</h1>
@endsection

@section('content')
    <main>
            <div id="wrapper">
                <h2 class="title">メールアドレスに認証用URLを送信しました。<br>メールBOXから内容をご確認ください。</h2>
            </div>
            <div class="moving-mail">
                <img src="{{ asset('assets/user/img/anime.gif') }}">
            </div>
            <p class="btn"><a href="{{ route('user.login.form') }}">ログインに行く</a></p>
    </main>
@endsection
