@extends('user.layouts.app')

@section('title', 'マップから探す')

@section('header')
    <h1 id="title">お店で探す</h1>
@endsection

@section('page_css')
            <link rel="stylesheet" href="{{ asset('assets/user/css/pages/store_imasugu.css') }}"> 
@endsection

@section('content')
    <main>
            <nav>
                <ul class="nav">
                    <li><a href="{{ route('user.stores.imasugu_search', ['view_kind'=>'map']) }}">イマスグを探す</a></li>
                    <li class="active">お店を探す</li>
                </ul>
            </nav>
            <nav>
                <ul class="morenav">
                    <li><a href="{{ route('user.stores.search.condition') }}">条件から探す</a></li>
                    <li><a href="{{ route('user.stores.search.map') }}" class="active">マップから探す</a></li>
                </ul>
            </nav> 
            
            <div id="map2" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
        </div>
    </main>
    
   <!--MODAL -->
   <div id="shop-detail" class="modal-content">
        <div class="textarea">
                <div class="pic" id="pic"><span>5商品</span><i></i><img src="{{ asset('assets/user/img/sample.jpg') }}" alt="サンプル写真"></div>
                <div class="text text_category" id="text_category"><i class="category">ケーキ</i>
                    <h2 class="shop-name">ラ・メゾン・ジュヴォー広尾店</h2>
                    <p class="address">東京都港区南麻布5-10-24</p>
                    <time>営業時間: 10:00～20:00</time>
                </div>
            <p class="closemodal"><a class="modal-close" id="modal-close">×</a></p>
        </div>
    </div>
    <!-- -->
@endsection

 

@section('page_js')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&callback=initMap&libraries=places" defer></script>
<script type="text/javascript">
$(document).on('click', '#btn_favorite', function(e){
    var store_id = $(this).data('id');
    var product_type = '{{ config("const.product_type_code.fix") }}'; 
    $.ajax({
        type: "post",
        url : '{{ route("user.stores.toggle_favorite") }}',
        data: {
            store_id: store_id,
            product_type: product_type,
            _token: '{{ csrf_token() }}',
        },
        dataType: "json",
        success: function(result) {
            if (result.result_code == 'success') { 
                var btn_fav = document.getElementById('btn_favorite'); 
                if(btn_fav != ""){ 
                    btn_fav.className = btn_fav.className == "active" ? "" : "active";// '<i id="btn_favorite" class="' + 'active' + '" data-id="' + store_id + '"></i>';
                }
                    
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
    var map;
    var infowindow;  
 
    var centerPos = {};
    store_address = new Array(); 
            
    function initMap() { 
        var index = 0;    
         
        @isset($stores) 
        @foreach($stores as $store) 
            @php
            if(isset($search_params['category_id'])){
                if($search_params['category_id'] == 0){
                    foreach($store->arr_categories() as $categoryId){
                        $category_id = $categoryId;
                        foreach(\App\Service\CategoryService::getParentCategoryName($category_id) as $parent){
                            $parentName = $parent['name']; 
                            break; 
                        } 
                    } 
                }
                else{
                    foreach(\App\Service\CategoryService::getParentCategoryName($search_params['category_id']) as $parent){
                        $parentName = $parent['name']; 
                        break; 
                    } 
                }
            }
            else{
                foreach(\App\Service\CategoryService::getParentCategoryName($store->arr_categories()) as $parent){
                    $parentName = $parent['name']; 
                    break; 
                } 
            }
            
            $lat = isset($store['lat']) ? $store['lat'] : null; 
            $lng = isset($store['lng']) ? $store['lng'] : null; 
            @endphp
             
            store_object = new Array(); 
            store_object['title'] = "{{$store->store_name}}";   
            store_object['category'] = "{{$parentName}}";
            store_object['address'] = "{{$store->store_address}}";   
            @php     
                $position = json_decode($store->position, true);  
                $pos['lat'] = isset($position['lat']) ? $position['lat'] : null; 
                $pos['lng'] = isset($position['lng']) ? $position['lng'] : null;   
                $active = in_array($store->id, $p_arr_favorite_stores) ? 'active' : '';
            @endphp   
            //store_object['position'] = new google.maps.LatLng("{{$pos['lat']}}", "{{$pos['lng']}}");   
            $posit = {'lat' : "{{$pos['lat']}}", 'lng' : "{{$pos['lng']}}"};  
            store_object['position'] = $posit; 
            store_object['work_time'] = "{{ isset($store->work_from) ? Carbon::parse($store->work_from)->format('H:i') . ' ~ ' : ''}}  {{isset($store->work_to) ? Carbon::parse($store->work_to)->format('H:i') : ''}}";
            store_object['product_size'] = "{{number_format($store->getProductCountByType(config('const.product_type_code.fix'))) . '商品'}}";
            store_object['link'] = "{{ route('user.stores.store_products', ['store' => $store, 'product_type'=> config('const.product_type_code.fix')]) }}";
            @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                store_object['image_url'] = '{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}';
            @endif   
            store_object['id'] = "{{$store->id}}";
            store_object['acivte'] = "{{ $active }}";
            store_object['prefecture'] = "{{ \App\Service\AreaService::getPrefectureNameByID($store->prefecture) }}";
            store_address.push(store_object);
        @endforeach 
        @endisset
 
        @php 
            $lat = isset($self_pos['lat']) ? $self_pos['lat'] : null; 
            $lng = isset($self_pos['lng']) ? $self_pos['lng'] : null; 
        @endphp  

        ///////////detect current address////////////////////
        if (navigator.geolocation) {
            var options = { enableHighAccuracy: false, maximumAge: 100, timeout: 10000 };
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    }; 
                    const latlng = {
                        lat: parseFloat(pos.lat),
                        lng: parseFloat(pos.lng),
                    }; 
                    const geocoder = new google.maps.Geocoder(); 
                    geocoder.geocode({ location: latlng }, (results, status) => {
                        if (status === "OK") { 
                            if (results[0]) {
                                drawMap(pos.lat, pos.lng, results[0].formatted_address); 
                            }
                            else{
                                drawMap(pos.lat, pos.lng, ""); 
                            }
                        }
                        else{
                            drawMap(pos.lat, pos.lng, ""); 
                        }  
                    });
                }, 
                () => {
                    drawMap("{{ $lat }}", "{{ $lng }}", "");
                }, 
                options
            );
        }
        else {
            drawMap("{{ $lat }}", "{{ $lng }}", "");
        } 
        //////////////////////////////////////////////////////////
            
         
    }

    function drawMap(lat, lng, address){
        map = new google.maps.Map(document.getElementById('map2'), {
            center: new google.maps.LatLng(lat, lng),
            zoom: 17
        }); 

        var icon = { 
            scaledSize: new google.maps.Size(30, 32), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0,0) // anchor
        }; 
 
        var contentHtml = "";
        if(address === ""){
            var prefecture = "{{ \App\Service\AreaService::getPrefectureNameByID($user->prefecture) }}"; 
            contentHtml= '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">〒' + "{{ $user->post_first }}" + '-' + "{{ $user->post_second }}" + prefecture + "{{ $user->address }}" + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' + 
                          '"https://www.google.com/maps/place/{{ $user->address }}/@' + lat + ',' + lng + ',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d{{ $lat }}!4d{{ $lng }}?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';
        }
        else{
            contentHtml= '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="4" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">' + address + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' + 
                          '"https://www.google.com/maps/place/' + address + '/@' + lat + ',' + lng + ',18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d{{ $lat }}!4d{{ $lng }}?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';
        }
        
        var myInfo = new google.maps.InfoWindow({
            content: contentHtml, 
            maxWidth: 350
        }); 

        var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng(lat, lng), 
            icon: icon, 
        });
        
        google.maps.event.addListener(marker, 'click', function() {
            myInfo.open(map,marker);
        });
        
        var request = {
            location: map.getCenter(),
            radius: 5000,  
        }; 
                
        var service = new google.maps.places.PlacesService(map);  
        service.nearbySearch(request, callback);  
        //infowindow = new google.maps.InfoWindow({pixelOffset: new google.maps.Size(300, 0)});
        infowindow = document.createElement("div");  
        CenterControl(infowindow, map); 
        infowindow.style.display = "none"; 
        map.controls[google.maps.ControlPosition.BOTTOM_CENTER].push(infowindow); 
    }

    function callback(results, status) {    
        @php 
            $lat = isset($self_pos['lat']) ? $self_pos['lat'] : null; 
            $lng = isset($self_pos['lng']) ? $self_pos['lng'] : null; 
        @endphp  
           
        centerPos.lat = "{{$lat}}";
        centerPos.lng = "{{$lng}}";  
        if (status == google.maps.places.PlacesServiceStatus.OK) {  
            for (var i = 0; i < store_address.length; i++) {    
                //var dis = mile_distance(centerPos, store_address[i].position);  
                //if(dis < 5)
                {  
                    createMarker(store_address[i]);  
                }  
            }
        }
    }

    function createMarker(place) {    
        var imageUrl = "{{ asset('assets/user/img/icon/pin.png') }}";   
        var icon = {
            url: imageUrl, // url
            scaledSize: new google.maps.Size(30, 32), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0,0) // anchor
        };
        var latlng = new google.maps.LatLng(place.position.lat, place.position.lng);
        var marker = new google.maps.Marker({
            map: map,
            position: latlng,
            title: place.title,
            icon: icon,
            category: place.category,
            address: place.address,
            work_time: place.work_time, 
            product_size:place.product_size,
            image_url:place.image_url,
            link:place.link,
            id:place.id,
            acivte:place.acivte,
            prefecture:place.prefecture,
        });  
        
        google.maps.event.addListener(marker, 'click', function() {    
            infowindow.style.display = "inline-block"; 
            infowindow.style.left = "0px"; 
            document.getElementById('modal-close').onclick = function() {  
                infowindow.style.display = "none"; 
            };

            document.getElementById('pic').innerHTML =  
            '<div id="link">' + 
            '<span>' + marker.product_size + '</span>' +
            '<i id="btn_favorite" style="cursor: pointer;" class="' + marker.acivte + '" data-id="' + marker.id + '"></i>' +
            '<a href=' + marker.link + '>' +
            '<img src=' + marker.image_url + '></div></div>';
            document.getElementById('text_category').innerHTML =
            '<a id="link" href=' + marker.link + '>' +
            "<i class='category'>" + marker.category + "</i>" +
            '<h2 class="shop-name" style="font-size: 13px;">' + marker.title + '</h2>' +
            '<p class="address">' + marker.prefecture + marker.address + '</p>' +
            '<time>営業時間: ' + marker.work_time + '</time></a>';


        }.bind(this)); 
    }

    function CenterControl(controlDiv, map) {
        controlDiv.style.marginBottom = "15px";
        controlDiv.style.backgroundColor = "#fff";  
        controlDiv.style.width = "100%";
        // Set CSS for the control border.
        const controlUI = document.createElement("div"); 
        controlUI.style.backgroundColor = "#fff"; 
        controlUI.style.border = "2px solid #fff"; 
        controlUI.style.borderRadius = "3px"; 

        controlDiv.appendChild(controlUI);
        // Set CSS for the control interior.
        const controlText = document.createElement("div"); 
        controlText.style.backgroundColor = "#fff"; 
        controlText.style.color = "rgb(255,255,255)";

        var listItem= document.getElementById('shop-detail'); 
        controlText.innerHTML = listItem.innerHTML; 
        controlText.style.marginBottom = "10px";
        controlText.style.margin = "10px";
        controlUI.appendChild(controlText);
        // Setup the click event listeners: simply set the map to Chicago.
        controlUI.addEventListener("click", () => {
            map.setCenter(chicago);
        });
    }


    function mile_distance(pos1, pos2) {  
        var R = 3958.8; // Radius of the Earth in miles 
        var rlat1 = pos1.lat * (Math.PI/180); // Convert degrees to radians
        var rlat2 = pos2.lat * (Math.PI/180); // Convert degrees to radians
        var difflat = rlat2-rlat1; // Radian difference (latitudes)
        var difflon = (pos2.lng-pos1.lng) * (Math.PI/180); // Radian difference (longitudes) 
        var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat/2)*Math.sin(difflat/2)+Math.cos(rlat1)*Math.cos(rlat2)*Math.sin(difflon/2)*Math.sin(difflon/2)));
        return d;
    }
      
</script>
@endsection