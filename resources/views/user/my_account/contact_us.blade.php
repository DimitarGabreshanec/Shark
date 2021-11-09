@extends('user.layouts.app')

@section('title', 'お問い合わせ')

@section('header')
    <h1 id="title">お問い合わせ</h1>
@endsection 

@section('content')
    <main> 
        {{ Form::open(['route'=>"user.my_account.faq", 'method'=>'get']) }}
        <div class="update"> 
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
  