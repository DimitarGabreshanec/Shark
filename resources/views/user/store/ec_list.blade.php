@extends('user.layouts.app')

@section('title', '通販で探す')

@section('header')
    <h1 id="title">通販で探す</h1>
@endsection

@section('content')
    <main>
        <div class="gray-contents">
        {{ Form::open(['route'=>"user.stores.ec_list", 'method'=>'get']) }}
                <dl class="matching">
                    <dt>条件を絞り込む</dt>
                    @php
                        $search_params['category_id'] = isset($search_params['category_id']) ? $search_params['category_id']: '';
                        $search_params['parent_category'] = isset($search_params['parent_category']) ? $search_params['parent_category']: '';
                        $search_params['price_from'] = isset($search_params['price_from']) ? $search_params['price_from']: '';
                        $search_params['price_to'] = isset($search_params['price_to']) ? $search_params['price_to']: '';
                    @endphp
                    <dd style="{{ ($search_params['category_id'] == 0 && $search_params['price_from'] <= 0 && $search_params['price_to'] <= 0) ? '' : 'display: block;'}}">
                        <div class="narrow">
                        <table class="table2">
                                <tr id="category">
                                    <th>カテゴリ</th>
                                    <td>
                                    <select name="search_params[category_id]" id="search_params[category_id]">
                                        <option value="0">--</option> 
                                        @php
                                        foreach(\App\Service\CategoryService::getAllCategories() as $parent){
                                            $category_value = '';
                                            if($search_params['category_id'] == $parent['id']){
                                                $category_value = $parent['name'];
                                                $category_id = $parent['id'];
                                                break;
                                            } 
                                        }
                                        @endphp 
                                        @include('user.share._category_list_option', ['parent_id' => 0, 'selected_categories' => $search_params['category_id']])
                                         
                                    </select>
                                    </td>
                                </tr>
                                <tr id="price">
                                    <th>価格</th>
                                    <td>
                                        <ul class="price">
                                            <li>
                                                <input type="text" id="text_price" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" name=search_params[price_from]' value="{{ old('search_params[price_from]', isset($search_params['price_from']) ? $search_params['price_from'] : '') }}">
                                                <span>&nbsp;円</span>
                                            </li>
                                            <li>～</li>
                                            <li>
                                                <input type="text" id="text_price" oninput="this.value = this.value.replace(/[^1-9]/g, '').replace(/(\..*)\./g, '$1');" name=search_params[price_to]' value="{{ old('search_params[price_to]', isset($search_params['price_to']) ? $search_params['price_to'] : '') }}">
                                                <span>&nbsp;円</span>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>
                            <input class='search' type="submit" value="絞り込む" onclick="collapsePane()">
                        </div>
                    </dd>
                </dl>
            {{ Form::close() }} 

            <ul class="shop-list-search2">
            @if ($stores->count() > 0)
                @foreach ($stores as $record)
                <li>
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
                    <a href="{{ route('user.stores.store_products', ['store'=>$record->id, 'product_type'=>  config('const.product_type_code.ec')]) }}">
                        <div class="pic">
                            <span>{{ number_format($record->getProductCountByType(config('const.product_type_code.ec'))) . '商品'}}</span>
                                 <i class="favorite {{ in_array($record->id, $p_arr_favorite_stores) ? 'active' : '' }}" onclick="toggleFavorite('{{ $record->id }}', '{{config("const.product_type_code.ec")}}')"></i>
                            @if(is_object($record->obj_main_img) && Storage::disk('stores')->has("{$record->id}/{$record->obj_main_img->img_name}"))
                                <img class="short" src="{{ asset("storage/stores/{$record->id}/{$record->obj_main_img->img_name}") }}" alt={{$record->obj_main_img->img_name}}>
                            @endif
                        </div>
                        <div class="text">
                            <i class="category">{{$category_value}}</i>
                            <h2 class="shop-name2">{{ isset($record->store_name) ? $record->store_name : '' }}</h2>
                            <p class="fromprice">商品価格: {{number_format($record->getRangePrice(config('const.product_type_code.ec'))['min']) . '円'}} ~ {{number_format($record->getRangePrice(config('const.product_type_code.ec'))['max']) . '円'}}</p>
                        </div>
                    </a>
                </li>
                @endforeach
            @else
                <li style="text-align: center">検索結果が存在しません。</li>
            @endif
            </ul>

        </div>
    </main>
@endsection 

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/user/css/pages/ec_list.css') }}"> 
@endsection
