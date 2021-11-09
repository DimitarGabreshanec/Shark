@extends('store.layouts.app')

@section('title', '通販商品')

@section('header')
    <h1 id="title">通販商品</h1>
@endsection


@section('content')  
    <main>    
    <nav id="sticky">
        <ul class="nav">
            <li ><a href="{{ route('store.ec_products.create', ['product_type' => config('const.product_type_code.ec')])}}">商品情報登録</a></li>
            <li class="active">商品情報編集</a></li>
        </ul>
    </nav>
    {{ Form::open(["route"=>["store.ec_products.update", ['product' => $product]], "method"=>"put", 'files'=>true]) }} 
    <div id="wrapper2">
      <table class="table">
        <tbody><tr>
            <th>商品名</th>
            <td>
                <input type="text" name="product_name" class="input-text" value="{{ old('product_name', isset($product) && isset($product->product_name) ? $product->product_name : '') }}">
                @error('product_name') 
                    <p class="error">{{ $message }}</p> 
                @enderror 
            </td>
        </tr>
        <tr>
            <th>商品紹介</th>
            <td>
                <textarea name="introduction" id="introduction" class="input-text"> {{ old('introduction', isset($product) && isset($product->introduction) ? $product->introduction : '') }}</textarea>
                @error('introduction') 
                    <p class="error">{{ $message }}</p> 
                @enderror 
            </td>
        </tr> 
        <tr>
            <th>商品価格</th>
            <td>
                <input type="text" name="price" class="input-text w100 text-right" value="{{ old('price', isset($product) && isset($product->price) ? $product->price : '') }}">
                @error('price') 
                    <p class="error">{{ $message }}</p> 
                @enderror
            </td>
        </tr>
        <tr> 
            <th>在庫数</th>
            <td>
                <input type="text" name="quantity" class="input-text w100 text-right" value="{{ old('quantity', isset($product) && isset($product->quantity) ? $product->quantity : '') }}">
                @error('quantity') 
                    <p class="error">{{ $message }}</p> 
                @enderror
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
		 
        <input type="hidden" name='type' value="{{ config('const.product_type_code.ec') }}"
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
