@extends('store.layouts.auth')

@section('title', 'パスワードをお忘れの方へ')

@section('header')
    <h1 id="title">パスワードをお忘れの方へ</h1>
@endsection

@section('content')
    <main>
        <div id="wrapper"> 
            <h2 class="title">メールアドレスに認証用URLを送信しました。<br>メールBOXから内容をご確認ください。</h2>
        </div>
        <div class="moving-mail">
            <img src="{{ asset('assets/user/img/anime.gif') }}">
        </div>
    </main>
@endsection
