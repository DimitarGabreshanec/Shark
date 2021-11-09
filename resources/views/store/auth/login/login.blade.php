@extends('store.layouts.auth')

@section('title', 'ログイン')

@section('content')
    <h1 id="logo">
        <img src="{{ asset('assets/store/img/logo.png') }}" alt="">
    </h1>

    <main>
    {{ Form::open(['route' => ['store.login'], 'id' => 'frm_login', 'name' => 'frm_login', 'method' => 'post']) }}
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
                <aside class="plus">パスワードをお忘れの方は<a href="{{ route('store.password.change') }}">こちら</a></aside>
                <div class="account">
                    <div class="firsttime">
                            <h2 class="title">初めてご利用の方</h2>
                    <p class="btn"><a href="{{ route('store.register.form') }}">会員登録</a></p>

                    </div>
                </div>
            </div>
        {{ Form::close()}}
    </main>
@endsection
