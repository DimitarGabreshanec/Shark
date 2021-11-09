@extends('user.layouts.app')

@section('title', 'プライバシーポリシー')

@section('header')
    <h1 id="title">プライバシーポリシー</h1>
@endsection 

@section('content')
    <main> 
        {{ Form::open(['route'=>"user.my_account.privacy_police", 'method'=>'get']) }}
        <div class="shop-detail"> 
            <table class="table">
            <tbody>
             

            <tr>
                <th></th>
                <td>
                     
                </td>
            </tr>

 
            </tbody>
            </table>
        </div> 
        {{ Form::close() }}
    </main>

@endsection

@section('page_css')

@endsection
  