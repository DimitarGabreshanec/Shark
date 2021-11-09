@extends('user.layouts.app')

@section('title', '詳細を見る')

@section('header')
    @if($product_type == config('const.product_type_code.fix'))
        <h1 id="title">お店で探す</h1>
    @else
        <h1 id="title">通販で探す</h1>
    @endif
@endsection


@section('content')
    <main>
        <h2 class="title2">{{$store->store_name}}</h2>
        <nav>
            <ul class="nav">
                <li><a href='{{ route('user.stores.store_products', ['store' => $store, 'product_type'=> $product_type]) }}'>商品情報</a></li>
                <li class="active">店舗情報</li>
            </ul>
        </nav>
        <div class="shop-detail">
            <h3 class="mainpic" style="text-align:center; width:100%;">
                @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                    <img class="short" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}" alt={{$store->obj_main_img->img_name}}>
                @endif
            </h3>
            <table class="table">
            <tbody>
                <tr>
                    <th>カテゴリ</th>
                    <td>
                        @include('user.share._get_category_list', ['parent_id' => 0, 'fullName' => '', 'selected_categories' => $store->arr_categories()])
                    </td>
                </tr>
                <tr>
                    <th>店舗名</th>
                    <td>{{$store->store_name}}</td>
                </tr>

                <tr>
                    <th>店舗紹介</th>
                    <td class="text-wrap">{{$store->detail}}</td>
                </tr>
                <tr>
                    <th>お店へのアクセス</th>
                    <td class="text-wrap">{{$store->address_access}}</td>
                </tr>
                <tr>
                    <th>電話番号</th>
                    <td>{{$store->tel}}</td>
                </tr>
                <tr>
                    <th>URL</th>
                    <td><a href="{{$store->url}}" target="_blank"><u>{{Str::limit($store->url ,100, '...')}}</u></a></td>
                </tr>

                <tr>
                    <th>郵便番号</th>
                    <td> {{ $store->post_first }} - {{ $store->post_second }} </td>
                </td>

                <tr>
                    <th>都道府県</th>
                    <td>
                        @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
                        @if($key == $store->prefecture)
                            <p class="show-element">{{ $value }}</p>
                            <input type="hidden" id="prefecture" name="prefecture" value="{{ $value }}">
                        @endif
                        @endforeach
                    </td>
                </td>

                <tr>
                    <th>住所</th>
                    <td>  {{ $store->store_address }} </td>
                </tr>
                <input type="hidden" id="post_first" name="post_first"  value="{{ old('post_first', isset($store->post_first) ? $store->post_first : '') }}">
                <input type="hidden" id="post_second" name="post_second" value="{{ old('post_second', isset($store->post_second) ? $store->post_second : '') }}">
                <input type="hidden" id="address" name="address" value="{{ $store->store_address }}">
                <input type="hidden" id="store_name" name="store_name" value="{{ $store->store_name }}">
            </tbody>
        </table>
        @isset($store->position)
        @php
            $position = json_decode($store->position, true);
            $pos_lat = isset($position['lat']) ? $position['lat'] : null;
            $pos_lng = isset($position['lng']) ? $position['lng'] : null;
        @endphp
        @endisset
        <input type="hidden" name="store_lat" id="store_lat" value="{{ old('store_lat',  isset($store) && isset($pos_lat) ? $pos_lat : '') }}">
        <input type="hidden" name="store_lng" id="store_lng" value="{{ old('store_lng',  isset($store) && isset($pos_lng) ? $pos_lng : '') }}">

        <div class="map_store_info" id="map_store_info"></div>
        </div>
    </main>


@endsection


@section('page_css')
    <link href="{{ asset('assets/vendor/dropzone/dist/basic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/dropzone/dist/dropzone.css') }}" rel="stylesheet">

    <style>
        div.plan-section {
            display: inline-flex;
        }

        #map_store_info {
        width: 100%;
        height: 200px;
        position: relative;
        margin: 15px;
        }
    </style>
@endsection

@section('page_js')
<script src="{{ asset('assets/vendor/dropzone/dist/dropzone.js') }}"></script>
<script  src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&callback=initMap"></script>
<script>
    var g_geocoder, g_map;

    $(document).ready(function () {
        initMap();
    });

    function initMap() {

        g_geocoder = new google.maps.Geocoder();

        var prefecture_name =  $("#prefecture").val();
        var address = $('#address').val();
        var store_name = $('#store_name').val();
        var address_detail = '';
        if(store_name != '' || prefecture_name != '--' || address != '') {
            address_detail = store_name + '+' + prefecture_name +  address;
        }
        var lat = $('#store_lat').val();
        var lng = $('#store_lng').val();

        var prefecture = "{{ \App\Service\AreaService::getPrefectureNameByID($store->prefecture) }}";

        var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="3" jsan="7.title,7.full-width" style="font-size: 14px; font-weight:bold">' + store_name + '</div>' +
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">〒' + $('#post_first').val() + '-' + $('#post_second').val() + prefecture + address + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' +
                          '"https://www.google.com/maps/place/' + store_name + '/@' + lat + ',' + lng +',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d' + lat + '!4d' + lng +'?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentHtml,
            maxWidth: 350
        });

        if(lat!='' && lng!='') {
            var myLatLng = {lat: parseFloat(lat), lng: parseFloat(lng)};
            g_map = new google.maps.Map(document.getElementById('map_store_info'), {
                zoom: 18,
                center: myLatLng
            });
            var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: g_map,
                        title: address_detail
                    });

            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(g_map,marker);
            });
            infowindow.open(g_map,marker);
        } else if (address_detail != '') {
            g_geocoder.geocode( {'address': address_detail}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    $('#store_lat').val(latitude);
                    $('#store_lng').val(longitude);

                    var myLatLng = {lat: latitude, lng: longitude};

                    g_map = new google.maps.Map(document.getElementById('map_store_info'), {
                        zoom: 18,
                        center: myLatLng
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: g_map,
                        title: results[0].formatted_address
                    });
                    google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(g_map,marker);
                    });
                    infowindow.open(g_map,marker);


                } else if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                    //alert('有効なアドレスを入力してください。');
                }
            });
        }

    }
</script>
@endsection
