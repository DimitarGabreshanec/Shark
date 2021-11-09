@extends('store.layouts.app')

@section('title', '通販商品')

@section('header')
    <h1 id="title">通販商品</h1>
@endsection


@section('content')
    <main>
        <nav id="sticky">
            <ul class="nav">
                <li ><a href="{{ route('store.ec_products.create', ['product_type' => config('const.product_type_code.ec')])}}">商品情報登録</a></li>
                <li class="active">商品情報一覧</li>
            </ul>
        </nav>
        <div id="wrapper2">
            @include('store.layouts.flash-message')
            <ul class="shop-list-search">
                @foreach($products as $product)
                @if(is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))
                <li>
                    <div class="imagebox">
                        <a data-target="{{ $product->id}}" class="modal-open">
                            <img src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}">
                        </a>
                    </div>

                </li>
                @endif
                @endforeach
            </ul>
            
            @if($products->count() == 0)
                <p style="text-align: center;">データが存在しません。</p>
            @endif
        </div>
    </main>
    @foreach($products as $product)
    @if(is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))
        <div id="{{ $product->id}}" class="modal-content">
            <div class="textarea">
                <div class="imagebox">
                    <img src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}">
                </div>
                <table class="table">
                <tbody><tr>
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
                    <th>商品紹介</th>
                    <td>
                        <p class="show-element" id="list_reason">{{ isset($product) && isset($product->introduction) ? $product->introduction : '' }}</p>
                    </td>
                </tr>
                <tr>
                    <th>商品価格</th>
                    <td>
                        <p class="show-element">{{ isset($product) && isset($product->price) ? $product->price : '' }}</p>
                    </td>
                </tr>
                <tr>
                    <th>在庫数</th>
                    <td>
                        <p class="show-element">{{ isset($product) && isset($product->quantity) ? $product->quantity : '' }}</p>
                    </td>
                </tr>

                </tbody></table>
                <input type="submit" value="編集する" onClick="location.href='{{ route('store.ec_products.edit', ['product' => $product, 'product_type' => config('const.product_type_code.ec')]) }}'; return false;">
                <p class="no-cancel"><a class="modal-close">×編集せずに閉じる</a></p>
            </div>
        </div>
    @endif
    @endforeach
@endsection



@section('page_css')
<link rel="stylesheet" href="{{ asset('assets/store/css/pages/products_show.css') }}">
@endsection


@section('page_js')
<script>
    function gotoEdit(button){
        button.value = "保存する";
        var id_value = $(button).data('id_value');
        document.getElementById('product_name_' + id_value).readOnly = false;
    }
</script>
@endsection
