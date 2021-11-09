@include('admin.layouts.flash-message')
@isset($product)
    <input type="hidden" name="id" value="{{ $product->id }}">
@endisset
<table class="normal">

    @isset($product)
        <tr>
            <th>商品番号</th>
            <td>{{ $product->product_no }}</td>
        </tr>
    @endisset
    <tr>
        <th>商品種類</th>
        <td>
            <div class="radiobtn-wrap f-left">
                @foreach(config('const.product_type') as $key => $value)
                    <input type="radio" {{ isset($product) ? 'disabled' : '' }} name="type" id="type{{ $key }}" value="{{ $key }}" {{ $key==old('type', isset($product->type) ? $product->type : 1) ? 'checked' : '' }}>
                    <label for="type{{ $key }}" class="switch-on">{{ $value }}</label>
                @endforeach
            </div>
            @if(isset($product))
                <input type="hidden" name="type" value="{{ $product->type }}">
            @endif

            @php
                $type = old('type', isset($product->type) ? $product->type : '1' );
            @endphp

            @error('type')
            <span class="error_text">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
        </td>
    </tr> 

    <th>店舗</th>
    <td>
        <div class="select-wrap display-inline-flex">
            <label for="store_id">
                <select name="store_id" id="store_id" style="width: 100%">
                    @php
                        $store_id = old('store_id',  isset($product) && isset($product->store_id) ? $product->store_id : '' );
                    @endphp 
                    <option value="">--</option>
                    @foreach($stores_params as $key => $value)
                        <option value="{{$key}}" {{ $store_id == $key ? "selected" : "" }}>{{ $value }}</option>
                    @endforeach
                </select> 
            </label>
        </div>
        @error('store_id')
        <span class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </td>

    <tr>
        <th>商品名</th>
        <td>
            <input type="text" name="product_name" class="input-text" value="{{ old('product_name', isset($product->product_name) ? $product->product_name : '') }}">
            @error('product_name')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr class="view-element type-1 {{ $type==1 ? '' : 'hide' }}">
        <th>飲食店種類</th>
        <td>
            <div class="radiobtn-wrap f-left">
                @foreach(config('const.restaurant_kind') as $key => $value)
                    <input type="radio" name="restaurant_kind" id="restaurant_kind{{ $key }}" value="{{ $key }}" {{ $key==old('restaurant_kind', isset($product->restaurant_kind) ? $product->restaurant_kind : 1) ? 'checked' : '' }}>
                    <label for="restaurant_kind{{ $key }}" class="switch-on">{{ $value }}</label>
                @endforeach
            </div> 

            @php
                $restaurant_kind = old('restaurant_kind', isset($product->restaurant_kind) ? $product->restaurant_kind : '1' );
            @endphp

            @error('restaurant_kind')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>   
    <tr class="view-element type-1 restaurant_kind {{ ($type==1 && $restaurant_kind==2) ? '' : 'hide' }}" id='res_kind'>
        <th>予約金</th>
        <td>
            <input type="text" name="restaurant_deposit" class="input-text w100 text-right" value="{{ old('restaurant_deposit', isset($product->restaurant_deposit) ? $product->restaurant_deposit : '') }}">&nbsp;円
            @error('restaurant_deposit')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    {{-- 店頭商品 --}}
    <tr class="view-element type-1 {{ $type==1 ? '' : 'hide' }}">
        <th>出品理由</th>
        <td>
            <textarea name="list_reason" id="list_reason" class="input-text"> {{ old('list_reason', isset($product->list_reason) ? $product->list_reason : '') }}</textarea>
            @error('list_reason')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr class="view-element type-1 {{ $type==1 ? '' : 'hide' }}">
        <th>引取可能時間</th>
        <td>
            <div class="select-wrap display-inline-flex">
                <label for="available_from_hour">
                    @php
                        $available_from_hour = old('available_from_hour',  isset($product->available_from) ? Carbon::parse($product->available_from)->isoFormat('H') : '' );
                    @endphp
                    <select name="available_from_hour" id="available_from_hour" style="width: 100%">

                        <option value="">--</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{$i}}" {{ $available_from_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;時</span>
            </div>&nbsp;&nbsp;&nbsp;
            <div class="select-wrap display-inline-flex">
                <label for="available_from_minute">
                    <select name="available_from_minute" id="available_from_minute" style="width: 100%">
                        @php
                            $available_from_minute = old('available_from_minute',  isset($product->available_from) ? Carbon::parse($product->available_from)->isoFormat('m') : '' );
                        @endphp
                        <option value="">--</option>
                        @for ($i = 0; $i < 60; $i++)
                            <option value="{{$i}}" {{ $available_from_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;分</span>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="select-wrap display-inline-flex">~</div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

            <div class="select-wrap display-inline-flex">
                <label for="available_to_hour">
                    @php
                        $available_to_hour = old('available_to_hour',  isset($product->available_to) ? Carbon::parse($product->available_to)->isoFormat('H') : '' );
                    @endphp
                    <select name="available_to_hour" id="available_to_hour" style="width: 100%">

                        <option value="">--</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{$i}}" {{ $available_to_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;時</span>
            </div>&nbsp;&nbsp;&nbsp;
            <div class="select-wrap display-inline-flex">
                <label for="available_to_minute">
                    <select name="available_to_minute" id="available_to_minute" style="width: 100%">
                        @php
                            $available_to_minute = old('available_to_minute',  isset($product->available_to) ? Carbon::parse($product->available_to)->isoFormat('m') : '' );
                        @endphp
                        <option value="">--</option>
                        @for ($i = 0; $i < 60; $i++)
                            <option value="{{$i}}" {{ $available_to_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;分</span>
            </div>

            <input type="hidden" name="available_from" value="{{ isset($product->available_from) ? $product->available_from : ''  }}">
            <div>
                @error('available_from')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <input type="hidden" name="available_to" value="{{ isset($product->available_to) ? $product->available_to : ''  }}">
            <div>
                @error('available_to')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>

    </tr>

    <tr class="view-element type-1 {{ $type==1 ? '' : 'hide' }}">
        <th>掲載開始日</th>
        <td>
            <div class="calendar-wrap display-inline-flex">
                <label for="post_from_date">
                    <input type="text" name="post_from_date" class="datepicker" value="{{ old('post_from_date', isset($product->post_from) ? Carbon::parse($product->post_from)->format('Y-m-d') : '') }}">
                </label>
            </div>&nbsp;&nbsp;&nbsp;

            <div class="select-wrap display-inline-flex">
                <label for="post_from_hour">
                    @php
                        $post_from_hour = old('post_from_hour',  isset($product->post_from) ? Carbon::parse($product->post_from)->isoFormat('H') : '' );
                    @endphp
                    <select name="post_from_hour" id="post_from_hour" style="width: 100%">

                        <option value="">--</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{$i}}" {{ $post_from_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;時</span>
            </div>&nbsp;&nbsp;&nbsp;
            <div class="select-wrap display-inline-flex">
                <label for="post_from_minute">
                    <select name="post_from_minute" id="post_from_minute" style="width: 100%">
                        @php
                            $post_from_minute = old('post_from_minute',  isset($product->post_from) ? Carbon::parse($product->post_from)->isoFormat('m') : '' );
                        @endphp
                        <option value="">--</option>
                        @for ($i = 0; $i < 60; $i++)
                            <option value="{{$i}}" {{ $post_from_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;分</span>
            </div>

            <input type="hidden" name="post_from" value="{{ isset($product->post_from) ? $product->post_from : ''  }}">

            <div>
                @error('post_from')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>
    </tr>

    <tr class="view-element type-1 {{ $type==1 ? '' : 'hide' }}">
        <th>掲載停止日</th>
        <td>
            <div class="calendar-wrap display-inline-flex">
                <label for="post_to_date">
                    <input type="text" name="post_to_date" class="datepicker"  value="{{ old('post_to_date', isset($product->post_to) ? Carbon::parse($product->post_to)->format('Y-m-d') : '') }}">
                </label>
            </div>&nbsp;&nbsp;&nbsp;

            <div class="select-wrap display-inline-flex">
                <label for="post_to_hour">
                    @php
                        $post_to_hour = old('post_to_hour',  isset($product->post_to) ? Carbon::parse($product->post_to)->isoFormat('H') : '' );
                    @endphp
                    <select name="post_to_hour" id="post_to_hour" style="width: 100%">

                        <option value="">--</option>
                        @for ($i = 0; $i < 24; $i++)
                            <option value="{{$i}}" {{ $post_to_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;時</span>
            </div>&nbsp;&nbsp;&nbsp;
            <div class="select-wrap display-inline-flex">
                <label for="post_to_minute">
                    <select name="post_to_minute" id="post_to_minute" style="width: 100%">
                        @php
                            $post_to_minute = old('post_to_minute',  isset($product->post_to) ? Carbon::parse($product->post_to)->isoFormat('m') : '' );
                        @endphp
                        <option value="">--</option>
                        @for ($i = 0; $i < 60; $i++)
                            <option value="{{$i}}" {{ $post_to_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                        @endfor
                    </select>
                </label>
            </div>
            <div class="select-wrap display-inline-flex">
                <span>&nbsp;&nbsp;分</span>
            </div>

            <input type="hidden" name="post_to" value="{{ isset($product->post_to) ? $product->post_to : ''  }}">

            <div>
                @error('post_to')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </td>
    </tr>
    {{-- 通販商品 --}}
    <tr class="view-element type-2 {{ $type==2 ? '' : 'hide' }}">
        <th>商品紹介</th>
        <td>
            <textarea name="introduction" id="introduction" class="input-text"> {{ old('introduction', isset($product->introduction) ? $product->introduction : '') }}</textarea>
            @error('introduction')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr class="view-element type-2 {{ $type==2 ? '' : 'hide' }}">
        <th>在庫数</th>
        <td>
            <input type="text" name="quantity" class="input-text w100 text-right" value="{{ old('quantity', isset($product->quantity) ? $product->quantity : '') }}">
            @error('quantity')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>

    <tr>
        <th>出品価格</th>
        <td>
            <input type="text" name="price" class="input-text w100 text-right" value="{{ old('price', isset($product->price) ? $product->price : '') }}">&nbsp;円
            @error('price')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>
    <tr>
        <th>送料価格</th>
        <td>
            <input type="text" name="ship_price" class="input-text w100 text-right" value="{{ old('ship_price', isset($product->ship_price) ? $product->ship_price : '') }}">&nbsp;円
            @error('ship_price')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>
    <tr>
        <th>割引</th>
        <td>
            <div class="products-wrap">
                <div class="radiobtn-wrap f-left">
                    <input type="radio" name="discount_type" class="member-discount" id="discount_type_amount"
                           value="{{ config('const.discount_type.percent') }}" {{ old("discount_type",  isset($product) && $product->discount_type ? $product->discount_type : config('const.discount_type.percent')) == config('const.discount_type.percent') ? "checked" : "" }}>
                    <label for="discount_type_amount" class="radio-label">割引率で設定</label>
                    <input type="radio" name="discount_type" id="discount_type_percent" class="member-discount"
                           value="{{ config('const.discount_type.amount') }}" {{ old("discount_type", isset($product) && $product->discount_type ? $product->discount_type : '') == config('const.discount_type.amount') ? "checked" : "" }}>
                    <label for="discount_type_percent" class="radio-label">割引額で設定</label>
                </div>
                <div class="discount-wrap">
                    <div class="select-body">
                        <input type="number" value="{{ old("discount", isset($product) ? $product->discount : '') }}" class="input-text text-right" name="discount" id="discount" min="0">
                    </div>
                    <div class="select-title"><span id="discount_desc">{{ old("discount_type", isset($product)  && $product->discount_type ? $product->discount_type : config('const.discount_type.percent')) == config('const.discount_type.percent') ? "%" : "円" }}</span></div>
                </div>
            </div>
            @error('discount')
            <span class="error_text">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </td>
    </tr>
    <tr>
        <th>備考</th>
        <td>
            <textarea name="note" id="detail"
                      class="input-text"> {{ old('note', isset($product->note) ? $product->note : '') }}</textarea>

            @error('note')
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
                @if(isset($product) && is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))
                    <li class="li_{{ $product->obj_main_img->sequence }}">     
                    <img src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}">
                    <input type="button" id="clear3" data-image_name="{{ $product->obj_main_img->img_name }}" data-sequence_="{{ $product->obj_main_img->img_name }}" value="ファイルを削除する" style="display: block !important">
                    <input type="hidden" id="main_img_name" name="main_img_name" value="{{ $product->obj_main_img->img_name }}">
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
                @if(isset($product) && $product->obj_sub_img->count() > 0)
                <ul id="sub_old_img"> 
                    @foreach($product->obj_sub_img as $obj_sub_img_one) 
                    @if(is_object($obj_sub_img_one) && Storage::disk('products')->has("{$product->id}/{$obj_sub_img_one->img_name}"))
                        <li class="li_{{ $obj_sub_img_one->sequence }}">
                            <img  src="{{ asset("storage/products/{$product->id}/{$obj_sub_img_one->img_name}") }}"> 
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
    <link rel="stylesheet" href="{{ asset('assets/admin/css/pages/products_form.css') }}"> 
@endsection

@section('page_js') 
    <script>
        $(document).ready(function(){
        @if($mode == 'edit')
        {
                $("#sub_img").on('change', function(e){    
                var tgt = e.target || window.event.srcElement,
                    files = tgt.files;
                var form_data = new FormData();
                var totalfiles = files.length;
                for (var index = 0; index < totalfiles; index++) {
                    form_data.append("sub_img[]", files[index]);
                }
                form_data.append("_token", "{{csrf_token()}}");  
                form_data.append("product_id", "{{  $product->id }}");
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.products.upload_img") }}',
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
                form_data.append("product_id", "{{ $product->id }}");
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.products.upload_img") }}',
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
                        url : '{{ route("admin.products.delete_img") }}',
                        data: {
                            image_name: image_name, 
                            product_id: "{{ $product->id }}",
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
 
        }
        @else
        {
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
        }
        @endif

        $(".member-discount").click(function(){
            if($(this).val() == "{{ config('const.discount_type.percent') }}")
            {
                $("#discount_desc").text("%");
            }
            else
            {
                $("#discount_desc").text("円");
            }
        });

        $('input[name="type"]').click(function () {
            if ($(this).is(':checked')) {
                var vis_flag = document.getElementById('res_kind').style.display; 
                var type = $(this).val();  
                $('.view-element').hide(); 
                $('.type-' + type).show();  
                document.getElementById('res_kind').style.display = vis_flag;

            }
        });  

        $('input[name="restaurant_kind"]').click(function () {
            if ($(this).is(':checked')) {
                var restaurant_kind = $(this).val();
                if(restaurant_kind == 2){
                    $('.restaurant_kind').show(); 
                } else {
                    $('.restaurant_kind').hide();  
                } 
            }
        }); 
        
    });
    </script>
@endsection

