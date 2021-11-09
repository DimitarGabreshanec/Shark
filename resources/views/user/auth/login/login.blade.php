@extends('user.layouts.auth')

@section('title', 'ログイン')

@section('header')
    <h1 id="title">ログイン</h1>
@endsection

@section('content')
    {{ Form::open(['route' => ['user.login'], 'id' => 'frm_login', 'name' => 'frm_login', 'method' => 'POST']) }}
        <div id="wrapper" class="contents">
            <table class="table">
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        <input type="text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="email" value="{{ old('email') }}">
                        @error('email')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>パスワード</th>
                    <td>
                        <input type="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="password">
                        @error('password')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>

            </table>
            <input type="submit" value="ログイン">
            <aside class="plus">パスワードをお忘れの方は<a href="{{ route('user.password.change') }}">こちら</a></aside>
        </div>
    {{ Form::close()}}
@endsection
