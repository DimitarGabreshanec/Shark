@extends('store.layouts.app')

@section('title', '店舗情報')

@section('header')
    <h1 id="title">店舗情報</h1>
@endsection

@section('content') 
    <main>
        <div id="wrapper2"> 
            @include('store.layouts.flash-message')
            <table class="table">
                <tbody>
                    <tr>
                        <th>掲載カテゴリ</th>
                        <td>
                            <ul class="acc">
                            @include('store.my_account._category_list', ['parent_id' => 0, 'disabled' => 'disabled'])
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th>店舗名</th>
                        <td> 
                            <p class="show-element">{{ isset($store) && isset($store->store_name) ? $store->store_name : '' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>営業時間</th>
                        <td> 
                            @php
                                $work_from = isset($store) && isset($store->work_from) ? Carbon::parse($store->work_from)->format('H:i').' ~ ' : ''; 
                                $work_to = isset($store) && isset($store->work_to) ? Carbon::parse($store->work_to)->format('H:i') : ''; 

                            @endphp
                            <p class="show-element">{{ $work_from . $work_to}}</p>
                              
                        </td>
                    </tr>
                    <tr>
                        <th>お店へのアクセス</th>
                        <td>
                            <p class="show-element" id="detail">{{ isset($store) && isset($store->address_access) ? $store->address_access : '' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>
                            <p class="show-element">{{ isset($store) && isset($store->tel) ? $store->tel : '' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td>
                            <p class="show-element">{{\App\Service\AreaService::getPrefectureNameByID($store->prefecture)}}</p>
                             
                            <p class="show-element">{{ isset($store) && isset($store->store_address) ? $store->store_address : '' }}</p>
                        </td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td> 
                            <p class="show-element" ><a id="url" href="{{$store->url}}" target="_blank"><u> {{ Str::limit(isset($store) && isset($store) && isset($store->url) ? $store->url : '',100, '...') }}</u></a></p>
                        </td>
                    </tr>
                    <tr>
                        <th>店舗紹介文</th>
                        <td>
                        <p class="show-element" id="detail"> {{ isset($store) && isset($store->detail) ? $store->detail : '' }}</p>

                        </td>
                    </tr>
                    <tr>
                        <th>メイン画像</th>
                        <td >
                            <div class="imagebox_main">
                            @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                                <img class="w250" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}">
                            @endif
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>サブ画像(複数選択可)</th>
                        <td >
                            <div class="imagebox_sub">
                            @if($store->obj_sub_img->count() > 0)
                            <ul>
                                @foreach($store->obj_sub_img as $obj_sub_img_one)
                                @if(is_object($obj_sub_img_one) && Storage::disk('stores')->has("{$store->id}/{$obj_sub_img_one->img_name}"))
                                    <li><img class="w250" src="{{ asset("storage/stores/{$store->id}/{$obj_sub_img_one->img_name}") }}"> </li>

                                @endif
                                @endforeach
                            </ul>
                            @endif
                            </div>
                            <div class="hidden_sub"> </div>
                        </td>
                    </tr> 

                </tbody>
            </table>
            <input type="submit" value="編集する" onclick="location.href='{{ route('store.my_account.edit') }}'; return false;">
        </div>
    </main> 
@endsection



@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/store/css/pages/my_account_info.css') }}">
@endsection



@section('page_js')
@endsection
