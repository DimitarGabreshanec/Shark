@extends('user.layouts.app')

@section('title', '基本情報')

@section('header')
    <h1 id="title">基本情報</h1>
@endsection


@section('content')
    <main> 
        <div class="shop-detail"> 
        
            <table class="table">
            <tbody>
            @include('user.layouts.flash-message')
                <tr>
                    <th>UID</th>
                    <td>
                        <p class="show-element">{{ $user->member_no }}</p>
                    </td>
                </tr>
                <tr>
                    <th>名前</th>
                    <td>
                        <p class="show-element">{{ $user->name }}</p>
                    </td>
                </tr>

                <tr>
                    <th>メールアドレス</th>
                    <td>
                    <p class="show-element">{{ $user->email }}</p>
                    </td>
                </tr>

                {{-- 
                <tr>
                    <th>パスワード</th>
                    <td>
                        <p class="show-element">{{ $user->password }}</p>
                    </td>
                </tr>
                --}}

                <tr>
                    <th>性別</th>
                    <td>
                        <p class="show-element">{{ config('const.gender.' . $user->gender) }}</p>
                    </td>
                </tr> 

                <tr>
                    <th>生年月日</th>
                    <td>
                        <p class="show-element">{{ isset($user->birthday) ? Carbon::parse($user->birthday)->format('Y年m月d日') : '' }}</p>
                    </td>
                </tr>

                <tr>
                    <th>ステータス</th>
                    <td>
                        <p class="show-element">{{ config('const.user_status.' . $user->status) }}</p>
                    </td>
                </tr> 
                <tr>
                    <th>登録日時</th>
                    <td>
                        <p class="show-element">{{ Carbon::parse($user->created_at)->format('Y.m.d H:i') }}</p>
                    </td>
                </tr>
                <tr>
                    <th>郵便番号</th>
                    @php 
                        $dash = "-";
                        if($user->post_first == ""){
                            $dash = "";
                        }
                    @endphp
                    <td> <p class="show-element"> {{ $user->post_first }} {{ $dash }} {{ $user->post_second }} </p></td>
                </tr> 
                <tr>
                    <th>都道府県</th>
                    <td> 
                        <p class="show-element">{{ \App\Service\AreaService::getPrefectureNameByID($user->prefecture) }}</p>
                        <input type="hidden" id="prefecture" name="prefecture" value="{{ \App\Service\AreaService::getPrefectureNameByID($user->prefecture) }}">
                        
                    </td>
                </tr>
                <tr>
                    <th>住所</th>
                    <td> <p class="show-element"> {{ $user->address }} </p></td> 
                </tr>  
                {{--<tr>
                    <td>
                    <div class="check-map"><a id="btn_check_map">マップで確認</a></div>  
                    </td>
                </tr>--}}
                <tr>
                    <td style="margin-left: 0px;">
                    <div class="map_user_info" id="map_user_info"></div>
                    </td>
                </tr>
                
            </tbody>
            </table>  
            <input type="submit" value="情報の編集へ" onclick="location.href='{{ route('user.my_account.edit') }}'; return false;"> 
            </div>
        <input type="hidden" id="post_first" name="post_first"  value="{{ old('post_first', isset($user->post_first) ? $user->post_first : '') }}">
        <input type="hidden" id="post_second" name="post_second" value="{{ old('post_second', isset($user->post_second) ? $user->post_second : '') }}">
        <input type="hidden" id="address" name="address" value="{{ $user->address }}">
        @isset($user->position)
        @php 
            $position = json_decode($user->position, true);  
            $pos_lat = isset($position['lat']) ? $position['lat'] : null; 
            $pos_lng = isset($position['lng']) ? $position['lng'] : null;  
        @endphp
        @endisset
        <input type="hidden" name="user_lat" id="user_lat" value="{{ old('user_lat',  isset($user) && isset($pos_lat) ? $pos_lat : '') }}">
        <input type="hidden" name="user_lng" id="user_lng" value="{{ old('user_lng',  isset($user) && isset($pos_lng) ? $pos_lng : '') }}">
        
  
    </main>

@endsection

    
@section('page_css')
    <link href="{{ asset('assets/vendor/dropzone/dist/basic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/dropzone/dist/dropzone.css') }}" rel="stylesheet">
 
    <style> 
        div.plan-section {
            display: inline-flex;
        }
        .check-map {
            margin-top: 10px;
            display: block;
        }

        #map_user_info {
        width: 100%;
        height: 250px;
        position: relative; 
        margin-top: 15px;
        }

        .check-map a {
            clear: both;
            display: inline-block;
            background: #00a6a3;
            color: #FFF;
            padding: 4px 8px;
            box-shadow: 0 3px 0 #036950;
            position: relative;
            cursor: pointer;
            margin: 0px 0px 3px;
            border-radius: 7px;
            font-size: 14.7px;
            text-decoration: none;
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
        var address_detail = '';
        if(prefecture_name != '--' || address != '') {
            address_detail = prefecture_name +  address;
        }
        var lat = $('#user_lat').val();
        var lng = $('#user_lng').val();
        var prefecture = "";
        prefecture = "{{ \App\Service\AreaService::getPrefectureNameByID($user->prefecture) }}"; 

        var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' + 
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
            g_map = new google.maps.Map(document.getElementById('map_user_info'), {
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

                    $('#user_lat').val(latitude);
                    $('#user_lng').val(longitude);

                    var myLatLng = {lat: latitude, lng: longitude};  

                    g_map = new google.maps.Map(document.getElementById('map_user_info'), {
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

  
