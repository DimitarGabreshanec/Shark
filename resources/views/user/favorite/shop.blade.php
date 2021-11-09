@extends('user.layouts.app')

@section('title', 'お気に入り')

@section('header')
    <h1 id="title">お気に入り</h1>
@endsection

@section('content')
    <main>
            <nav>
                <ul class="nav mb20">
                    <li class="active">お店</li>
                    <li><a href="{{ route('user.favorite.index', ['product_type' => config('const.product_type_code.ec')]) }}">通販</a></li>
                </ul>
            </nav>
            <ul class="shop-list-search">
                @php
                    $category_id = isset($category_id) ? $category_id : 1;
                    $parentName = isset($parentName) ? $parentName : '';
                    $stores = \App\Service\StoreService::getFavoriteStores(config('const.product_type_code.fix'));
                @endphp
                @foreach($stores as $store)
                    @php
                        foreach($store->arr_categories() as $categoryId){
                            $category_id = $categoryId;
                            foreach(\App\Service\CategoryService::getParentCategoryName($category_id) as $parent){
                                    $parentName = $parent['name'];
                            }
                            break;
                        }
                    @endphp
                <li><a href="{{ route('user.stores.store_products', ['store' => $store, 'product_type'=> config('const.product_type_code.fix')]) }}">
                        <div class="pic"><span>{{number_format($store->getProductCountByType(config('const.product_type_code.fix'))) . '商品'}}</span>
                        <i class="favorite active" onclick="location.href='{{ route('user.favorite.index', ['product_type'=> config('const.product_type_code.fix')]) }}';toggleFavorite('{{ $store->id }}', '{{config("const.product_type_code.fix")}}')"></i>

                            @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                                <img class="short" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}" alt={{$store->obj_main_img->img_name}}>
                            @endif
                        </div>
                        <div class="text"><i class="category">{{$parentName}}</i>
                            <h2 class="shop-name">{{$store->store_name}}</h2>
                            <p class="address">{{ \App\Service\AreaService::getPrefectureNameByID($store->prefecture) }}{{ $store->store_address }}</p>
                            <time>営業時間: {{ isset($store->work_from) ? Carbon::parse($store->work_from)->format('H:i') . ' ~ ' : ''}}  {{isset($store->work_to) ? Carbon::parse($store->work_to)->format('H:i') : ''}}</time>
                        </div>
                    </a>
                </li>
                @endforeach
                @if(($stores->count() == 0))
                <p style="text-align: center; width:100%" class="no_msg">データが存在しません。</p>
                @endif
            </ul> 
            
    </main>

@endsection

@section('page_js')
@endsection

@section('page_css')
<style>
    .no_msg
    {
        text-align: center;
    }
</style>
@endsection
