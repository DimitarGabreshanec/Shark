@extends('store.layouts.app')

@section('title', '店頭商品')

@section('header')
    <h1 id="title">店頭商品</h1>
@endsection


@section('content')  
    <main>   
        <nav id="sticky">
            <ul class="nav">
                <li ><a href="{{ route('store.fix_products.create', ['product_type' => config('const.product_type_code.fix')]) }}">商品情報登録</a></li>
                <li class="active">商品情報編集</a></li>
            </ul>
        </nav>
        {{ Form::open(["route"=>["store.fix_products.update", ['product' => $product]], "method"=>"put", 'files'=>true]) }} 
        <div id="wrapper2">
        <table class="table">
            <tbody><tr>
                <th>商品名</th>
                <td>
                    <input type="text" name="product_name" class="input-text" value="{{ old('product_name', isset($product->product_name) ? $product->product_name : '') }}">
                    @error('product_name') 
                        <p class="error">{{ $message }}</p> 
                    @enderror 
                </td>
            </tr>
            <tr>
                <th>飲食店種類</th>
                <td>
                    <div class="radiobtn-wrap f-left">
                        @foreach(config('const.restaurant_kind') as $key => $value)
                            <input type="radio" name="restaurant_kind" id="restaurant_kind{{ $key }}" value="{{ $key }}" {{ $key==old('restaurant_kind', isset($product->restaurant_kind) ? $product->restaurant_kind : 1) ? 'checked' : '' }}>
                            <label for="restaurant_kind{{ $key }}">{{ $value }}</label>
                        @endforeach
                    </div> 
        
                    @php
                        $restaurant_kind = old('restaurant_kind', isset($product->restaurant_kind) ? $product->restaurant_kind : '1' );
                    @endphp
        
                    @error('restaurant_kind')
                    <p class="error">{{ $message }}</p> 
                    @enderror
                </td>
            </tr>   
    
            <tr class="view-element restaurant_kind {{ $restaurant_kind == 2 ? '' : 'hide' }}" id='res_kind'>
                <th>予約金</th>
                <td>
                    <input type="text" name="restaurant_deposit" class="input-text text-right" id="deposite" value="{{ old('restaurant_deposit', isset($product->restaurant_deposit) ? $product->restaurant_deposit : '') }}">&nbsp;円
                    @error('restaurant_deposit')
                    <p class="error">{{ $message }}</p> 
                    @enderror
                </td>
            </tr>
            <tr>
                <th>出品理由</th>
                <td>
                    <textarea name="list_reason" id="list_reason" class="input-text"> {{ old('list_reason', isset($product->list_reason) ? $product->list_reason : '') }}</textarea>
                    @error('list_reason') 
                        <p class="error">{{ $message }}</p> 
                    @enderror 
                </td>
            </tr>
            <tr>
                <th>引取可能時間</th>
                <td>
                    <div class="avail_time"> 
                        @php
                            $available_from_hour = old('available_from_hour',  isset($product->available_from) ? Carbon::parse($product->available_from)->isoFormat('H') : '' );
                        @endphp
                        <select name="available_from_hour" id="available_from_hour" class="avail_time">

                            <option value="">--</option>
                            @for ($i = 0; $i < 24; $i++)
                                <option value="{{$i}}" {{ $available_from_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select> 
                    </div> 
                    <div class="avail_one"> 
                    時
                    </div>
                    <div class="avail_time"> 
                        <select name="available_from_minute" id="available_from_minute" class="avail_time">
                            @php
                                $available_from_minute = old('available_from_minute',  isset($product->available_from) ? Carbon::parse($product->available_from)->isoFormat('m') : '' );
                            @endphp
                            <option value="">--</option>
                            @for ($i = 0; $i < 60; $i++)
                                <option value="{{$i}}" {{ $available_from_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select> 
                    </div> 
                    <div class="avail_one"> 
                    分
                    </div>
    
                    <div class="available">&nbsp;&nbsp;~</div>&nbsp;&nbsp;
                    <div class="avail_time"> 
                        @php
                            $available_to_hour = old('available_to_hour',  isset($product->available_to) ? Carbon::parse($product->available_to)->isoFormat('H') : '' );
                        @endphp
                        <select name="available_to_hour" id="available_to_hour" class="avail_time">

                            <option value="">--</option>
                            @for ($i = 0; $i < 24; $i++)
                                <option value="{{$i}}" {{ $available_to_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>  
                    </div> 
                    <div class="avail_one"> 
                    時
                    </div>
                    <div class="avail_time"> 
                        <select name="available_to_minute" id="available_to_minute" class="avail_time">
                            @php
                                $available_to_minute = old('available_to_minute',  isset($product->available_to) ? Carbon::parse($product->available_to)->isoFormat('m') : '' );
                            @endphp
                            <option value="">--</option>
                            @for ($i = 0; $i < 60; $i++)
                                <option value="{{$i}}" {{ $available_to_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>  
                    </div> 
                    <div class="avail_one"> 
                    分
                    </div>
                    <input type="hidden" name="available_from" value="{{ isset($product->available_from) ? $product->available_from : ''  }}">
                    <div>
                        @error('available_from') 
                            <p class="error">{{ $message }}</p> 
                        @enderror 
                    </div>
                    <input type="hidden" name="available_to" value="{{ isset($product->available_to) ? $product->available_to : ''  }}">
                    <div>
                        @error('available_to') 
                            <p class="error">{{ $message }}</p> 
                        @enderror
                    </div>
                </td>
            </tr>
            <tr>
                <th>出品価格</th>
                <td>
                    <input type="text" name="price" class="input-text w100 text-right" value="{{ old('price', isset($product->price) ? $product->price : '') }}">
                    @error('price') 
                        <p class="error">{{ $message }}</p> 
                    @enderror
                </td>
            </tr> 
            <tr>
                <th>掲載期間</th>
                <td>
                    <div class="post_date calendar-wrap"> 
                        <input type="text" name="post_from_date" placeholder="当日年月日" class="datepicker" value="{{ old('post_from_date', isset($product->post_from) ? Carbon::parse($product->post_from)->format('Y-m-d') : '') }}">
                    </div>  
                    <div class="post_time_h"> 
                        @php
                            $post_from_hour = old('post_from_hour',  isset($product->post_from) ? Carbon::parse($product->post_from)->isoFormat('H') : '' );
                        @endphp
                        <select name="post_from_hour" id="post_from_hour" style="width: 100%"> 
                            <option value="">--</option>
                            @for ($i = 0; $i < 24; $i++)
                                <option value="{{$i}}" {{ $post_from_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="avail_one">
                        <span>時</span>
                    </div>
                    <div class="post_time"> 
                        <select name="post_from_minute" id="post_from_minute" style="width: 100%">
                            @php
                                $post_from_minute = old('post_from_minute',  isset($product->post_from) ? Carbon::parse($product->post_from)->isoFormat('m') : '' );
                            @endphp
                            <option value="">--</option>
                            @for ($i = 0; $i < 60; $i++)
                                <option value="{{$i}}" {{ $post_from_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select> 
                    </div> 
                    <div class="avail_one">
                        <span>分</span>
                    </div>
                    <div class="available">&nbsp;&nbsp;~</div>&nbsp;&nbsp;
                    <div class="post_date calendar-wrap"> 
                        <input type="text" name="post_to_date" placeholder="当日年月日" class="datepicker" value="{{ old('post_to_date', isset($product->post_to) ? Carbon::parse($product->post_to)->format('Y-m-d') : '') }}">
                    </div> 

                    <div class="post_time_h"> 
                        @php
                            $post_to_hour = old('post_to_hour',  isset($product->post_to) ? Carbon::parse($product->post_to)->isoFormat('H') : '' );
                        @endphp
                        <select name="post_to_hour" id="post_to_hour" style="width: 100%">

                            <option value="">--</option>
                            @for ($i = 0; $i < 24; $i++)
                                <option value="{{$i}}" {{ $post_to_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select> 
                    </div> 
                    <div class="avail_one">
                        <span>時</span>
                    </div>
                    <div class="post_time"> 
                        <select name="post_to_minute" id="post_to_minute" style="width: 100%">
                            @php
                                $post_to_minute = old('post_to_minute',  isset($product->post_to) ? Carbon::parse($product->post_to)->isoFormat('m') : '' );
                            @endphp
                            <option value="">--</option>
                            @for ($i = 0; $i < 60; $i++)
                                <option value="{{$i}}" {{ $post_to_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                            @endfor
                        </select> 
                    </div> 
                    <div class="avail_one">
                        <span>分</span>
                    </div>

                    <input type="hidden" name="post_to" value="{{ isset($product->post_to) ? $product->post_to : ''  }}">
                    <input type="hidden" name="post_from" value="{{ isset($product->post_from) ? $product->post_from : ''  }}">

                    <div>
                        @error('post_from')
                        <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        @error('post_to')
                        <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </td>   
            </tr> 
            <tr>
                <th>メイン画像</th>
                <td> 
                    <button id="fileforbutton" onclick="document.getElementById('main_img').click(); return false;">ファイル選択</button>
                    <input type="file" name="main_img" id="main_img" accept="image/*" class="one" >
                    <div class="imagebox_main">  
                        @if(is_object($product->obj_main_img) && Storage::disk('products')->has("{$product->id}/{$product->obj_main_img->img_name}"))
                            <li class="li_{{ $product->obj_main_img->sequence }}">     
                                <img class="w250" src="{{ asset("storage/products/{$product->id}/{$product->obj_main_img->img_name}") }}">
                                <input type="button" id="clear3" data-image_name="{{ $product->obj_main_img->img_name }}" data-sequence_="{{ $product->obj_main_img->img_name }}" value="ファイルを削除する" style="display: block !important">
                            </li>
                            <input type="hidden" id="main_img_name" name="main_img_name" value="{{ $product->obj_main_img->img_name }}">
                        @endif
                        
                    </div>
                    @error('main_img_name')
                    <p class="error">{{ $message }}</p>  
                    @enderror 
                </td>      
            </tr>
            <tr>
                <th>サブ画像(複数選択可)</th>
                <td>
                    <button id="fileforbutton1" onclick="document.getElementById('sub_img').click(); return false;">ファイル選択</button>
                    <input type="file" id="sub_img" name="sub_img[]" accept="image/*"  multiple class="many">
                    <div class="imagebox_sub">  
                        @if($product->obj_sub_img->count() > 0)
                        <ul id="sub_old_img"> 
                            @foreach($product->obj_sub_img as $obj_sub_img_one) 
                            @if(is_object($obj_sub_img_one) && Storage::disk('products')->has("{$product->id}/{$obj_sub_img_one->img_name}"))
                                <li class="li_{{ $obj_sub_img_one->sequence }}">
                                    <img class="w250" src="{{ asset("storage/products/{$product->id}/{$obj_sub_img_one->img_name}") }}"> 
                                    <input type="button" id="clear3" data-image_name="{{ $obj_sub_img_one->img_name }}" data-sequence_="{{ $obj_sub_img_one->sequence }}" class="clear_sub" value="ファイルを削除する">
                                </li>
                            @endif 
                            @endforeach 
                        </ul>
                        @endif
                    </div>   
                </td>
            </tr>
            <input type="hidden" name='type' value="{{ config('const.product_type_code.fix') }}"
        </tbody></table> 
        <input type="submit" value="更新する">
        </div>  
        {{ Form::close() }}
    </main>
    
@endsection





@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/store/css/pages/products_create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}">
@endsection
 

@section('page_js')   
<script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.js') }}"></script> 
<script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
<script>  
     
    $(document).ready(function(){
           
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
        
        $("#sub_img").on('change', function(e){    
            var tgt = e.target || window.event.srcElement,
                files = tgt.files;
            var form_data = new FormData();
            var totalfiles = files.length;
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("sub_img[]", files[index]);
            }
            form_data.append("_token", "{{csrf_token()}}");  
            form_data.append("product_id", "{{ $product->id }}");
            $.ajax({
                type: "post",
                url : '{{ route("store.fix_products.add_img") }}',
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
                url : '{{ route("store.fix_products.add_img") }}',
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
            if (confirm("画像を削除しますか？") == true) { 
                var image_name = $(this).data('image_name'); 
                $.ajax({
                    type: "post",
                    url : '{{ route("store.fix_products.delete_img") }}',
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
                }
        });
            $('.datepicker').datepicker({ 
                dateFormat: 'yy-mm-dd',
                showOn:"both",
                buttonImage:"{{ asset('assets/admin/img/calender.png') }}",
                buttonImageOnly:true
            });

            $('.ui-datepicker-trigger').hide();
            $(".calendar-wrap span img").css('display', 'none');
            $(".calendar-wrap img").css('padding', '3px 0');
            $(".calendar-datetime img").css('display', 'inline');

            lightbox.option({
                'resizeDuration': 200,
                'wrapAround': true
            });

            
    });
</script>
@endsection
