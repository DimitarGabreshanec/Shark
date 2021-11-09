@extends('admin.layouts.app')

@section('content')
    <h2 class="title">ユーザー編集</h2>
    {{ Form::open(["route"=>["admin.users.update", 'user'=>$user->id], "method"=>"put"]) }}
        <input type="hidden" name="id" value="{{ $user->id }}">
        @include('admin.users._form')
        <div class="center-submit double">
            <input type="submit" value="更新">
            <input type="button" class="btn-back-index" data-url="{{ route("admin.users.index", $index_params) }}" value="戻る">
        </div>
    {{ Form::close() }}
@endsection

@section('page_css')

@endsection

@section('page_js')

@endsection
