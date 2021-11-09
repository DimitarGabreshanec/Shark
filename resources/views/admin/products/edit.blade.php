@extends('admin.layouts.app')

@section('content')
    <h2 class="title">商品情報編集</h2> 
    @php 
        $mode = 'edit';
    @endphp
    {{ Form::open(["route"=>["admin.products.update", 'product'=>$product->id], "method"=>"put", 'files'=>true]) }}
        @include('admin.products._form')
        <div class="center-submit double">
            <input type="submit" value="更新">
            <input type="button" class="btn-back-index" data-url="{{ route("admin.products.index", $index_params) }}" value="戻る">
        </div>
    {{ Form::close() }}
@endsection

@section('page_css')
 
@endsection

@section('page_js') 

@endsection
