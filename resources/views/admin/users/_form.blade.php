@include('admin.layouts.flash-message') 

<table class="normal"> 

    @isset($user)
        <tr>
            <th>ユーザー番号</th>
            <td>{{ $user->member_no }}</td>
        </tr>
    @endisset 

    <tr>
        <th>名前</th>
        <td>
            <input type="text" name="name" class="input-text" value="{{ old('name', isset($user->name) ? $user->name : '') }}">
            @error('name')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    
    <tr>
        <th>メールアドレス</th>
        <td>
            <input type="text" name="email" class="input-text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" value="{{ old('email', isset($user->email) ? $user->email : '') }}">
            @error('email')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    {{--   
    <tr>
        <th>パスワード</th>
        <td>
            <input type="text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="password" class="input-text" value="{{ old('password', isset($user->password) ? $user->password : '') }}">
            @error('password')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </-tr>
    
    <tr>
        <th>パスワード確認</th>
        <td>
            @php
                if(isset($user)){
                    $user->password_confirmation = $user->password;
                }

            @endphp
            <input type="text" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" name="password_confirmation" class="input-text" value="{{ old('password_confirmation', isset($user->password_confirmation) ? $user->password_confirmation : '') }}">
            @error('password_confirmation')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>
    --}}
    
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
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>
 
    <tr>
        <th>誕生日</th>
        <td>
        <div class="calendar-wrap display-inline-flex">
            <label for="birthday">
                    <input type="text" name="birthday" class="datepicker" value="{{ old('birthday', isset($user->birthday) ? Carbon::parse($user->birthday)->format('Y-m-d') : '') }}">
            </label>
            @error('birthday')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        </td>
    </tr>

    <tr>
        <th>ステータス</th>
        <td>
            <div class="radiobtn-wrap f-left">
                @foreach(config('const.user_status') as $key => $value)
                    <input type="radio" name="status" id="status{{ $key }}" value="{{ $key }}" {{ $key==old('status', isset($user->status) ? $user->status : '') ? 'checked' : '' }}>
                    <label for="status{{ $key }}" class="switch-on">{{ $value }}</label> 
                @endforeach
            </div>
            @error('status')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>  
    
    <tr>
        <th>住所</th>
        <td>  
            <input type="text" id="post_first" name="post_first" class="input-text w120" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('post_first', isset($user->post_first) ? $user->post_first : '') }}" onKeyUp="AjaxZip3.zip2addr(this, 'post_second','prefecture', 'address');" placeholder="郵便番号（後）">&nbsp;-
            <input type="text" id="post_second" name="post_second" class="input-text w120" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('post_second', isset($user->post_second) ? $user->post_second : '') }}" onKeyUp="AjaxZip3.zip2addr('post_first', this,'prefecture', 'address');" placeholder="郵便番号（前）">
            @error('post_first')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            @error('post_second')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br>
            <br>
            <div class="select-wrap w200">
                <label>
                    <select name="prefecture" id="prefecture">
                        <option value="">--</option>
                        @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
                            <option value="{{ $key }}"  {{ old('prefecture', isset($user)? $user->prefecture : '')  == $key ?  'selected' : '' }} >{{ $value }}</option>
                        @endforeach
                    </select>
                </label>
            </div>
            @error('prefecture')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <br> 
            <input type="text" id="address" name="address" class="input-text" value="{{ old('address', isset($user->address) ? $user->address : '') }}" placeholder="住所詳細">
            @error('address')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            <div class="check-map"><a id="btn_check_map">マップで確認</a></div>

            <div class="map" id="map">
            </div>
            @isset($user->position)
            @php 
                $position = json_decode($user->position, true);  
                $pos_lat = isset($position['lat']) ? $position['lat'] : null; 
                $pos_lng = isset($position['lng']) ? $position['lng'] : null;  
            @endphp
            @endisset
            <input type="hidden" name="user_lat" id="user_lat" value="{{ old('user_lat',  isset($user) && isset($pos_lat) ? $pos_lat : '') }}">
            <input type="hidden" name="user_lng" id="user_lng" value="{{ old('user_lng',  isset($user) && isset($pos_lng) ? $pos_lng : '') }}">
            <input type="hidden" name="firebase_token" value="{{ isset($user->firebase_token) ? $user->firebase_token : 'null'}}">
        </td>
    </tr>

</table>
 

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

                var prefecture_name =  $("#prefecture option:selected").html();
                if(prefecture_name == '--') {
                    alert('都道府県を選択してください。');
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

        var prefecture_name =  $("#prefecture option:selected").html();
        var address = $('#address').val(); 
        var prefecture = $('#prefecture').val();
        var address_detail = '';
        if(prefecture_name != '--' || address != '') {
            address_detail = prefecture_name +  address;
        }
        
        var lat = $('#user_lat').val();
        var lng = $('#user_lng').val();  
        var contentHtml;
        if(lat != '' && lng != ''){
            var prefecture_text = "";
            @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value) 
            if("{{ $key }}" == prefecture){
                prefecture_text = "{{ $value }}";
            } 
            @endforeach   
            contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">〒' + $('#post_first').val() + '-' + $('#post_second').val() + prefecture_text + address + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' + 
                          '"https://www.google.com/maps/place/' + address +
                          '/@' + lat + ',' + lng +',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d' + lat + '!4d' + lng +'?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';
            var infowindow = new google.maps.InfoWindow({
                content: contentHtml, 
                maxWidth: 350
            }); 
        }        
               
        if (address_detail != '') {
            g_geocoder.geocode( {'address': address_detail}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    var latitude = results[0].geometry.location.lat();
                    var longitude = results[0].geometry.location.lng();

                    $('#user_lat').val(latitude);
                    $('#user_lng').val(longitude);

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
                } else if(status == google.maps.GeocoderStatus.ZERO_RESULTS) {
                    //alert('有効なアドレスを入力してください。');
                }
            });
        }
        else if(lat!='' && lng!='') {
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
        } 

    }
</script>
@endsection

