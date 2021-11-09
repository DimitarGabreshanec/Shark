@extends('user.layouts.app')

@section('title', 'イマスグを探す')

@section('header')
    <h1 id="title">お店で探す</h1>
@endsection


@section('content') 
    <main>
        @php
            $parentName = isset($parentName) ? $parentName : '';
        @endphp
        <nav>
            <ul class="nav">
                <li class="active">イマスグを探す</li>
                <li><a href="{{ route('user.stores.search')  }}">お店を探す</a></li>
            </ul>
        </nav>
        @if(!isset($self_pos['lat']) || !isset($self_pos['lat']))
            <div id="wrapper" class="contents">
                <table class="table">
                <tr>
                    <th style="text-align: center;">住所を入力してください。</th>
                </tr>
                </table>
                <input type="submit" value="基本情報へ行く" onclick="location.href='{{ route('user.my_account.edit') }}'; return false;"> 

            </div>
        @else
            <p class="search-list-btn"><a href="{{ route('user.stores.imasugu_search', ['view_kind'=>'list'])  }}">リストから探す</a></p>
            <p class="change-btn"><a href="{{ route('user.stores.imasugu_cond', ['back'=>'map'])  }}">表示するカテゴリを変更する</a></p>
            <div id="map">
                <!--iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.2296219427067!2d139.70788901612636!3d35.646713880202306!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b4046e3f71d%3A0x85ab1d92ef294edf!2z5oG15q-U5a-_6aeF!5e0!3m2!1sja!2sjp!4v1598963478541!5m2!1sja!2sjp" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe-->
                <!--div class="pin"><a data-target="shop-detail" class="modal-open"><img src="{{ asset('assets/user/img/icon/pin.png') }}" alt="ピン"></a></div-->
            </div>
        @endif
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



@section('page_css')
            <link rel="stylesheet" href="{{ asset('assets/user/css/pages/store_imasugu.css') }}">
@endsection



@section('page_js')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
<script  src="https://maps.googleapis.com/maps/api/js?key={{ config('const.google_api_key') }}&callback=initMap&libraries=places" defer></script>
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
        @php
        $stores = \App\Service\StoreService::getAllImasuguStores();
        //$search_params['categories'] = \App\Service\StoreService::getImasuguCategories();
        @endphp
         
        @isset($stores)
        @foreach($stores as $store) 
            @php
            if(isset($search_params['categories'])){
                $select_cateories = $search_params['categories'];
                foreach($store->arr_categories() as $value){
                    if(in_array($value, $select_cateories)){
                        $parentName = $value;
                        $parent = \App\Service\CategoryService::getCategoryName($parentName);
                        $parentName = $parent[0]; 
                        break;
                    }
                } 
                 
            }else{  
                $parent = \App\Service\CategoryService::getCategoryName($store->arr_categories());
                $parentName = $parent[0]; 
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

        map = new google.maps.Map(document.getElementById('map'), {
            center: new google.maps.LatLng("{{$lat}}", "{{$lng}}"),
            zoom: 17
        });

        var icon = {
            scaledSize: new google.maps.Size(30, 32), // scaled size
            origin: new google.maps.Point(0,0), // origin
            anchor: new google.maps.Point(0,0) // anchor
        };
        if("{{ $lat }}" == "" || "{{ $lng }}" == ""){
            return;
        }
        var prefecture = "{{ \App\Service\AreaService::getPrefectureNameByID($user->prefecture) }}"; 

        var contentHtml = '<div jstcache="33" class="poi-info-window gm-style">' +
                          '<div jstcache="1">' +
                          '<div jstcache="2" jsinstance="0" class="address-line full-width" jsan="7.address-line,7.full-width">〒' + "{{ $user->post_first }}" + '-' + "{{ $user->post_second }}" + prefecture + "{{ $user->address }}" + '</div>' +
                          '<div jstcache="5" style="display:none"></div>' +
                          '<div class="view-link"> <a target="_blank" jstcache="6" href=' +
                          '"https://www.google.com/maps/place/{{ $user->address }}/@{{ $lat }},{{ $lng }},18z/data=!4m5!3m4!1s0x0:0x85ab1d92ef294edf!8m2!3d{{ $lat }}!4d{{ $lng }}?hl=ja">' +
                          '<span> 拡大地図を表示 </span> </a> </div>';

        var myInfo = new google.maps.InfoWindow({
            content: contentHtml,
            maxWidth: 350
        });

        var marker = new google.maps.Marker({
            map: map,
            position: new google.maps.LatLng("{{$lat}}", "{{$lng}}"),
            icon: icon,
        });

        google.maps.event.addListener(marker, 'click', function() {
            myInfo.open(map,marker);
        });

        var request = {
            location: map.getCenter(),
            radius: 5,
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

        //if (status == google.maps.places.PlacesServiceStatus.OK) {
            for (var i = 0; i < store_address.length; i++) {
                //var dis = mile_distance(centerPos, store_address[i].position);
                //if(dis < 5)
                {
                    createMarker(store_address[i]);
                }
            }
        //}
    }

    function createMarker(place) {
        var imageUrl = "{{ asset('assets/user/img/icon/pin.png') }}"
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
