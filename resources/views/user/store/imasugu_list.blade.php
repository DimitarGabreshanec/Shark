@extends('user.layouts.app')

@section('title', 'イマスグを探す')

@section('header')
    <h1 id="title">お店で探す</h1>
@endsection

@section('content')
    <main>
        <nav>
            <ul class="nav">
                <li class="active">イマスグを探す</li>
                <li><a href="{{ route('user.stores.search')  }}">お店を探す</a></li>
            </ul>
        </nav>
        <p class="search-map-btn"><a href="{{ route('user.stores.imasugu_search', ['view_kind'=>'map'])  }}">マップから探す</a></p>
        <p class="change-btn"><a href="{{ route('user.stores.imasugu_cond', ['back'=>'list'])  }}">表示するカテゴリを変更する</a></p>
        @php
            $parentName = isset($parentName) ? $parentName : '';
            $stores = \App\Service\StoreService::getAllImasuguStores();
        @endphp
        @isset($stores)
        <ul class="shop-list-search">
        @foreach($stores as $store)
        @php
            $select_cateories = $search_params['categories'];
            $select_cateories = $search_params['categories'];
            foreach($store->arr_categories() as $value){
                if(in_array($value, $select_cateories)){
                    $parentName = $value;
                    $parent = \App\Service\CategoryService::getCategoryName($parentName);
                    $parentName = $parent[0]; 
                    break;
                }
            }  
            $my_lat = isset($self_pos['lat']) ? $self_pos['lat'] : null;
            $my_lng = isset($self_pos['lng']) ? $self_pos['lng'] : null;
            $position = json_decode($store->position, true);
            $lat= isset($position['lat']) ? $position['lat'] : null;
            $lng = isset($position['lng']) ? $position['lng'] : null;
            //$distance = \App\Service\StoreService::mile_distance($my_lat, $my_lng, $lat, $lng);
        @endphp 
        <li><a href="{{ route('user.stores.store_products', ['store' => $store, 'product_type'=> config('const.product_type_code.fix')]) }}">
                <div class="pic"><span>{{number_format($store->getProductCountByType(config('const.product_type_code.fix'))) . '商品'}}</span>
                <i class="favorite {{ in_array($store->id, $p_arr_favorite_stores, config('const.product_type_code.fix')) ? 'active' : '' }}" onclick="toggleFavorite('{{ $store->id }}', '{{config("const.product_type_code.fix")}}')"></i>
                    @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                        <img class="short" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}" alt={{$store->obj_main_img->img_name}}>
                    @endif
                </div>
                <div class="text"><i class="category">{{$parentName}}</i>
                    <h2 class="shop-name">{{$store->store_name}}</h2>
                    <p class="address">{{ \App\Service\AreaService::getPrefectureNameByID($store->prefecture) }}{{$store->store_address}}</p>
                    <time>営業時間: {{ isset($store->work_from) ? Carbon::parse($store->work_from)->format('H:i') . ' ~ ' : ''}}  {{isset($store->work_to) ? Carbon::parse($store->work_to)->format('H:i') : ''}}</time>
                </div>
            </a>
        </li> 
        @endforeach
        @if(!isset($stores) || sizeof($stores) == 0)
        <p style="text-align: center; width:100%" class="no_msg">検索結果が存在しません。</p>
        @endif
    </ul>
    
    @endisset
    </main>

@endsection

@section('page_js')

@endsection

@section('page_css')
<style>
    .no_msg{
        text-align: center;
    }
</style>
@endsection
