@extends('admin.layouts.app')

@section('content')
    <h2 class="title">ユーザー情報登録</h2>
    {{ Form::open(["route"=>"admin.users.store", "method"=>"post"]) }}

        @include('admin.users._form') 
        <div class="center-submit double">
            <input type="submit" value="登録"> 
            <input type="button" class="btn-back-index" data-url="{{ route("admin.users.index", $index_params) }}" value="戻る">
        </div>
    {{ Form::close() }}


@endsection

@section('page_css')

@endsection

@section('page_js')

@endsection
