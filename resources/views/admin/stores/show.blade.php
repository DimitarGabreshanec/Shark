@extends('admin.layouts.app')

@section('content')
    <h2 class="title">店舗詳細</h2>
        <table class="normal">

            <tr>
                <th>店舗番号</th>
                <td>
                    <p class="show-element">{{ $store->store_no }}</p>
                </td>
            </tr>

            <tr>
                <th>種別</th>
                <td>
                    <p class="show-element">{{ config('const.store_type.' . $store->type) }}</p>
                </td>
            </tr>

            <tr>
                <th>ログインID</th>
                <td>
                    <p class="show-element">{{ $store->email }}</p>
                </td>
            </tr>

            <tr>
                <th>店舗名</th>
                <td>
                  <p class="show-element">{{ $store->store_name }}</p>
                </td>
            </tr>

            <tr>
                <th>店舗所在地</th>
                <td>
                <table class="normal" style="margin: 0px;"> 
                    <tr>
                        <th>郵便番号</th>
                        <td> <p class="show-element"> {{ $store->post_first }} - {{ $store->post_second }} </p></td>
                    </tr> 
                    <tr>
                        <th>都道府県</th>
                        <td> 
                            @php
                                $prefecture = \App\Service\AreaService::getPrefectureNameByID($store->prefecture);
                            @endphp
                            <p class="show-element">{{ $prefecture  }}</p>
                            <input type="hidden" id="prefecture" name="prefecture" value="{{ $prefecture }}"> 
                        </td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td> <p class="show-element"> {{ $store->store_address }} </p></td> 
                    </tr>  
                     
                    <input type="hidden" id="post_first" name="post_first"  value="{{ old('post_first', isset($store->post_first) ? $store->post_first : '') }}">
                    <input type="hidden" id="post_second" name="post_second" value="{{ old('post_second', isset($store->post_second) ? $store->post_second : '') }}">
                    <input type="hidden" id="address" name="address" value="{{ $store->store_address }}">
                    <input type="hidden" id="store_name" name="store_name" value="{{ $store->store_name }}">
            
                </table>

                <table class="normal" style="margin: 0px;"> 
                    <tr>
                        <td>
                            {{--<div class="check-map"><a id="btn_check_map">マップで確認</a></div>--}}
                            <div class="map" id="map" style="margin: 0px;"></div>
                        </td> 
                    </tr>  
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
                </td>
            </tr>

            <tr>
                <th>お店へのアクセス</th> 
                <td>
                    <p class="show-element text-wrap">{{ $store->address_access }}</p>
                </td>
            </tr> 

            <tr>
                <th>営業時間</th>
                <td>
                    <p class="show-element">{{ $store->work_from ? Carbon::parse($store->work_from)->format('H:i') : '' }} ~ {{ $store->work_to ? Carbon::parse($store->work_to)->format('H:i') : '' }}</p>
                </td>
            </tr> 

            <tr>
                <th>電話番号</th>
                <td>
                    <p class="show-element">{{ $store->tel }}</p>
                </td>
            </tr>

            <tr>
                <th>担当者名</th>
                <td>
                    <p class="show-element">{{ $store->charger_name }}</p>
                </td>
            </tr> 

            <tr>
                <th>URL</th>
                <td>
                    <p class="show-element"><a href="{{$store->url}}" target="_blank"><u>{{ Str::limit($store->url,130, '...') }}</u></a></p> 
                </td>
            </tr>

            <tr>
                <th>店舗紹介文</th>
                <td>
                    <p class="show-element text-wrap">{{ $store->detail }}</p>
                </td>
            </tr>

            <tr>
                <th>最終ログイン日時</th>
                <td>
                    <p class="show-element">{{ $store->last_login_at ? Carbon::parse($store->last_login_at)->format('Y.m.d H:i') : '' }}</p>
                </td>
            </tr>

            <tr>
                <th>登録ステータス</th>
                <td>
                    <p class="show-element">{{ config('const.store_status.' . $store->status) }}</p>
                </td>
            </tr>

            <tr>
                <th>登録日時</th>
                <td>
                    <p class="show-element">{{ Carbon::parse($store->created_at)->format('Y.m.d H:i') }}</p>
                </td>
            </tr>

            <tr>
                <th>メイン画像</th>
                <td >
                    @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                    <div class="imagebox_main">  
                        <img class="w250" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}">
                    </div>
                    @endif
                </td>
            </tr>

            <tr>
            <th>サブ画像</th>
            <td>
                <div class="imagebox_sub">  
                    @if(isset($store) && $store->obj_sub_img->count() > 0)
                    <ul id="sub_old_img"> 
                        @foreach($store->obj_sub_img as $obj_sub_img_one) 
                        @if(is_object($obj_sub_img_one) && Storage::disk('stores')->has("{$store->id}/{$obj_sub_img_one->img_name}"))
                            <li class="li_{{ $obj_sub_img_one->sequence }}">
                                <img  src="{{ asset("storage/stores/{$store->id}/{$obj_sub_img_one->img_name}") }}"> 
                            </li>
                        @endif 
                        @endforeach 
                    </ul>
                    @endif
                </div>    
            </td>
            </tr>
        </table>
        <div class="center-submit double">
            <input type="button" class="btn-back-index" data-url="{{ route("admin.stores.index", $index_params) }}" value="戻る">
        </div>
@endsection
 
@section('page_css')
    <link href="{{ asset('assets/vendor/dropzone/dist/basic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/dropzone/dist/dropzone.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/pages/products_show.css') }}"> 
    
@endsection 

 
@section('page_js')
<script src="{{ asset('assets/vendor/dropzone/dist/dropzone.js') }}"></script> 
<script  src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&callback=initMap"></script>
<script>
    var g_geocoder, g_map;

    $(document).ready(function () {
        initMap();
        $("#btn_check_map").click(function() { 
                var post_first = $('#post_first').val();
                var post_second = $('#post_second').val();
                if(post_first == '' || post_second =='') {
                    alert('郵便番号を入力してください。');
                    return false;
                } 

                var address = $('#address').val();
                if(address == '') {
                    alert('住所を入力してください。');
                    return false;
                }
 
                initMap();
            });
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
        var prefecture = "{{ \App\Service\AreaService::getPrefectureNameByID($store->prefecture) }}"
               
        var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="3" jsan="7.title,7.full-width" style="font-size: 14px; font-weight:bold">' + store_name + '</div>' + 
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">〒' + $('#post_first').val() + '-' + $('#post_second').val() + prefecture + address + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' + 
                          '"https://www.google.com/maps/place/' + address + '/@' + lat + ',' + lng +',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d' + lat + '!4d' + lng +'?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentHtml, 
            maxWidth: 350
        }); 
               
        if(lat!='' && lng!='') {
            var myLatLng = {lat: parseFloat(lat), lng: parseFloat(lng)};
            g_map = new google.maps.Map(document.getElementById('map'), {
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

                    g_map = new google.maps.Map(document.getElementById('map'), {
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