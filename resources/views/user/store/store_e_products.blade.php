@extends('user.layouts.app')

@section('title', '詳細を見る')

@section('header')
    <h1 id="title">通販で探す</h1>
@endsection

@section('content')
    <main>
        <h2 class="title2">{{$store->store_name}}</h2>
            <nav>
                <ul class="nav mb20">
                    <li class="active">商品情報</li>
                    <li><a href="{{ route('user.stores.store_info', ['store' => $store, 'product_type' => $product_type]) }}">店舗情報</a></li>
                </ul>
            </nav>

            <ul class="shop-list-search">
            @foreach($products as $product)
                <li>
                    <div class="imagebox">
                        @if(is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))
                        <a href="{{ route('user.stores.product_info', ['store' => $store, 'product' => $product]) }}">
                            <img src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}" alt={{$product->obj_main_img->img_name}}>
                        </a>
                        @endif
                    </div>
                    <div class="text">
                        <h2 class="goods-name">{{$product->product_name}}</h2>
                        <p class="price">@if($product->getRealPrice())<del>{{ number_format($product->price) }}円</del>@endif<span>{{ number_format($product->getRealPrice()) }}円</span></p>
                        <p class="comment">{{$product->note ? Str::limit($product->note, 50, '...') : ''}}&nbsp;</p>
                        <div class="ammount">数量
                            <input type="number" class="product-quantity" id="product_quantity_{{ $product->id }}" name="product_quantity_[{{ $product->id }}]" value="1">
                        </div>
                        <div class="add-wrap">
                            <p class="add" >買い物かごに<br>追加されました</p>
                        </div>
                        <input type="submit" value="カートに入れる" class="cart" onclick="addCartProduct(event, {{ $product->id }})">
                    </div>
                </li>

            @endforeach
            </ul>
            @if(($products->count() == 0))
                <p class="no_msg">データが存在しません。</p>
            @else
            <div class="fromshopbtn">
                 <input type="submit" id="selectcarts" value="購入手続きへ進む" onclick="location.href='{{ route('user.order.cart', ['order_type' => config('const.order_type_code.ec')]) }}';">
            </div>
            @endif
    </main>

@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/basic.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/user/css/pages/store_product.css') }}">
@endsection

@section('page_js')
    <script>

        $(document).ready(function(e) {
            $('input.product-quantity[type="number"]').change(function(){
                if( parseInt($(this).val()) <= 0 ) {
                    $(this).val(1);
                }
                return true;
            });
        });
    </script>
@endsection
