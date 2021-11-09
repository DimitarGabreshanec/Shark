@extends('user.layouts.auth')

@section('title', '会員登録')

@section('header')
    <h1 id="title">会員登録</h1>
@endsection

@section('content')
    <main>
        <div id="wrapper"> 
            <h2 class="title">ユーザー登録が完了しました。</h2>
            <input type="submit" value="ログイン画面へ" onClick="location.href='{{ route('store.login.form') }}'; return false;">

        </div>
    </main>
@endsection
