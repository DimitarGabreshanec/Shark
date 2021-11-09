@extends('user.layouts.app')

@section('title', 'メールアドレス変更')

@section('header')
    <h1 id="title">メールアドレス変更</h1>
@endsection

@section('content')
    <main>
        {{ Form::open(['route'=>"user.my_account.mail_update", 'method'=>'put']) }}
        <input type="hidden" name="id" value="{{ $user->id }}">

        <div class="shop-detail">
            <table class="table">
            <tbody>
            @include('user.layouts.flash-message')
            <input type="hidden" name="name" value="{{ old('name', isset($user->name) ? $user->name : '') }}">
            <input type="hidden" name="gender"  value="{{ old('gender', isset($user->gender) ? $user->gender : '') }}">
            <input type="hidden" name="password"  value="{{ old('password', isset($user->password) ? $user->password : '') }}">
            <input type="hidden" name="password_confirmation"  value="{{ old('password', isset($user->password) ? $user->password : '') }}">
                <tr>
                    <th>メールアドレス</th>
                    <td>
                    @if ($email = Session::get('email'))
                        <p class="show-element">{{ $email }}</p>
                    @else
                        <p class="show-element">{{ old('email', isset($user->email) ? $user->email : '') }}</p>
                    @endif
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        <input type="text" name="email" class="input-text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                        @error('email')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス確認</th>
                    <td>
                        <input type="text" name="email_confirmation" class="input-text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                        @error('email_confirmation')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                </tbody>
            </table>
            </div>
            <input type="submit" value="更新">
            {{ Form::close() }}
    </main>

@endsection

@section('page_css')

@endsection
