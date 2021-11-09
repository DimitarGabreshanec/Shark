@extends('user.layouts.app')

@section('title', '詳細を見る')

@section('header')
    <h1 id="title">お店で探す</h1>
@endsection


@section('content')
    <main>
    {{Form::open(["route"=>['user.order.set_f_products', ['store' => $store]], "method"=>"post"]) }}
        <h2 class="title2">{{$store->store_name}}</h2>
        <nav>
            <ul class="nav mb20">
                <li class="active">商品情報</li>
                <li><a href='{{ route('user.stores.store_info', ['store' => $store, 'product_type' => $product_type]) }}'>店舗情報</a></li>
            </ul>
        </nav>
        @php
            $total_price = 0;
        @endphp
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
                    @php
                        $real_price = $product->getRealPrice();
                    @endphp
                    <p class="price" id="{{'price_' . $product->id }}" data-product_price="{{ $real_price }}">@if($real_price != $product->price)<del>{{ number_format($product->price) }}円</del>@endif<span>{{ number_format($real_price) }}円</span></p>
                    @if($product->restaurant_kind==config('const.restaurant_kind_code.seat'))
                        <p class="price" id="{{'deposit_' . $product->id }}" data-product_price="{{ $product->restaurant_deposit }}"><span>(予約金:&nbsp;{{ number_format($product->restaurant_deposit) }}円)</span></p>
                    @endif
                    <p class="comment">{{$product->note ? Str::limit($product->note, 50, '...') : ''}}&nbsp;</p>
                    <div class="ammount">数量
                        <input class='product-quantity' data-is_deposit="{{ $product->restaurant_kind==config('const.restaurant_kind_code.seat') ? 1 : 0 }}" data-product_id="{{ $product->id }}" id="product_quantity_{{ $product->id }}" name="products[{{ $product->id }}][quantity]" min="1" style="text-align: right" type="number" value={{ array_key_exists($product->id, $selected_products) ? $selected_products[$product->id]['quantity'] : 1 }}></div>
                    <div class="check-wrap">
                        <input type="checkbox" class="select-product" data-product_id="{{ $product->id }}" name="products[{{ $product->id }}][checked]" id="{{'checkbox_' . $product->id }}" {{ array_key_exists($product->id, $selected_products) ? 'checked' :'' }}><label for="{{'checkbox_' . $product->id }}">選択する</label>
                    </div>
                </div>
            </li>
        @endforeach
        </ul>
        @if($products->count() == 0)
            <p style="text-align: center;" class="no_msg">データが存在しません。</p>
        @else
            <div class="fromshopbtn">
                <div class="total">合計金額:<em class="sum_price" id="sum_price" >{{ $total_price }}</em>円</div>
                <input type="submit" id="btn_next_step" {{ $total_price == 0 ? 'disabled' : 'enabled' }} style="opacity : {{ $total_price == 0 ? 0.3 : 1.0 }}" value="次に進む">
            </div>
        @endif
        {{ Form::close() }}
    </main>
@endsection

@section('page_js')
<script>

    $(document).ready(function(e) {
        getTotalPrice();

        $('input.select-product[type="checkbox"], input.product-quantity[type="number"]').change(function(){
            getTotalPrice();
            return true;
        });
    });

    function getTotalPrice(){
        var total_price = 0;
        var exist_selected = false;
        $('input.select-product[type="checkbox"]:checked').each(function(){
            exist_selected = true; 
            var isDeposit = $('#product_quantity_' + $(this).data('product_id')).data('is_deposit');  
            var price = 0;
            if(isDeposit == 1){
                price = $('#deposit_' + $(this).data('product_id')).data('product_price');
            } else {
                price = $('#price_' + $(this).data('product_id')).data('product_price');
            } 
            var quantity = $('#product_quantity_' + $(this).data('product_id')).val(); 
            total_price  += parseInt(price) * parseInt(quantity);
        });

        if(exist_selected){
            $('#btn_next_step').prop('disabled', false);
            $('#btn_next_step').css('opacity', '1.0')
        } else{
            $('#btn_next_step').prop('disabled', true);
            $('#btn_next_step').css('opacity', '0.3')
        }
        var nf = new Intl.NumberFormat();
        $('#sum_price').text(nf.format(total_price));
    }
</script>
@endsection

@section('page_css')
<link href="{{ asset('assets/admin/css/basic.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/user/css/pages/store_product.css') }}">
@endsection


