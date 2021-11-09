@extends('user.layouts.app')

@section('title', 'イマスグを探す')

@section('header')
    @php
        $back = isset($back) ? $back : 'map';
    @endphp
    @if($back == 'account')
    <h1 id="title">イマスグ表示条件</h1>
    @else
    <h1 id="title">お店で探す</h1>
    @endif
@endsection

@section('content')
    <main>
        {{ Form::open(["route"=>['user.stores.imasugu_search', ['view_kind' => $back]], "method"=>"get"]) }}
        @php
            $empty_categories = isset($selected_categories) ? count($selected_categories) : 0;
        @endphp 
            <div id="wrapper">
                @if ($message = Session::get('success'))
                <h2 class="title">{{ $message }}</h2>
                @else
                <h2 class="title">イマスグを受け取る</h2>
                @endif
                <p>※イマスグとは近くのエリア（半径5km圏内）で新しい商品が出品された際に発信される通知のことです。</p>
                <div class="get">
                    <ul class="line">
                        @foreach(config('const.imasugu_type') as $key => $value)
                        <li>
                            <input type="radio" name="check" id="check{{ $key }}" value="{{ $key }}" {{ (string)$key == (isset($check) ? $check : '') ? 'checked' : '' }}>
                            <label for="check{{ $key }}" class="switch-on">{{ $value }}</label>
                        </li>
                        @endforeach

                    </ul>
                </div>
            </div>
            <div class="gray-contents">
                <h2 class="title">カテゴリ選択</h2>
                <ul class="acc">
                    @include('user.share._category_list_li', ['parent_id' => 0, 'selected_categories' => $selected_categories, 'disabled' => ''])
                </ul>
                @if($empty_categories === 0)
                <input type="submit" value="保存する">
                @else
                <input type="submit" value="保存する">
                @endif
            </div>
        {{ Form::close() }}
    </main>
@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/user/css/pages/imasugu_change.css') }}">
@endsection

@section('page_js') 
<script>
    $(document).ready(function(){
        $(".check-parent").change(function () { 
            var parent_id = $(this).val();  
            $("input.node-parent-" + parent_id + ":checkbox").prop('checked', $(this).prop("checked"));  
            $("input.node-parent-" + parent_id + ":checkbox").change();  

            var parent_id = $(this).data('parent'); 
            if($("input.node-parent-" + parent_id + ":checked").length == $("input.node-parent-" + parent_id).length){
                $("input#category_" + parent_id + ":checkbox").prop('checked',true);   
                selChanged($("input#category_" + parent_id + ":checkbox"));
            }else{
                $("input#category_" + parent_id + ":checkbox").prop('checked',false);
                selChanged($("input#category_" + parent_id + ":checkbox"));
            }   
        });

        function selChanged(category){
            var parent_id = category.data('parent');  
            if($("input.node-parent-" + parent_id + ":checked").length == $("input.node-parent-" + parent_id).length){
                $("input#category_" + parent_id + ":checkbox").prop('checked',true);   
            }else{
                $("input#category_" + parent_id + ":checkbox").prop('checked',false);
            } 
        }  
    });
</script>
@endsection
