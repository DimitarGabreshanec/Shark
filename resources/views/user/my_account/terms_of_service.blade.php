@extends('user.layouts.app')

@section('title', 'ご利用規約')

@section('header')
    <h1 id="title">ご利用規約</h1>
@endsection 

@section('content')
    <main> 
        {{ Form::open(['route'=>"user.my_account.terms_of_service", 'method'=>'get']) }}
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
  