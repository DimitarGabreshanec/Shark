@extends('user.layouts.app')

@section('title', 'お店で探す')

@section('header')
    <h1 id="title">お店で探す</h1>
@endsection

@section('content')
    <main>
            <nav>
                <ul class="nav">
                    <li><a href="{{ route('user.stores.imasugu_search', ['view_kind'=>'map'])  }}">イマスグを探す</a></li>
                    <li class="active">お店を探す</li>
                </ul>
            </nav>
            <nav>
                <ul class="morenav">
                    <li><a href="{{ route('user.stores.search.condition') }}">条件から探す</a></li>
                    <li><a href="{{ route('user.stores.search.map') }}">マップから探す</a></li>
                </ul>
            </nav>
            <ul class="shop-list-search">
                @php
                    $category_id = isset($category_id) ? $category_id : 1;
                    $parentName = isset($parentName) ? $parentName : '';
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
                        <i class="favorite {{ in_array($store->id, $p_arr_favorite_stores, config('const.product_type_code.fix')) ? 'active' : '' }}" onclick="toggleFavorite('{{ $store->id }}', '{{config("const.product_type_code.fix")}}')"></i>
                            @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                                <img class="short" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}" alt={{$store->obj_main_img->img_name}}>
                            @endif
                        </div>
                        <div class="text"><i class="category">{{$parentName}}</i>
                            <h2 class="shop-name">{{$store->store_name}}</h2>
                            @php
                                $prefecture = \App\Service\AreaService::getPrefectureNameByID($store->prefecture);
                            @endphp
                            <p class="address">{{ $prefecture }}{{ $store->store_address }}</p>
                            <time>営業時間: {{ isset($store->work_from) ? Carbon::parse($store->work_from)->format('H:i') . ' ~ ' : ''}}  {{isset($store->work_to) ? Carbon::parse($store->work_to)->format('H:i') : ''}}</time>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
    </main>

    <!--MODAL -->
    <div id="shop-detail" class="modal-content">
        <div class="textarea"> <a href="{{ route('user.stores.store_products', ['store' => 'store', 'product_type'=> config('const.product_type_code.fix')]) }}">
                <div class="pic"><span>5商品</span>
                    {{--@if(is_object($store) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                        <img class="short" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}" alt={{$record->obj_main_img->img_name}}>
                    @endif  --}}
                </div>
                <div class="text"><i class="category">ケーキ</i>
                    <h2 class="shop-name">ラ・メゾン・ジュヴォー広尾店</h2>
                    <p class="address">東京都港区南麻布5-10-24</p>
                    <time>営業時間: 10:00～20:00</time>
                </div>
            </a>
            <p class="closemodal"><a class="modal-close">×</a></p>
        </div>
    </div>
    <!-- -->
@endsection

@section('page_js')
@endsection
