@extends('store.layouts.app')

@section('title', '店頭商品')

@section('header')
    <h1 id="title">店頭商品</h1>
@endsection


@section('content')  
    <main>   
    <nav id="sticky">
      <ul class="nav">
        <li class="active">商品情報登録</li>
        <li><a href="{{ route('store.fix_products.index', ['product_type' => config('const.product_type_code.fix')]) }}">商品情報一覧</a></li>
      </ul>
    </nav>
    {{ Form::open(["route"=>["store.fix_products.store"], "method"=>"put", 'files'=>true]) }} 
    <div id="wrapper2">
      <table class="table">
        <tbody>
        <tr>
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
                    <p class="error">{{ $message }}</p>  
                    @enderror
                    @error('post_to')
                    <p class="error">{{ $message }}</p>  
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
                    <ul id="sub_old_img"> 
                         
                    </ul> 
                </div>  
            </td>
        </tr>
        <input type="hidden" name='type' value="{{ config('const.product_type_code.fix') }}"
      </tbody></table> 
      <input type="submit" value="登録する">
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
        }) 
    }); 
</script>
@endsection
