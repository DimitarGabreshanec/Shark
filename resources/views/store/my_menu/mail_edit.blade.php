@extends('store.layouts.app')

@section('title', 'メールアドレス変更')

@section('header')
    <h1 id="title">メールアドレス変更</h1>
@endsection

@section('content')
    <main>
        {{ Form::open(['route'=>"store.my_menu.mail_update", 'method'=>'put']) }}
        <input type="hidden" name="id" value="{{ $store->id }}">
        <div class="shop-detail">
            <table class="table">
            <tbody>
            @include('store.layouts.flash-message')
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        <p class="show-element">{{ $store->email }}</p>
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス</th>
                    <td>
                        <input type="text" name="email" value="{{ old('email') }}" class="input-text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
                        @error('email')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr>
                    <th>メールアドレス確認</th>
                    <td>
                        <input type="text" name="email_confirmation" value="{{ old('email_confirmation') }}" class="input-text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$">
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
