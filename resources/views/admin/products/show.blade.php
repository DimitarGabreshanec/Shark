@extends('admin.layouts.app')

@section('content')
    <h2 class="title">商品詳細</h2>
        <table class="normal">

            <tr>
                <th>商品番号</th>
                <td>
                    <p class="show-element">{{ $product->product_no }}</p>
                </td>
            </tr>

            <tr>
                <th>店舗</th>
                <td>
                    <p class="show-element">{{ is_object($product->obj_store) ? $product->obj_store->store_name : '' }}</p>
                     
                </td>
            </tr>
            <tr>
                <th>商品名</th>
                <td>
                    <p class="show-element">{{ $product->product_name }}</p>
                </td>
            </tr>

            <tr>
                <th>商品種類</th>
                <td>
                    <p class="show-element">{{ config('const.product_type.' . $product->type) }}</p>
                </td>
            </tr>

            @if($product->type == 1)
            <tr>
                <th>出品理由</th>
                <td>
                    <p class="show-element text-wrap">{{ $product->list_reason }}</p>
                </td>
            </tr>
            

            <tr>
                <th>引取可能時間</th>
                <td>
                    <p class="show-element">{{ $product->available_from ? Carbon::parse($product->available_from)->format('H:i').' ~ ' : '' }}  {{ $product->available_to ? Carbon::parse($product->available_to)->format('H:i') : '' }}</p>
                </td>
            </tr>

            <tr>
                <th>掲載期間</th>
                <td>
                    <p class="show-element">{{ $product->post_from ? Carbon::parse($product->post_from)->format('Y.m.d H:i').' ~ ' : '' }}  {{ $product->post_to ? Carbon::parse($product->post_to)->format('Y.m.d H:i') : '' }}</p>
                </td>
            </tr> 
            @endif
            @if($product->type == 2)  
            <tr>
                <th>商品紹介</th>
                <td>
                    <p class="show-element text-wrap">{{ $product->introduction }} </p>
                </td>
            </tr>
            <tr>
                <th>在庫数</th>
                <td>
                    <p class="show-element">{{ $product->quantity ? number_format($product->quantity) : '' }} </p>
                </td>
            </tr>
            @endif

            <tr>
                <th>出品価格</th>
                <td>
                    <p class="show-element">{{ $product->price ? number_format($product->price) . '円' : ''}}</p>
                </td>
            </tr>

            <tr>
                <th>送料価格</th>
                <td>
                    <p class="show-element">{{ $product->ship_price ? number_format($product->ship_price) . '円' : ''}}</p>
                </td>
            </tr>

            <tr>
                <th>備考</th>
                <td>
                    <p class="show-element text-wrap">{{ $product->note }}</p>
                </td>
            </tr>

            <tr> 
                <th>メイン画像</th>
                <td> 
                    @if(is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))
                    <div class="imagebox_main">    
                        <img class="w250" src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}">
                    </div>
                    @endif
                </td>
            </tr>

            <tr>
            <th>サブ画像</th>
            <td>
                <div class="imagebox_sub">  
                    @if(isset($product) && $product->obj_sub_img->count() > 0)
                    <ul id="sub_old_img"> 
                        @foreach($product->obj_sub_img as $obj_sub_img_one) 
                        @if(is_object($obj_sub_img_one) && Storage::disk('products')->has("{$product->id}/{$obj_sub_img_one->img_name}"))
                            <li class="li_{{ $obj_sub_img_one->sequence }}">
                                <img  src="{{ asset("storage/products/{$product->id}/{$obj_sub_img_one->img_name}") }}"> 
                            </li>
                        @endif 
                        @endforeach 
                    </ul>
                    @endif
                </div>    
            </td>
            </tr>
            
            <tr>
                <th>登録日時</th>
                <td>
                    <p class="show-element">{{ Carbon::parse($product->created_at)->format('Y.m.d H:i') }}</p>
                </td>
            </tr>


        </table>
        <div class="center-submit double">
            <input type="button" class="btn-back-index" data-url="{{ route("admin.products.index", $index_params) }}" value="戻る">
        </div>
@endsection

@section('page_css')
<link rel="stylesheet" href="{{ asset('assets/admin/css/pages/products_show.css') }}"> 
@endsection

@section('page_js')

@endsection
