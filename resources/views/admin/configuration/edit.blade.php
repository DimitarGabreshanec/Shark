@extends('admin.layouts.app')

@section('content')
    <h2 class="title">設定管理</h2>
    @php 
        $mode = 'edit';
    @endphp
    {{ Form::open(["route"=>"admin.configuration.update", "method"=>"put"]) }}
        @include('admin.configuration._form')
        <div class="center-submit double">
            <input type="submit" value="更新">
        </div>
    {{ Form::close() }}
@endsection

@section('page_css')
 
@endsection

@section('page_js')
 
@endsection