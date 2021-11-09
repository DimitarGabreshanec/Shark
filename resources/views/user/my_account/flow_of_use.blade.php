@extends('user.layouts.app')

@section('title', 'ご利用の流れ')

@section('header')
    <h1 id="title">ご利用の流れ</h1>
@endsection 

@section('content')
    <main> 
        {{ Form::open(['route'=>"user.my_account.flow_of_use", 'method'=>'get']) }}
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
  