@extends('user.layouts.app')

@section('title', '注文内容')

@section('header')
    @if($order_type == config('const.order_type_code.fix'))
        <h1 id="title">お店注文</h1>
    @else
        <h1 id="title">通販買い物かご</h1>
    @endif
@endsection

@section('content')
    <main>
         <div class="gray-contents">
            <section>
                @foreach($cart_view_data as $record)
                    @php
                        $obj_store = $record['obj_store'];
                    @endphp
                    @isset($obj_store)
                    <h3 class="title">{{ $obj_store->store_name }}</h3>
                    <ul class="shop-list-search2">
                        @foreach($record['products'] as $product_data)
                            @php
                                $obj_product = $product_data['obj_product'];
                                $quantity = $product_data['quantity'];
                            @endphp
                            <li>
                                <div class="pic">
                                    @if(is_object($obj_product->obj_main_img) && Storage::disk('products')->has("{$obj_product->id}/{$obj_product->obj_main_img->img_name}"))
                                        <img class="short" src="{{ asset("storage/products/{$obj_product->id}/{$obj_product->obj_main_img->img_name}") }}" alt={{$obj_product->obj_main_img->img_name}}>
                                    @endif
                                </div>
                                <div class="text">
                                    <h2 class="goods-name">{{ $obj_product->product_name }}</h2>
                                    <p class="price" id="{{'price_' . $obj_product->id }}" data-product_price="{{ $obj_product->getRealPrice() }}" data-ship_price="{{ $obj_product->ship_price }}">{{ number_format($obj_product->getRealPrice()) }}円</p>
                                    @if($obj_product->restaurant_kind==config('const.restaurant_kind_code.seat'))
                                        <p class="price" id="{{'deposit_' . $obj_product->id }}" data-product_price="{{ $obj_product->restaurant_deposit }}"><span>(予約金:&nbsp;{{ number_format($obj_product->restaurant_deposit) }}円)</span></p>
                                    @endif
                                    <div class="ammount">数量
                                        <input class="product-quantity cart-product-quantity" data-is_deposit="{{ $obj_product->restaurant_kind==config('const.restaurant_kind_code.seat') ? 1 : 0 }}" data-order_type="{{ $order_type }}" data-product_id="{{ $obj_product->id }}" id="product_quantity_{{ $obj_product->id }}" type="number" name="products[{{ $obj_product->id }}][quantity]" min="1" value="{{ $quantity }}">
                                    </div>
                                    <input type="button" value="× 削除" class="delete" onclick="location.href='{{ route('user.cart.remove_product', ['order_type' => $order_type, 'product' => $obj_product->id]) }}';">
                                </div>
                            </li>
                        @endforeach
                    </ul>
                    @endisset
                @endforeach

                @if(count($cart_view_data) > 0)
                    <div class="products-price">商品金額:<em class="sub-price" id="products_price"></em>円</div>
                    <div class="tax-price">消費税:<em class="sub-price" id="tax_price"></em>円</div>
                    <div class="ship-price">送料:<em class="sub-price" id="ship_price"></em>円</div>
                    <div class="total">合計金額:<em class="sum_price" id="sum_price"></em>円</div>
                    <input type="submit" id="selectcarts" value="注文内容を確認する" onclick="location.href='{{ route('user.order.cart_confirm', ['order_type'=>$order_type]) }}';">
                @else
                    <p class="no_msg" style="text-align: center">データが存在しません。</p>
                @endif
            </section>
         </div>
    </main>
@endsection

@section('page_js')
    <script>
        $(document).ready(function(e) {
            getTotalPrice();
            $('input.product-quantity[type="number"]').change(function(){
                if( parseInt($(this).val()) <= 0 ) {
                    $(this).val(1);
                }
                getTotalPrice();
                return true;
            });
        });

        function getTotalPrice(){
            var products_price = 0;
            var tax_price = 0;
            var ship_price = 0;
            $('input.product-quantity[type="number"]').each(function(){
                exist_selected = true;
                var price = 0;
                var isDeposit = $('#product_quantity_' + $(this).data('product_id')).data('is_deposit'); 
                if(isDeposit == 1){
                    price = $('#deposit_' + $(this).data('product_id')).data('product_price');
                } else {
                    price = $('#price_' + $(this).data('product_id')).data('product_price');
                }  
                var quantity = $('#product_quantity_' + $(this).data('product_id')).val();
                ship_price = $('#price_' + $(this).data('product_id')).data('ship_price');
                products_price  += parseInt(price) * parseInt(quantity);

            });

            tax_price = Math.round(products_price * parseFloat({{ \App\Service\ConfigurationService::getTaxRate() }}));
            var nf = new Intl.NumberFormat();
            $('#products_price').text(nf.format(products_price));
            $('#tax_price').text(nf.format(tax_price));
            $('#ship_price').text(nf.format(ship_price));
            $('#sum_price').text(nf.format(products_price + tax_price + ship_price));
        }
    </script>
@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/basic.css') }}" rel="stylesheet">
    <style>
        input[type=number]{
            width: 60px;
            text-align: right;
        }
        .shop-list-search2 li {
            width: 100%;
        }
        em.sub-price {
            font-size: 1rem;
            margin: 0 5px;
        }
    </style>
@endsection
