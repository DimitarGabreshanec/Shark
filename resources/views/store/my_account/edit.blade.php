@extends('store.layouts.app')

@section('title', '店舗情報')

@section('header')
    <h1 id="title">店舗情報の編集</h1>
@endsection

@section('content')
    {{ Form::open(["route"=>["store.my_account.update"], "method"=>"put", 'files'=>true]) }}
    <main>
        <div id="wrapper2">
            <table class="table">
                <tbody >
                    <tr>
                        <th>掲載カテゴリ</th>
                        <td>
                            <ul class="acc">
                                @include('store.my_account._category_list', ['parent_id' => 0, 'disabled' => ''])
                            </ul>
                            @error('category')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>店舗名</th>
                        <td>
                            <input type="text" name="store_name" id="store_name"
                            value="{{ old('store_name', isset($store) && isset($store->store_name) ? $store->store_name : '') }}">
                            @error('store_name')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>営業時間</th>
                        <td>
                            <div class="work_time">
                            @php
                                $work_from_hour = old('work_from_hour',  isset($store) && isset($store->work_from) ? Carbon::parse($store->work_from)->isoFormat('H') : '' );
                            @endphp
                            <select name="work_from_hour" id="work_from_hour" class="work_time">
                                <option value="">--</option>
                                @for ($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}" {{ $work_from_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                            </div>
                            <div class="work_one">
                            時
                            </div>
                            <div class="work_time">
                                <select name="work_from_minute" id="work_from_minute" class="work_time">
                                    @php
                                        $work_from_minute = old('work_from_minute',  isset($store) && isset($store->work_from) ? Carbon::parse($store->work_from)->isoFormat('m') : '' );
                                    @endphp
                                    <option value="">--</option>
                                    @for ($i = 0; $i < 60; $i++)
                                        <option value="{{$i}}" {{ $work_from_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="work_one">
                            分
                            </div>

                            <div class="work_one"> &nbsp;&nbsp;~</div>&nbsp;&nbsp;&nbsp;

                            <div class="work_time">
                                @php
                                    $work_to_hour = old('work_to_hour',  isset($store) && isset($store->work_to) ? Carbon::parse($store->work_to)->isoFormat('H') : '' );
                                @endphp
                                <select name="work_to_hour" id="work_to_hour" class="work_time">

                                    <option value="">--</option>
                                    @for ($i = 0; $i < 24; $i++)
                                        <option value="{{$i}}" {{ $work_to_hour === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="work_one">
                            時
                            </div>
                            <div class="work_time">
                                <select name="work_to_minute" id="work_to_minute" class="work_time">
                                    @php
                                        $work_to_minute = old('work_to_minute',  isset($store) && isset($store->work_to) ? Carbon::parse($store->work_to)->isoFormat('m') : '' );
                                    @endphp
                                    <option value="">--</option>
                                    @for ($i = 0; $i < 60; $i++)
                                        <option value="{{$i}}" {{ $work_to_minute === (string)$i ? "selected" : "" }}>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="work_one">
                            分
                            </div>
                            <input type="hidden" name="work_from" value="{{ isset($store) && isset($store->work_from) ? $store->work_from : ''  }}">
                            <div>
                                @error('work_from')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <input type="hidden" name="work_to" value="{{ isset($store) && isset($store->work_to) ? $store->work_to : ''  }}">
                            <div>
                                @error('work_to')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th>お店へのアクセス</th>
                        <td>
                            <textarea name="address_access" id="address_access" class="input-text"> 
                            {{ old('address_access', isset($store) && isset($store->address_access) ? $store->address_access : '') }} </textarea>
                            @error('address_access')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>電話番号</th>
                        <td>
                            <input type="text" name="tel" id="tel"
                            value="{{ old('tel', isset($store) && isset($store->tel) ? $store->tel : '') }}">
                            @error('tel')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>住所</th>
                        <td>
                            <select class="prefecture_value" name="prefecture" id="prefecture">
                                <option value="">--</option>
                                @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
                                    <option value="{{ $key }}"  {{ old('prefecture', isset($store)? $store->prefecture : '')  == $key ?  'selected' : '' }} >{{ $value }}</option>
                                @endforeach
                            </select>
                            @error('prefecture')
                                <p class="error">{{ $message }}</p>
                            @enderror
                            <input type="text" name="store_address" id="store_address" value="{{ old('store_address', isset($store) && isset($store->store_address) ? $store->store_address : '') }}">
                            @error('store_address')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>URL</th>
                        <td>
                            <input type="text" maxlength="512" name="url" id="url"
                            value="{{ Str::limit(old('url', isset($store) && isset($store->url) ? $store->url : ''), 100, '...') }}">
                            @error('url')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>店舗紹介文</th>
                        <td>
                        <textarea name="detail" id="detail" class="input-text"> {{ old('detail', isset($store) && isset($store->detail) ? $store->detail : '') }}</textarea>
                        @error('detail')
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
                                @if(is_object($store->obj_main_img) && Storage::disk('stores')->has("{$store->id}/{$store->obj_main_img->img_name}"))
                                <li class="li_{{ $store->obj_main_img->sequence }}">
                                    <img class="w250" src="{{ asset("storage/stores/{$store->id}/{$store->obj_main_img->img_name}") }}">
                                    <input type="button" id="clear3" data-image_name="{{ $store->obj_main_img->img_name }}" data-sequence_="{{ $store->obj_main_img->img_name }}" value="ファイルを削除する" style="display: block !important">
                                </li>
                                <input type="hidden" id="main_img_name" name="main_img_name" value="{{ $store->obj_main_img->img_name }}">
                                @endif
                            </div>
                            @error('main_img_name')
                                <p class="error">{{ $message }}</p>  
                            @enderror
                        </td>
                    </tr>
                    <tr>
                        <th>サブ画像(複数選択可)</th>
                        <td >
                            <button id="fileforbutton1" onclick="document.getElementById('sub_img').click(); return false;">ファイル選択</button>
                            <input type="file" id="sub_img" name="sub_img[]" accept="image/*"  multiple class="many">
                            <div class="imagebox_sub">
                                @if($store->obj_sub_img->count() > 0)
                                <ul id="sub_old_img">
                                    @foreach($store->obj_sub_img as $obj_sub_img_one)
                                    @if(is_object($obj_sub_img_one) && Storage::disk('stores')->has("{$store->id}/{$obj_sub_img_one->img_name}"))
                                        <li class="li_{{ $obj_sub_img_one->sequence }}">
                                            <img class="w250" src="{{ asset("storage/stores/{$store->id}/{$obj_sub_img_one->img_name}") }}">
                                            <input type="button" id="clear3" data-image_name="{{ $obj_sub_img_one->img_name }}" data-sequence_="{{ $obj_sub_img_one->sequence }}" class="clear_sub" value="ファイルを削除する">
                                        </li>
                                    @endif
                                    @endforeach
                                </ul>
                                @endif
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input type="submit" name="btn_save" value="保存する">
        </div>
    </main>
    {{ Form::close() }}

@endsection



@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/store/css/pages/my_account_edit.css') }}">
@endsection



@section('page_js')

<script>

    $(document).ready(function(){

        $(".check-parent").change(function () { 
            var parent_id = $(this).val();  
            $("input.node-parent-" + parent_id + ":checkbox").prop('checked', $(this).prop("checked"));  
            $("input.node-parent-" + parent_id + ":checkbox").change();  

            var parent_id = $(this).data('parent'); 
            if($("input.node-parent-" + parent_id + ":checked").length == $("input.node-parent-" + parent_id).length){
                $("input#category_" + parent_id + ":checkbox").prop('checked',true);   
                selChanged($("input#category_" + parent_id + ":checkbox"));
            }else{
                $("input#category_" + parent_id + ":checkbox").prop('checked',false);
                selChanged($("input#category_" + parent_id + ":checkbox"));
            }   
        });

        function selChanged(category){
            var parent_id = category.data('parent');  
            if($("input.node-parent-" + parent_id + ":checked").length == $("input.node-parent-" + parent_id).length){
                $("input#category_" + parent_id + ":checkbox").prop('checked',true);   
            }else{
                $("input#category_" + parent_id + ":checkbox").prop('checked',false);
            } 
        }  

        $("#sub_img").on('change', function(e){
            var tgt = e.target || window.event.srcElement,
                files = tgt.files;
            var form_data = new FormData();
            var totalfiles = files.length;
            for (var index = 0; index < totalfiles; index++) {
                form_data.append("sub_img[]", files[index]);
            }
            form_data.append("_token", "{{csrf_token()}}");
            $.ajax({
                type: "post",
                url : '{{ route("store.my_account.add_img") }}',
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
            $.ajax({
                type: "post",
                url : '{{ route("store.my_account.add_img") }}',
                data:  form_data,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(result) {
                    location.reload();
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
                    url : '{{ route("store.my_account.delete_img") }}',
                    data: {
                        image_name: image_name,
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


    });


</script>
@endsection

