@extends('user.layouts.app')

@section('title', 'お支払い完了')

@section('header')
    <h1 id="title">お支払い完了</h1>
@endsection

@section('content')
    <main>
        <div id="wrapper">
            <div class="pay-ok">
                <svg version="1.2" baseProfile="tiny" id="pay-ok" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 83.07 59.11" overflow="visible" xml:space="preserve">
                <polygon fill="#CCC" points="31.04,59.11 0,28.07 7.07,21 31.04,44.96 76,0 83.07,7.07 "></polygon>
                </svg>
            </div>
            <h2 class="title">お支払いが完了しました。</h2>
            <p class="backbtn">
                @if($order_type == config('const.order_type_code.fix'))
                    <a href='{{ route('user.stores.imasugu_search', ['view_kind'=>'map']) }}'>戻る</a>
                @else
                    <a href='{{ route('user.stores.ec_list') }}'>戻る</a>
                @endif
            </p>
        </div>
    </main>
@endsection
