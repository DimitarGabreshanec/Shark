@include('admin.layouts.flash-message')
@isset($store)
    <input type="hidden" name="id" value="{{ $store->id }}">
@endisset

<table class="normal">
    <tr>
        <th>掲載カテゴリ</th>
        <td>
            @include('admin.share._category', ['selected_categories' => isset($store) ? $store->arr_categories() : []])
            @error('category')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    @isset($store)
        <tr>
            <th>店舗番号</th>
            <td>{{ $store->store_no }}</td>
        </tr>
    @endisset

    <tr>
        <th>種別</th>
        <td>
            <div class="radiobtn-wrap f-left">
                @foreach(config('const.store_type') as $key => $value)
                    <input type="radio" name="type" id="type{{ $key }}"
                           value="{{ $key }}" {{ $key==old('type', isset($store->type) ? $store->type : '') ? 'checked' : '' }}>
                    <label for="type{{ $key }}" class="switch-on">{{ $value }}</label>
                @endforeach
            </div>
            @error('type')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>ログインID</th>
        <td>
            <input type="text" name="email" class="input-text w300"
                   value="{{ old('email', isset($store->email) ? $store->email : '') }}">
            @error('email')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>店舗名</th>
        <td>
            <input type="text" name="store_name" id="store_name" class="input-text"
                   value="{{ old('store_name', isset($store->store_name) ? $store->store_name : '') }}">
            @error('store_name')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>店舗所在地</th>
        <td>
            <input type="text" id="post_first" name="post_first" class="input-text w120" maxlength="3" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('post_first', isset($store->post_first) ? $store->post_first : '') }}" onKeyUp="AjaxZip3.zip2addr(this, 'post_second','prefecture', 'store_address');" placeholder="郵便番号（後）">&nbsp;-
            <input type="text" id="post_second" name="post_second" class="input-text w120" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');" value="{{ old('post_second', isset($store->post_second) ? $store->post_second : '') }}" onKeyUp="AjaxZip3.zip2addr('post_first', this,'prefecture', 'store_address');" placeholder="郵便番号（前）">
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
                            <option value="{{ $key }}"  {{ old('prefecture', isset($store)? $store->prefecture : '')  == $key ?  'selected' : '' }} >{{ $value }}</option>
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
            <input type="text" id="store_address" name="store_address" class="input-text" value="{{ old('store_address', isset($store->store_address) ? $store->store_address : '') }}" placeholder="住所">
            @error('store_address')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

            <div class="check-map"><a id="btn_check_map">マップで確認</a></div>

            <div class="map" id="map">
            </div>
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
            <textarea name="address_access" id="address_access"
                class="input-text"> {{ old('address_access', isset($store->address_access) ? $store->address_access : '') }}</textarea>
            @error('address_access')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>


    <tr>
        <th>営業時間</th>
        <td>
            <div class="select-wrap display-inline-flex">
                <label for="work_from_hour">
                    @php
                        $work_from_hour = old('work_from_hour',  isset($store->work_from) ? Carbon::parse($store->work_from)->isoFormat('H') : '' );
                    @endphp
                    <select name="work_from_hour" id="work_from_hour" style="width: 100%">

                        <option value="">--</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option
                                value="{{$i}}" {{ $work_from_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;時</span>
            </div>&nbsp;&nbsp;&nbsp;
            <div class="select-wrap display-inline-flex">
                <label for="work_from_minute">
                    @php
                        $work_from_minute = old('work_from_minute',  isset($store->work_from) ? Carbon::parse($store->work_from)->isoFormat('m') : '' );
                    @endphp
                    <select name="work_from_minute" id="work_from_minute" style="width: 100%">
                        <option value="">--</option>
                        @for ($i = 0; $i < 60; $i++)
                            <option
                                value="{{$i}}" {{ $work_from_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;分</span>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="select-wrap display-inline-flex">~</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <div class="select-wrap display-inline-flex">
                <label for="work_to_hour">
                    @php
                        $work_to_hour = old('work_to_hour',  isset($store->work_to) ? Carbon::parse($store->work_to)->isoFormat('H') : '' );
                    @endphp
                    <select name="work_to_hour" id="work_to_hour" style="width: 100%">

                        <option value="">--</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option
                                value="{{$i}}" {{ $work_to_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;時</span>
            </div>&nbsp;&nbsp;&nbsp;
            <div class="select-wrap display-inline-flex">
                <label for="play_minute">
                    @php
                        $work_to_minute = old('work_to_minute',  isset($store->work_to) ? Carbon::parse($store->work_to)->isoFormat('m') : '' );
                    @endphp
                    <select name="work_to_minute" id="work_to_minute" style="width: 100%">
                        <option value="">--</option>
                        @for ($i = 0; $i < 60; $i++)
                            <option
                                value="{{$i}}" {{ $work_to_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;分</span>
            </div>
            <input type="hidden" name="work_from" value="{{ isset($store->work_from) ? $store->work_from : ''  }}">
            <div>
                @error('work_from')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <input type="hidden" name="work_to" value="{{ isset($store->work_to) ? $store->work_to : ''  }}">
            <div>
                @error('work_to')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>
    </tr>

    <tr>
        <th>電話番号</th>
        <td>
            <input type="text" name="tel" class="input-text w200"
                   value="{{ old('tel', isset($store->tel) ? $store->tel : '') }}">
            @error('tel')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>担当者名</th>
        <td>
            <input type="text" name="charger_name" class="input-text w200"
                   value="{{ old('charger_name', isset($store->charger_name) ? $store->charger_name : '') }}">
            @error('charger_name')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>URL</th>
        <td>
            <input type="text" name="url" maxlength="512" class="input-text"
                   value="{{ old('url', isset($store->url) ? $store->url : '') }}">
            @error('url')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    {{--@isset($store)
    <tr>
        <th>登録ステータス</th>
        <td>
            <div class="radiobtn-wrap f-left">
                @foreach(config('const.store_status') as $key => $value)
                    <input type="radio" name="status" id="status{{ $key }}" value="{{ $key }}" {{ $key==old('status', isset($store->status) ? $store->status : '') ? 'checked' : '' }}>
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
    @endisset--}}
    <tr>
        <th>店舗紹介文</th>
        <td>
            <textarea name="detail" id="detail"
                      class="input-text"> {{ old('detail', isset($store->detail) ? $store->detail : '') }}</textarea>
            @error('detail')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>メイン画像</th>
        <td>
            <button id="fileforbutton" onclick="document.getElementById('main_img').click(); return false;">ファイル選択</button>
            <input type="file" name="main_img" id="main_img" accept="image/*" class="one" >
            <div class="imagebox_main"> 
                @if(isset($store) && is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                    <li class="li_{{ $store->obj_main_img->sequence }}">     
                    <img src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}">
                    <input type="button" id="clear3" data-image_name="{{ $store->obj_main_img->img_name }}" data-sequence_="{{ $store->obj_main_img->img_name }}" value="ファイルを削除する" style="display: block !important">
                    <input type="hidden" id="main_img_name" name="main_img_name" value="{{ $store->obj_main_img->img_name }}">
                @endif
            </div> 
            @error('main_img_name')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>サブ画像</th>
        <td>
            <button id="fileforbutton1" onclick="document.getElementById('sub_img').click(); return false;">ファイル選択</button>
            <input type="file" id="sub_img" name="sub_img[]" accept="image/*"  multiple class="many">
            <div class="imagebox_sub">  
                @if(isset($store) && $store->obj_sub_img->count() > 0)
                <ul id="sub_old_img"> 
                    @foreach($store->obj_sub_img as $obj_sub_img_one) 
                    @if(is_object($obj_sub_img_one) && Storage::disk('stores')->has("{$store->id}/{$obj_sub_img_one->img_name}"))
                        <li class="li_{{ $obj_sub_img_one->sequence }}">
                            <img  src="{{ asset("storage/stores/{$store->id}/{$obj_sub_img_one->img_name}") }}"> 
                            <input type="button" id="clear3" data-image_name="{{ $obj_sub_img_one->img_name }}" data-sequence_="{{ $obj_sub_img_one->sequence }}" class="clear_sub" value="ファイルを削除する">
                        </li>
                    @endif 
                    @endforeach 
                </ul>
                @endif
            </div>   
            @error('sub_image')
                <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

</table>

@section('page_css')
    <link href="{{ asset('assets/vendor/dropzone/dist/basic.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/dropzone/dist/dropzone.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/pages/stores_form.css') }}"> 
   
@endsection


@section('page_js')
<script src="{{ asset('assets/vendor/dropzone/dist/dropzone.js') }}"></script>
<script>
    //var store_address = '{{ old('store_name', isset($store) ? ($store->store_name.'+') : "" )}}{{ old('store_address', isset($store) ? $store->getPrefectureName(). $store->store_address : "" )}}';
    //var store_lat = '{{ old('store_lat', isset($store) && isset($store->position)? $pos_lat : "" )}}';
    //var store_lng = '{{ old('store_lng', isset($store) && isset($store->position) ? $pos_lng : "" )}}';
    //initMap(store_address, store_lat, store_lng);
    ///var g_geocoder, g_map;

    $(document).ready(function () {
        @if($mode == 'edit')
            $("#sub_img").on('change', function(e){    
                var tgt = e.target || window.event.srcElement,
                    files = tgt.files;
                var form_data = new FormData();
                var totalfiles = files.length;
                for (var index = 0; index < totalfiles; index++) {
                    form_data.append("sub_img[]", files[index]);
                }
                form_data.append("_token", "{{csrf_token()}}");  
                form_data.append("store_id", "{{ $store->id }}");
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.stores.upload_img") }}',
                    data:  form_data,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result.result_code == 'success') {    
                            location.reload();
                            return true;
                        } else {
                            alert('お気に入りを設定することが出来ません。');
                        }
                    },
                    error: function(){
                        alert('お気に入りを設定することが出来ません。');
                    },
                }); 
            });

            $("#main_img").on('change', function(e){      
                var tgt = e.target || window.event.srcElement,
                    files = tgt.files;
                var form_data = new FormData();
                var totalfiles = files.length;
                form_data.append("main_img", files[0]);
                form_data.append("_token", "{{csrf_token()}}");  
                form_data.append("store_id", "{{ $store->id }}");
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.stores.upload_img") }}',
                    data:  form_data,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        if (result.result_code == 'success') {    
                            location.reload();
                            return true;
                        } else {
                            alert('お気に入りを設定することが出来ません。');
                        }
                    },
                    error: function(){
                        alert('お気に入りを設定することが出来ません。');
                    },
                }); 
            }); 

            $(document).on('click', '#clear3', function(e){  
                var image_name = $(this).data('image_name'); 
                helper_confirm("dialog-confirm", "削除", "画像を削除します。<br />よろしいですか？", 300, "確認", "閉じる", function(){
                    
                        $.ajax({
                            type: "post",
                            url : '{{ route("admin.stores.delete_img") }}',
                            data: {
                                image_name: image_name, 
                                store_id: "{{ $store->id }}",
                                _token: '{{ csrf_token() }}',
                            },
                            dataType: "json",
                            success: function(result) {
                                $('.li_' + result.sequence).remove();       
                                if(result.sequence == 0){
                                    $('#main_img_name').remove();   
                                }        
                                return true;
                                },
                            error: function(){
                                alert('お気に入りを設定することが出来ません。');
                            },
                            });  
                    });
            });
        @else
            $("#main_img").on('change', function(e){     
                var tgt = e.target || window.event.srcElement,
                files = tgt.files;
                $('.imagebox_main').html('');
                (function(){
                            var fr = new FileReader();
                            fr.onload = function () {  
                                $(".imagebox_main").append("<li class='li_main'><img src='"+fr.result+"'></img>" +
                                '<input type="button" class="clear2" id="clear2" data-sequence_="' + 'main" value="ファイルを削除する">' +
                                '<input type="hidden" id="main_img_name" name="main_img_name" value="main"></li>');
                            }
                            fr.readAsDataURL(files[0]);
                        })();
                

            });  

            $("#sub_img").on('change', function(e){   
                var tgt = e.target || window.event.srcElement,
                    files = tgt.files;

                // FileReaderがサポートされているか確認.
                if (FileReader && files && files.length) { 
                    $(".imagebox_sub").html(''); 
                    $(".imagebox_sub").append("<ul></ul>");
                    $index = 0;
                    for (var i = 0; i < files.length; i++)
                    { 
                        //それぞれの画像ファイルに対してFileReaderを読んで読んだらimg-placeにimgタグを追加していく.
                        (function(){
                            var fr = new FileReader();
                            fr.onload = function () {  
                                $index++;
                                $(".imagebox_sub ul").append("<li class='li_" + 'old' + $index + "'><img src='"+fr.result+"'></img>" +
                                '<input type="button" class="clear2" id="clear2" data-index_="' + $index + '"data-sequence_="' + 'old' + $index + '"value="ファイルを削除する">' + '</li>');
                            }
                            fr.readAsDataURL(files[i]);
                        })();
                        
                    }
                }
            });

            $(document).on('click', '.clear2', function(e){   
                var sequence = $(this).data('sequence_');  
                $('.li_' + sequence).remove();   
                var index = $(this).data('index_');   

                var names = [];
                for (var i = 0; i < $("#sub_img").get(0).files.length; ++i) {
                if(index==i){}else{  names.push($("#sub_img").get(0).files[i].name);  }
                } 
                $("#sub_img").val(names);
            });
        @endif
    });

     
</script>

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

                var store_name = $('#store_name').val();
                if(store_name == '') {
                    alert('店舗名を入力してください。');
                    return false;
                }
                var store_address = $('#store_address').val();
                if(store_address == '') {
                    alert('住所を入力してください。');
                    return false;
                }

                var address = store_name + '+' + prefecture_name +  store_address;
                initMap();
            });
    });
    function initMap() {

        g_geocoder = new google.maps.Geocoder();

        var prefecture_name =  $("#prefecture option:selected").html();
        var store_address = $('#store_address').val();
        var prefecture = $('#prefecture').val();
        
        var store_name = $('#store_name').val();
        var address = '';
        if(store_name != '' || prefecture_name != '--' || store_address != '') {
            address = store_name + '+' + prefecture_name +  store_address;
        }
        var lat = $('#store_lat').val();
        var lng = $('#store_lng').val(); 
        var prefecture_text = "";
        @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value) 
        if("{{ $key }}" == prefecture){
            prefecture_text = "{{ $value }}";
        } 
        @endforeach  
         
        var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="3" jsan="7.title,7.full-width" style="font-size: 14px; font-weight:bold">' + store_name + '</div>' + 
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">' + '〒' + $('#post_first').val() + '-' + $('#post_second').val() + prefecture_text + store_address + '</div>' +
                         
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' + 
                          '"https://www.google.com/maps/place/' + store_address + '/@' + lat + ',' + lng +',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d' + lat + '!4d' + lng +'?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';
         
        var infowindow = new google.maps.InfoWindow({
            content: contentHtml, 
            maxWidth: 350
        }); 
               
        if (address != '') {
            g_geocoder.geocode( {'address': address}, function(results, status) {
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
                        title: store_name
                    }); 
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.open(g_map,marker);
            });
        }

    }
</script>
@endsection


