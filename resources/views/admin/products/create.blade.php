@extends('admin.layouts.app')

@section('content')
    <h2 class="title">商品情報登録</h2>
    @php 
        $mode = 'create';
    @endphp
    {{ Form::open(["route"=>"admin.products.store", "method"=>"post", 'files'=>true]) }}

        @include('admin.products._form') 

        <div class="center-submit double">
            <input type="submit" value="登録">
            <input type="button" class="btn-back-index" data-url="{{ route("admin.products.index", $index_params) }}" value="戻る">
        </div>
    {{ Form::close() }}


@endsection

@section('page_css')

@endsection

@section('page_js')

@endsection
