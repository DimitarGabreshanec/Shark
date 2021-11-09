
@extends('store.layouts.app')

@section('title', 'パスワード変更')

@section('header')
    <h1 id="title">パスワード変更</h1>
@endsection 

@section('content')
    <main> 
        {{ Form::open(['route'=>"store.my_menu.password_update", 'method'=>'put']) }}
        <div class="shop-detail"> 
            <table class="table">
            <tbody>
            @include('store.layouts.flash-message')
            <input type="hidden" name="name" value="{{ old('name', isset($user->name) ? $user->name : '') }}">
            <input type="hidden" name="gender"  value="{{ old('gender', isset($user->gender) ? $user->gender : '') }}">

            <tr>
                <th>先パスワード</th>
                <td>
                    <input type="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="current_password" class="input-text" >
                    @error('current_password')
                        <p class="error">{{ $message }}</p> 
                    @enderror
                </td>
            </tr>

            <tr>
                <th>パスワード</th>
                <td>
                    <input type="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="password" class="input-text" >
                    @error('password')
                        <p class="error">{{ $message }}</p> 
                    @enderror
                </td>
            </tr>
            
            <tr>
                <th>パスワード確認</th>
                <td> 
                    <input type="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="password_confirmation" class="input-text" >
                    @error('password_confirmation')
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
  