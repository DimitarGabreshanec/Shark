@extends('store.layouts.auth')

@section('title', '会員登録')

@section('header')
    <h1 id="title">会員登録</h1>
@endsection

@section('content')
    <main>
        {{ Form::open(['route' => ['store.register.set_store', ['store'=>$store->id]], 'id' => 'frm_user_data', 'name' => 'frm_user_data', 'method' => 'POST', 'files'=>true]) }}
            <div id="wrapper" class="contents">
                <table class="table">
                    <tr>
                        <th>会員種別<span>※</span></th>
                        <td>
                            <ul class="line">
                            @foreach(config('const.store_login_type') as $key => $value)
                                <li>
                                    <input type="radio" name="type" id="type{{ $key }}" data_id_="{{ $key }}" onclick="checkTypes(this)" value="{{ $key }}" {{ $key==old('type', isset($store->type) ? $store->type : '') ? 'checked' : '' }}>
                                    <label for="type{{ $key }}" class="switch-on">{{ $value }}</label>
                                </li> 
                            @endforeach
                            </ul>
                            @error('type')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                        @php
                            $type = old('type', isset($store) && isset($store->type) ? $store->type : 1 );
                        @endphp
                        
                    </tr>
                    <tr>
                        <th>メイン画像<span>※</span></th>
                        <td>
                            <button id="fileforbutton" onclick="document.getElementById('main_image').click(); return false;">ファイル選択</button>
                            <input type="file" name="main_image" id="main_image" accept="image/*" class="one" >
                            <div class="imagebox"> 
                                @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                                    <img class="w250" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}">
                                    <input type="button" id="clear3" value="ファイルを削除する" onclick="removeMainImg();" style="display: block !important">
                                @endif
                            </div>
                            <input type="button" id="clear" value="ファイルを削除する" onclick="removeMainImg();">
                            @error('main_img')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>   
                        
                    </tr>
                    <tr class="appears" {{ $type==1 ? '' : 'hide' }}>
                        <th>会社名・屋号<span>※</span></th>
                        <td>
                            <input type="text" name="store_name">
                            @error('store_name')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="appears" {{ $type==1 ? '' : 'hide' }}>
                        <th>所在地<span>※</span></th>
                        <td>
                            <input type="text" name="store_address" value = "{{ old('store_address', isset($store->store_address) ? $store->store_address : '') }}">
                            @error('store_address')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr class="appears" {{ $type==1 ? '' : 'hide' }}>
                        <th>電話番号<span>※</span></th>
                        <td>
                            <input type="text" name="tel" value = "{{ old('tel', isset($store->tel) ? $store->tel : '') }}">
                            @error('tel')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>ご担当者名<span>※</span></th>
                        <td>
                            <input type="text" name="email" value="{{ $store->email }}" readonly>
                            @error('email')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>ログインパスワード<span>※</span></th>
                        <td>
                            <ul class="list">
                                <li>
                                    <input type="password" name="password" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" placeholder="入力してください。" }}">
                                </li>
                                @error('password')
                                <p class="error">{{ $message }}</p>
                                @enderror
                                <li>
                                    <input type="password" name="password_confirmation" pattern="^[a-zA-Z0-9!-/:-@¥[-`{-~]*$" placeholder="確認のため再度入力してください。" }}">
                                </li>
                                @error('password_confirmation')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </ul> 
                           
                        </td> 
                    </tr> 
                    <tr>
                        <th>プライバシーポリシー</th>
                        <td><div class="privacy">
                                <h2>タイトルタイトルタイトルタイトル</h2>
                                <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
                                <p>テキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキストテキスト</p>
                            </div>
                            <div class="ok">
                                <input type="checkbox" id="agree_privacy" name="agree_privacy" value="ok"  {{ old('agree_privacy') == 'ok' ? 'checked' : '' }}>
                                <label for="agree_privacy">同意する</label>
                            </div></td>
                    </tr>
                </table>
                <input type="submit" id="btn_submit" class="{{ old('agree_privacy') == 'ok' ? '' : 'disabled' }}" value="登録する" {{ old('agree_privacy') == 'ok' ? '' : 'disabled' }}>
            </div>
        {{ Form::close()}}
    </main>
@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/store/css/pages/login_store_form.css') }}">
@endsection

@section('page_js')
    <script> 
        function removeMainImg(){ 
            document.getElementById("main_img").value = "";
        }
        function checkTypes(radioCheck){
            if (radioCheck.checked) {   
                if(radioCheck.id == 'type1'){
                    $('.appears').show();
                }else{
                    $('.appears').hide();
                } 
            }
        }
        $(document).ready(function(){

            $('#main_image_section').change(function(e){  
                document.getElementById("main_img").value = e.target.files[0].name; 
                $("#clear").style.display="block";
            });

            $('#clear3').click(function() {
                $('input[type=file].many').val(''); 
                $('.imagebox').html('');

            $(this).hide();
            });

            $("#agree_privacy").change(function() {
                if ($(this).is(':checked')) {
                    $('#btn_submit').removeClass('disabled');
                    $('#btn_submit').prop( "disabled", false );
                } else {
                    $('#btn_submit').addClass('disabled');
                    $('#btn_submit').prop( "disabled", true );
                }
            });
        }); 

        function setLeapMonth() {
            var year = parseInt($('#birth_year').val());
            var month =  parseInt($('#birth_month').val());
            var leap_year = false;
            if((year % 400==0 || year%100!=0) &&(year%4==0)) {
                leap_year = true;
            }
            var end_day = 31;
            switch (month) {
                case 2:
                    if (leap_year) {
                        end_day = 29;
                    } else {
                        end_day = 28;
                    }
                    break;
                case 4:
                case 6:
                case 9:
                case 11:
                    end_day = 30;
                    break;
            }
            var day_content = '<option value="">--</option>';
            for (let i = 1; i <= end_day; i++) {
                day_content += '<option value="' + i + '">' + i + '日</option>';
            }
            $("#birth_day").empty().html(day_content);
        }
    </script>
@endsection

