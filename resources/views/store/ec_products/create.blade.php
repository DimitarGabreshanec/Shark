@extends('store.layouts.app')

@section('title', '通販商品')

@section('header')
    <h1 id="title">通販商品</h1>
@endsection


@section('content')  
    <main>    
    <nav id="sticky">
      <ul class="nav">
        <li class="active">商品情報登録</li>
        <li><a href="{{ route('store.ec_products.index', ['product_type' => config('const.product_type_code.ec')]) }}">商品情報一覧</a></li>
      </ul>
    </nav>
    {{ Form::open(["route"=>["store.ec_products.store"], "method"=>"put", 'files'=>true]) }} 
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
		 
        <input type="hidden" name='type' value="{{ config('const.product_type_code.ec') }}"
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

        $(document).on('click', '.clear2', function(e){   
            var sequence = $(this).data('sequence_');  
            $('.li_' + sequence).remove();   
        });

        $(document).on('click', '#clear3', function(e){ 
            $('.imagebox_main').html(''); 
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
                            '<input type="button" class="clear2" id="clear2" data-sequence_="' + 'old' + $index + '"value="ファイルを削除する">' + '</li>');
                        }
                        fr.readAsDataURL(files[i]);
                    })();
                    
                }
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
            })

           
    }); 
</script>
@endsection
