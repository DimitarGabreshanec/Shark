@extends('store.layouts.auth')
@section('title', '会員登録')

@section('header')
    <h1 id="title">会員登録</h1>
@endsection
  

@section('content')
    {{ Form::open(['route' => ['store.register'], 'id' => 'frm_register', 'name' => 'frm_register', 'method' => 'POSt']) }}
        <div id="wrapper">
            <h2 class="title">メールアドレスを入力してください。</h2>
            <input type="text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="email" value="{{ old('email') }}">
            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
            <input type="submit" value="送信する">
        </div>
    {{ Form::close()}}
@endsection
