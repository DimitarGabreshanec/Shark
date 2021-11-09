@extends('store.layouts.auth')

@section('title', 'パスワードをお忘れの方へ')

@section('header')
    <h1 id="title">パスワード再設定</h1>
@endsection

@section('content')
    <div id="wrapper">
        <h2 class="title">パスワードが変更されました。</h2>
        <input type="submit" value="ログイン画面へ" onClick="location.href='{{ route('store.login.form') }}'; return false;">
    </div>
@endsection
