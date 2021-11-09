@extends('user.layouts.auth')

@section('title', '会員登録')

@section('header')
    <h1 id="title">会員登録</h1>
@endsection

@section('content')
    <div id="wrapper">
        <ul class="account-btns">
            <li class="google"><input type="button" value="Googleで登録" onClick="location.href='{{ route('social.login', ['provider'=>'google']) }}'; return false;"></li>
            <li class="facebook"><input type="button" value="Facebookで登録" onClick="location.href='{{ route('social.login', ['provider'=>'facebook']) }}'; return false;"></li>
            <li class="apple"><input type="button" value="Appleで登録" onClick="location.href='{{ route('social.login', ['provider'=>'apple']) }}'; return false;"></li>
            <li class="mail"><input type="button" value="メールアドレスで登録" onClick="location.href='{{ route('user.register.form') }}'; return false;"></li>
            <li class="line"><input type="button" value="LINEで登録" onClick="location.href='{{ route('social.login', ['provider'=>'line']) }}'; return false;"></li>
        </ul>
    </div>
@endsection
