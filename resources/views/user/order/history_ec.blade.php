@extends('user.layouts.app')

@section('title', '注文履歴')

@section('header')
    <h1 id="title">注文履歴</h1>
@endsection

@section('content')
    <main>
            <nav>
                <ul class="nav mb20">
                    <li><a href="{{ route('user.order.history', ['product_type'=>config('const.product_type_code.fix')]) }}">お店で購入したもの</a></li>
                    <li class="active">通販で購入したもの</li>
                </ul>
            </nav>
            <div class="shop-detail">
                @foreach($order_products as $record)
                    <div class="history-box">
                        @if(is_object($record->obj_product->obj_main_img) && Storage::disk('products')->has("{$record->product_id}/{$record->obj_product->obj_main_img->img_name}"))
                            <img src="{{ asset("storage/products/{$record->product_id}/{$record->obj_product->obj_main_img->img_name}") }}">
                        @endif
                    <table class="table3">
                        <tr>
                            <th>取引成立日　</th>
                            <td>{{ $record->created_at ? Carbon::parse($record->created_at)->format('Y年m月d日 H:i') : '' }}</td>
                        </tr>
                        <tr>
                            <th>店舗名</th>
                            <td>{{ is_object($record->obj_store) ?  $record->obj_store->store_name : '' }}</td>
                        </tr>
                        <tr>
                            <th>商品名　</th>
                            <td>{{ $record->obj_product->product_name }} x {{ $record->quantity }}個</td>
                        </tr>
                        <tr>
                            <th>商品価格　</th>
                            <td>{{ number_format($record->total_price) }}円</td>
                        </tr>
                        <tr class="">
                            <th>現在のステータス</th>
                            <td>{{ config('const.e_order_product_status.' .$record->status) }}</td>
                        </tr>

                    </table>
                </div>
                @endforeach
            </div>
    </main>
@endsection
