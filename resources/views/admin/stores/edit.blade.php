@extends('admin.layouts.app')

@section('content')
    <h2 class="title">店舗情報編集</h2>
    @php
        $mode = 'edit';
    @endphp
    {{ Form::open(["route"=>["admin.stores.update", 'store'=>$store->id], "method"=>"put", 'files'=>true]) }}
        <input type="hidden" name="id" value="{{ $store->id }}">
        @include('admin.stores._form')
        <div class="center-submit double">
            <input type="submit" value="更新">
            <input type="button" class="btn-back-index" data-url="{{ route("admin.stores.index", $index_params) }}" value="戻る">
        </div>
    {{ Form::close() }}
@endsection

@section('page_css')

@endsection

@section('page_js')

@endsection
