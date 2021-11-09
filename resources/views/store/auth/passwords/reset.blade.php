@extends('store.layouts.auth')

@section('title', 'パスワード再設定')

@section('content')
    {{ Form::open(['route' => ['store.password.update'], 'id' => 'frm_update_password', 'name' => 'frm_update_password', 'method' => 'POST']) }}
    <div id="wrapper" class="contents">
        <input type="hidden" name="token" value="{{ $token }}">

        <table class="table">
            <tr>
                <th>メールアドレス</th>
                <td>
                    <input id="email" name="email" type="text"  readonly value="{{ $email }}">
                    @error('email')
                    <p class="error">{{ $message }}</p>
                    @enderror
                </td>
            </tr>
            <tr>
                <th>新しいパスワード</th>
                <td>
                    <input id="password" name="password" required type="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                    @error('password')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </td>
            </tr>
            <tr>
                <th>確認用パスワード</th>
                <td>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                </td>
            </tr>
        </table>
        <input type="submit" value="変更する">
    </div>
    {{ Form::close()}}
@endsection
