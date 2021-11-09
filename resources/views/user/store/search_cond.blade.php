@extends('user.layouts.app')

@section('title', '条件から探す')

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
                    <li><a href="{{ route('user.stores.search.condition') }}" class="active">条件から探す</a></li>
                    <li><a href="{{ route('user.stores.search.map') }}">マップから探す</a></li>
                </ul>
            </nav>
        {{ Form::open(['route'=>"user.stores.search.condition", 'method'=>'get']) }}

            <div class="narrow">
                <table class="table2">
                    <tr id="category">
                        <th>カテゴリ</th>
                        <td>
                            @php
                                $search_params = Session::get('user.stores.search_cond');
                                $search_params['category_id'] = isset($search_params['category_id']) ? $search_params['category_id']: '';
                                $search_params['parent_category'] = isset($search_params['parent_category']) ? $search_params['parent_category']: '';
                                foreach(\App\Service\CategoryService::getAllCategories() as $parent){
                                    $category_value = '';
                                    if($search_params['category_id'] == $parent['id']){
                                        $category_value = $parent['name'];
                                        $category_id = $parent['id'];
                                        break;
                                    } 
                                }
                            @endphp 
                            <select name="search_params[category_id]" id="search_params[category_id]">
                                <option value="0">--</option>
                                @include('user.share._category_list_option', ['parent_id' => 0, 'selected_categories' => $search_params['category_id']])
                            </select>
                        </td>
                    </tr>
                    <tr id="area">
                        <th>エリア</th>
                        <td>
                            @php
                                $search_params['prefecture'] = isset($search_params['prefecture']) ? $search_params['prefecture']: '';
                            @endphp
                            <select name="search_params[prefecture]" id="search_params[prefecture]">
                                <option value="">--</option>
                                @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
                                <option value="{{ $key }}"  {{ $search_params['prefecture']  == $key ?  'selected' : '' }} >{{ $value }}</option>
                                @endforeach
                            </select>
                    </select>
                        </td>
                    </tr>
                    <tr id="price">
                        <th>価格</th>
                        <td>
                            @php
                                $search_params['price_from'] = isset($search_params['price_from']) ? $search_params['price_from']: '';
                                $search_params['price_to'] = isset($search_params['price_to']) ? $search_params['price_to']: '';
                            @endphp
                            <ul class="price">
                                <li>
                                    <input type="text" id="price_from" name=search_params[price_from]' oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('search_params[price_from]', isset($search_params['price_from']) ? $search_params['price_from'] : '') }}">
                                    <span>&nbsp;円</span>
                                </li>
                                <li>～</li>
                                <li>
                                    <input type="text" id="price_to" name=search_params[price_to]' oninput="this.value = this.value.replace(/[^1-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('search_params[price_to]', isset($search_params['price_to']) ? $search_params['price_to'] : '') }}">
                                    <span>&nbsp;円</span>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </table>
                <input class='search' type="submit" value="絞り込む" onclick="collapsePane()">
            </div>
            {{ Form::close() }}

            @if ($stores->count() > 0)
                <div class="gray-contents">
                <p class="hit"><span>{{ $stores->count() }}件ヒットしました</span></p>
                <ul class="shop-list-search2">
                @foreach ($stores as $record)
                <li>
                    <a href="{{ route('user.stores.store_products', ['store'=>$record->id, 'product_type'=>  config('const.product_type_code.fix')]) }}">
                        <div class="pic">
                            <span>{{ number_format($record->getProductCountByType(config('const.product_type_code.fix'))) . '商品'}}</span>
                                 <i class="favorite {{ in_array($record->id, $p_arr_favorite_stores) ? 'active' : '' }}" onclick="toggleFavorite('{{ $record->id }}', '{{config("const.product_type_code.fix")}}')"></i>
                            @if(is_object($record->obj_main_img) && Storage::disk('stores')->has("{$record->id}/{$record->obj_main_img->img_name}"))
                                <img src="{{ asset("storage/stores/{$record->id}/{$record->obj_main_img->img_name}") }}" alt={{$record->obj_main_img->img_name}}>
                            @endif
                        </div>
                        <div class="text">
                            @php
                                if($search_params['category_id'] == 0){
                                    foreach($record->arr_categories() as $categoryId){
                                        $category_id = $categoryId;
                                        foreach(\App\Service\CategoryService::getParentCategoryName($category_id) as $parent){
                                            $category_value = $parent['name'];
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            <i class="category">{{$category_value}}</i>
                            <h2 class="shop-name">{{ isset($record->store_name) ? $record->store_name : '' }}</h2>
                            @php
                                $prefecture = \App\Service\AreaService::getPrefectureNameByID($record->prefecture);
                            @endphp
                            <p class="address">{{ $prefecture }}{{$record->store_address}}</p>
                            <time>営業時間: {{ isset($record->work_from) ? Carbon::parse($record->work_from)->format('H:i') . ' ~ ' : ''}}  {{isset($record->work_to) ? Carbon::parse($record->work_to)->format('H:i') : ''}}</time>
                        </div>
                    </a>
                </li>
                @endforeach
                </ul>
            @else
                <ul class="shop-list-search2">
                <li style="text-align: center">検索結果が存在しません。</li>
                </ul>
            @endif
            </div>
        </div> 
    </main>
@endsection

@section('page_css')
<style>
    #price_from, #price_to {
        padding: 5px;
        background: #FFF;
        border: 1px solid #CCC;
        border-radius: 7px;
        text-align: right;
        width: 78%;
        outline-color: #828282;
    }
</style>
@endsection

@section('page_js')
@endsection
