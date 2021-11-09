@extends('user.layouts.auth')

@section('title', 'パスワードをお忘れの方へ')

@section('header')
    <h1 id="title">パスワードをお忘れの方へ</h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['user.password.email'], 'id' => 'frm_register', 'name' => 'frm_register', 'method' => 'POST']) }}
        <div id="wrapper"> 
            <h2 class="title">ご登録のメールアドレスを入力してください。</h2>
            <input type="text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="email" value="{{ old('email') }}">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
            <input type="submit" value="送信する">
        </div>
    {{ Form::close()}}
@endsection
