@extends('admin.layouts.app')

@section('content')
    <h2 class="title">注文詳細</h2>

    <table class="normal">
        <tr>
            <th>注文番号</th>
            <td>{{ $order->order_no }}</td>
        </tr>

        <tr>
            <th>商品</th>
            <td>
                @foreach($order->arr_order_products() as $product)
                    <a href="{{ route('admin.products.show', ['product'=> $product['id']]) }}" target="_blank">{{ $product['name'] . '（' . $product['quantity'] . 'つ）' }}</a><br>
                @endforeach
            </td>
        </tr>

        <tr>
            <th>送料</th>
            <td>{{ $order->ship_price ? ('￥' . number_format($order->ship_price)) : '' }}</td>
        </tr>

        <tr>
            <th>消費税</th>
            <td>{{ $order->tax_price ? ('￥' . number_format($order->tax_price)) : '' }}</td>
        </tr>

        <tr>
            <th>注文金額</th>
            <td>{{ $order->order_price ? ('￥' . number_format($order->order_price)) : '' }}</td>
        </tr>

        <tr>
            <th>注文者</th>
            <td>{{ $order->obj_user->name }}</td>
        </tr>

        <tr>
            <th>注文日時</th>
            <td>{{ $order->ordered_at ? Carbon::parse($order->ordered_at)->format('Y.m.d H:i:s') : ''  }}</td>
        </tr>

        <tr>
            <th>引受者名</th>
            <td>{{ $order->target_client() }}</td>
        </tr>

        <tr>
            <th>引受者名（フリガナ）</th>
            <td>{{ $order->target_client_kana() }}</td>
        </tr>

        <tr>
            <th>電話番号</th>
            <td>{{ $order->tel }}</td>
        </tr>

        <tr>
            <th>郵便番号</th>
            <td>{{ $order->target_zip() }}</td>
        </tr>
        <tr>
            <th>住所</th>
            <td>{{ $order->target_address() }}</td>
        </tr>
        <tr>
            <th>ステータス</th>
            <td>{{ config('const.order_status.' . $order->order_status)}}</td>
        </tr>

        <tr>
            <th>メモ</th>
            <td>{{ $order->order_note }}</td>
        </tr>
    </table>
    <div class="center-submit double">
        <input type="button" class="btn-back-index" data-url="{{ route("admin.orders.index", $index_params) }}" value="戻る">
    </div>
@endsection

