@extends('store.layouts.app')

@section('title', '注文履歴')

@section('header')
    <h1 id="title">注文履歴</h1>
@endsection


@section('content')
    <main>
        <nav id="sticky">
        <ul class="nav mb20">
            <li class="active">商品引取</li>
            <li><a href="{{ route('store.history.ec') }}">通販</a></li>
        </ul>
        </nav>

        <div class="shop-detail">
            @foreach($order_products as $record)
                <div class="history-box">
                    @if(is_object($record->obj_product->obj_main_img) && Storage::disk('products')->has("{$record->product_id}/{$record->obj_product->obj_main_img->img_name}"))
                        <img src="{{ asset("storage/products/{$record->product_id}/{$record->obj_product->obj_main_img->img_name}") }}">
                    @endif
                    <table class="table3">
                        <tbody>
                            <tr>
                                <th>取引成立日</th>
                                <td>{{ $record->created_at ? Carbon::parse($record->created_at)->format('Y年m月d日 H:i') : '' }}</td>
                            </tr>
                            <tr>
                                <th>商品名</th>
                                <td>{{ $record->obj_product->product_name }} x {{ $record->quantity }}個</td>
                            </tr>
                            <tr>
                                <th>商品価格　</th>
                                <td>{{ number_format($record->total_price) }}円</td>
                            </tr>
                            <tr>
                                <th>顧客名</th>
                                <td>{{ $record->obj_user->name }}</td>
                            </tr>
                            <tr>
                                <th>顧客電話番号</th>
                                <td>{{ $record->obj_user->tel }}</td>
                            </tr>
                            <tr class="red">
                                <th>現在のステータス</th>
                                @if(!$record->status)
                                    <td>受付待ち</td>
                                @else
                                    <td>{{ config('const.f_order_product_status.' . $record->status) }}</td>
                                @endif
                            </tr>
                        </tbody>
                    </table>
                    @if(!$record->status)
                        <input type="submit" value="「受付済み」に変更する" class="btn-set-order-complete" data-message="受付済みに変更します。" data-name="{{ $record->obj_product->product_name }}" data-id="{{ $record->id }}" id="btn_set_compete{{$record->id}}">
                        <form id="frm_complete_{{ $record->id }}" action="{{ route('store.history.set_complete', ['order_product'=> $record->id]) }}" method="POST" style="display: none;">
                            @csrf
                            @method('post')
                        </form>
                    @endif
                </div>
            @endforeach
            @if($order_products->count() == 0)
                <p style="text-align: center; width:100%;" class="no_msg">データが存在しません。</p>
            @endif
        </div>
    </main>

@endsection

