@extends('user.layouts.app')

@section('title', '詳細を見る')

@section('header')
    @if($product->type == config('const.product_type_code.fix'))
    <h1 id="title">お店で探す</h1>
    @else
    <h1 id="title">通販で探す</h1>
    @endif
@endsection


@section('content')
    <main>
        <h2 class="title2">{{$store->store_name}}</h2>
        <nav>
            <ul class="nav mb20">
                <li class="active">商品情報</li>
                <li><a href="{{ route('user.stores.store_info', ['store' => $store, 'product_type' => $product->type]) }}">店舗情報</a></li>
            </ul>
        </nav>
        <div class="shop-detail">
            @if(is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))

            <div class="textarea">
                <div class="imagebox">
                    <img src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}">
                </div>
                <table class="table">
                <tbody>
                <tr>
                    <td rowspan="2">
                        <div class="imagebox_sub">
                            @if($product->obj_sub_img->count() > 0)
                            <ul>
                            @foreach($product->obj_sub_img as $obj_sub_img_one)

                            @if(is_object($obj_sub_img_one) && Storage::disk('products')->has("{$product->id}/{$obj_sub_img_one->img_name}"))
                                <li><img class="w250" src="{{ asset("storage/products/{$product->id}/{$obj_sub_img_one->img_name}") }}"> </li>
                            @endif

                            @endforeach
                            </ul>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr>
                    <th>商品名</th>
                    <td>
                        <p class="show-element">{{ isset($product) && isset($product->product_name) ? $product->product_name : '' }}</p>
                    </td>
                </tr>
                <tr>
                    <th>出品理由</th>
                    <td>
                        <p class="show-element" id="list_reason">{{ isset($product) && isset($product->list_reason) ? $product->list_reason : '' }}</p>

                    </td>
                </tr>
                <tr>
                <th>引取可能時間</th>
                <td>
                    @php
                        $available_from = isset($product) && isset($product->available_from) ? Carbon::parse($product->available_from)->format('H:i').' ~ ' : '';
                        $available_to = isset($product) && isset($product->available_to) ? Carbon::parse($product->available_to)->format('H:i') : ''
                    @endphp
                    <p class="show-element">{{ $available_from . $available_to}}</p>

                </td>
                </tr>
                <tr>
                    <th>出品価格</th>
                    <td>
                        <p class="show-element">{{ isset($product->price) ? number_format($product->price).'円' : ''}}</p>
                    </td>
                </tr>
                <tr class="{{ $product->restaurant_kind == 0 ? 'hide' : '' }}">
                    <th>飲食店種類</th>
                    <td> 
                        {{--<div class="radiobtn-wrap f-left">
                            @foreach(config('const.restaurant_kind') as $key => $value)
                                <input type="radio" disabled name="restaurant_kind" id="restaurant_kind{{ $key }}" value="{{ $key }}" {{ $key==$product->restaurant_kind ? 'checked' : '' }}>
                                <label for="restaurant_kind{{ $key }}">{{ $value }}</label>
                            @endforeach
                        </div> --}} 
                        <p class="show-element">{{ isset($product->restaurant_kind) ? config('const.restaurant_kind.'.$product->restaurant_kind) : '' }}</p>
                        @php
                            $restaurant_kind = old('restaurant_kind', isset($product->restaurant_kind) ? $product->restaurant_kind : '1' );
                        @endphp 
                    </td>
                </tr>   
        
                <tr class="view-element restaurant_kind {{ $restaurant_kind == 2 ? '' : 'hide' }}" id='res_kind'>
                    <th>予約金</th>
                    <td>
                        <p class="show-element" id="deposite">{{ isset($product->restaurant_deposit) ? $product->restaurant_deposit.'円' : ''}}</p> 
                    </td>
                </tr>
                <tr>
                <th>掲載期間</th>
                <td>
                    @php
                        $post_from = isset($product) && isset($product->post_from) ? Carbon::parse($product->post_from)->format('Y年m月d日 H:i').' ~ ' : '';
                        $post_to = isset($product) && isset($product->post_to) ? Carbon::parse($product->post_to)->format('Y年m月d日 H:i') : ''
                    @endphp
                    <p class="show-element">{{ $post_from . $post_to}}</p>
                </td>
                </tr>
                </tbody></table>
                <p class="no-cancel"> <a class="modal-close" href="{{ route('user.stores.store_products', ['store' => $store, 'product_type' => $product->type]) }}">戻る</a></p>

            </div>
        @endif
    </main>


@endsection


@section('page_css')
    <link href="{{ asset('assets/vendor/dropzone/dist/basic.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/user/css/pages/product_info.css') }}">
    <style>
        div.plan-section {
            display: inline-flex;
        }
    </style>
@endsection

@section('page_js')
<script>

</script>
@endsection
