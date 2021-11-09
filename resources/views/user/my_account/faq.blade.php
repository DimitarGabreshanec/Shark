@extends('user.layouts.app')

@section('title', 'よくあるご質問')

@section('header')
    <h1 id="title">よくあるご質問</h1>
@endsection 

@section('content')
    <main> 
        {{ Form::open(['route'=>"user.my_account.faq", 'method'=>'get']) }}
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
  