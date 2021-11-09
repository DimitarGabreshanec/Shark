@extends('admin.layouts.app')

@section('content')
    <h2 class="title">店舗情報登録</h2>
    @php 
        $mode = 'create';
    @endphp
    {{ Form::open(["route"=>"admin.stores.store", "method"=>"post", 'files'=>true]) }}

        @include('admin.stores._form') 
        <div class="center-submit double">
                <input type="submit" value="登録"> 
                <input type="button" class="btn-back-index" data-url="{{ route("admin.stores.index", $index_params) }}" value="戻る">
            </div>
    {{ Form::close() }}


@endsection

@section('page_css')

@endsection

@section('page_js')

@endsection
