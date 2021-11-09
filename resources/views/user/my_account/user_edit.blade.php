@extends('user.layouts.app')

@section('title', '基本情報の編集')

@section('header')
    <h1 id="title">基本情報の編集</h1>
@endsection

@section('content')
    <main>
        {{ Form::open(['route'=>"user.my_account.update", 'method'=>'get']) }}
        <div class="shop-detail">
            <table class="table">
            <tbody>
            <input type="hidden" name="password" value="{{ old('password', isset($user->password) ? $user->password : '') }}">
            <input type="hidden" name="password_confirmation" value="{{ old('password', isset($user->password) ? $user->password : '') }}">

            <tr>
                <th>名前</th>
                <td>
                    <input type="text" name="name" class="input-text" value="{{ old('name', isset($user->name) ? $user->name : '') }}">
                    @error('name')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </td>
            </tr>


            {{--<tr>
                <th>メールアドレス</th>
                <td>
                    <input type="text" name="email" class="input-text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" value="{{ old('email', isset($user->email) ? $user->email : '') }}">
                    @error('email')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </td>
            </tr> --}}

            <tr>
                <th>性別</th>
                <td>
                    <div class="radiobtn-wrap f-left">
                        @foreach(config('const.gender') as $key => $value)
                            <input type="radio" name="gender" id="gender{{ $key }}" value="{{ $key }}" {{ $key==old('gender', isset($user->gender) ? $user->gender : '') ? 'checked' : '' }}>
                            <label for="gender{{ $key }}" class="switch-on">{{ $value }}</label>
                        @endforeach
                    </div>
                    @error('gender')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </td>
            </tr>

            <tr>
            <th>生年月日<span>※</span></th>
            <td>
                <select name="birth_year" id="birth_year" onchange="setLeapMonth()">
                    <option value="">--</option>
                    @foreach(range(date('Y'), date('Y')-100) as $year )
                        <option value="{{ $year }}" {{ $year==$birth_year ? 'selected' : '' }}>{{ $year }}年</option>
                    @endforeach
                </select>

                <select name="birth_month" id="birth_month" onchange="setLeapMonth()">
                    <option value="">--</option>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}" {{ $month==$birth_month ? 'selected' : '' }}>{{ $month }}月</option>
                    @endforeach
                </select>
                @php
                    $year = old('birth_year') ? old('birth_year') : date('Y');
                    $month = old('birth_month') ?  old('birth_month') : 1;
                    $month_last_day = Carbon::parse("{$year}-{$month}-1")->endOfMonth()->format('d');
                @endphp
                <select name="birth_day" id="birth_day">
                    <option value="">--</option>
                    @foreach(range(1, $month_last_day) as $day)
                        <option value="{{ $day }}" {{ $day==$birth_day ? 'selected' : '' }}>{{ $day }}日</option>
                    @endforeach
                </select>
                <input type="hidden" name="birthday">
                @error('birthday')
                <p class="error">{{ $message }}</p>
                @enderror
            </td>
            </tr>
            <tr>
            <th>郵便番号</th>
            <td>
                <input type="text" name="post_first" id="post_first" class="input-text" style="width: 70px;" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('post_first', isset($user->post_first) ? $user->post_first : '') }}" onKeyUp="AjaxZip3.zip2addr(this, 'post_second','prefecture', 'address');" placeholder="123">
                &nbsp;-
                <input type="text" name="post_second" id="post_second" class="input-text" style="width: 70px;" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('post_second', isset($user->post_second) ? $user->post_second : '') }}" onKeyUp="AjaxZip3.zip2addr('post_first', this,'prefecture', 'address');" placeholder="4567">
                @error('post_first')
                    <p class="error">{{ $message }}</p>
                @enderror
                @error('post_second')
                    <p class="error">{{ $message }}</p>
                @enderror
            </td>
            </tr>
            <tr>
            <th>都道府県</th>
            <td>
                <select name="prefecture" id="prefecture">
                    <option value="">--</option>
                    @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
                        <option value="{{ $key }}"  {{ old('prefecture', isset($user)? $user->prefecture : '')  == $key ?  'selected' : '' }} >{{ $value }}</option>
                    @endforeach
                </select>
                @error('prefecture')
                    <p class="error">{{ $message }}</p>
                @enderror
            </td>
            </tr>
            <tr>
            <th>住所</th>
            <td>
                <input type="text" name="address" id="address" class="input-text" value="{{ old('address', isset($user->address) ? $user->address : '') }}" placeholder="">
                @error('address')
                    <p class="error">{{ $message }}</p>
                @enderror
                <br><br>
                <div class="check-map"><a id="btn_check_map">マップで確認</a></div>
                <div class="map_user_edit" id="map_user_edit">
                </div>

                @isset($user->position)
                @php
                    $position = json_decode($user->position, true);
                    $pos_lat = isset($position['lat']) ? $position['lat'] : null;
                    $pos_lng = isset($position['lng']) ? $position['lng'] : null;
                @endphp
                @endisset<input type="submit" value="更新">
                <input type="hidden" name="user_lat" id="user_lat" value="{{ old('user_lat',  isset($user) && isset($pos_lat) ? $pos_lat : '') }}">
                <input type="hidden" name="user_lng" id="user_lng" value="{{ old('user_lng',  isset($user) && isset($pos_lng) ? $pos_lng : '') }}">
            </td>
            </tr>
            </tbody>
            </table>
        </div>

        {{ Form::close() }}
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

        #map_user_edit {
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
        var prefecture = $('#prefecture').val();

        var address_detail = '';
        if(prefecture_name != '--' || address != '') {
            address_detail = prefecture_name +  address;
        }
        var lat = $('#user_lat').val();
        var lng = $('#user_lng').val();
        var prefecture_name = "";
        @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
        if("{{ $key }}" == prefecture){
            prefecture_name = "{{ $value }}";
        }
        @endforeach
        var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">〒' + $('#post_first').val() + '-' + $('#post_second').val() + prefecture_name + address + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' + 
                          '"https://www.google.com/maps/place/' + address + '/@' + lat + ',' + lng +',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d' + lat + '!4d' + lng +'?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';

        var infowindow = new google.maps.InfoWindow({
            content: contentHtml,
            maxWidth: 350
        });


        if (address_detail != '') {
            g_geocoder.geocode( {'address': address_detail}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    $('#user_lat').val(latitude);
                    $('#user_lng').val(longitude);

                    var myLatLng = {lat: latitude, lng: longitude};

                    g_map = new google.maps.Map(document.getElementById('map_user_edit'), {
                        zoom: 18,
                        center: myLatLng
                    });

                    var marker = new google.maps.Marker({
                        position: myLatLng,
                        map: g_map,
                        title: results[0].formatted_address
                    });
                    if(lat != "" && lng != ""){
                        google.maps.event.addListener(marker, 'click', function() {
                        infowindow.open(g_map,marker);
                        });
                    }
                    
                } else if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                    //alert('有効なアドレスを入力してください。');
                }
            });
        }
        else if(lat!='' && lng!='') {
            var myLatLng = {lat: parseFloat(lat), lng: parseFloat(lng)};
            g_map = new google.maps.Map(document.getElementById('map_user_edit'), {
                zoom: 18,
                center: myLatLng
            });
            var marker = new google.maps.Marker({
                    position: myLatLng,
                    map: g_map,
                    title: address_detail
                });
            if(lat != "" && lng != ""){
                google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(g_map,marker);
                });
            }
        }

    }
</script>
@endsection


