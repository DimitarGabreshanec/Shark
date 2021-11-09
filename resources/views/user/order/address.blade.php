@extends('user.layouts.app')

@section('title', 'お客様情報')

@section('header')
    <h1 id="title">お客様情報</h1>
@endsection

@section('content')
<main>
    <div class="gray-contents">
        {{ Form::open(["route"=>['user.order.set_address', ['order_type'=>$order_type]], "method"=>"post"]) }}
        <section>
            <table class="table customer">
                <tr id="name_row" class="inp1">
                    <th scope="row">お名前</th>
                    <td class="name_td"><ul class="two">
                            <li>
                                <input name="address[last_name]" id="last_name" type="text" value="{{ old('address.last_name', isset($address) ? $address->last_name : '') }}" style="ime-mode: active" placeholder="氏">
                            </li>
                            <li>
                                <input name="address[first_name]" id="first_name" type="text" value="{{ old('address.first_name', isset($address) ? $address->first_name : '') }}" style="ime-mode: active" placeholder="名">
                            </li>
                        </ul>
                        @error('address.last_name')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('address.first_name')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="furikana_row" class="inp1">
                    <th scope="row">フリガナ</th>
                    <td>
                        <ul class="two">
                            <li>
                                <input name="address[last_name_kana]" id="last_name_kana" type="text" value="{{ old('address.last_name_kana', isset($address) ? $address->last_name_kana : '') }}" style="ime-mode: active">
                            </li>
                            <li>
                                <input name="address[first_name_kana]" id="first_name_kana" type="text" value="{{ old('address.first_name_kana', isset($address) ? $address->first_name_kana : '') }}" style="ime-mode: active">
                            </li>
                        </ul>
                        @error('address.last_name_kana')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('address.first_name_kana')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="zipcode_row">
                    <th scope="row">郵便番号</th>
                    <td>
                        <ul class="two">
                            <li>
                                <input name="address[post_first]" id="post_first" type="text" placeholder="(入力例) 123" value="{{ old('address.post_first', isset($address) ? $address->post_first : '') }}" style="ime-mode: active">
                            </li>
                            <li>
                                <input name="address[post_second]" id="post_second" type="text" placeholder="(入力例) 4567" value="{{ old('address.post_second', isset($address) ? $address->post_second : '') }}" style="ime-mode: active">
                            </li>
                        </ul>
                        @error('address.post_first')
                            <p class="error">{{ $message }}</p>
                        @enderror
                        @error('address.post_second')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="states_row">
                    <th scope="row">都道府県</th>
                    <td colspan="2">
                        <select name="address[prefecture]" id="prefecture" class="pref">
                            <option value="">--選択--</option>
                            @foreach(\App\Service\AreaService::getAllPrefecture() as $key=>$value)
                                <option value="{{ $key }}" {{ old('address.prefecture', isset($address) ? $address->prefecture : '')  == $key ?  'selected' : '' }} >{{ $value }}</option>
                            @endforeach
                        </select>
                        @error('address.prefecture')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="address1_row" class="inp2">
                    <th scope="row">市区郡町村</th>
                    <td colspan="2">
                        <input name="address[address1]" id="address1" type="text" value="{{ old('address.address1', isset($address) ? $address->address1 : '') }}" style="ime-mode: active">
                        @error('address.address1')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="address2_row">
                    <th scope="row">番地</th>
                    <td colspan="2">
                        <input name="address[address2]" id="address2" type="text" value="{{ old('address.address2', isset($address) ? $address->address2 : '') }}" style="ime-mode: active">
                        @error('address.address2')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="address3_row">
                    <th scope="row">ビル名</th>
                    <td colspan="2">
                        <input name="address[address3]" id="address3" type="text" value="{{ old('address.address3', isset($address) ? $address->address3 : '') }}" style="ime-mode: active">
                        @error('address.address3')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr>
                <tr id="tel_row">
                    <th scope="row">電話番号</th>
                    <td colspan="2">
                        <input name="address[tel]" id="tel" type="text" value="{{ old('address.tel', isset($address) ? $address->tel : '') }}" style="ime-mode: inactive">
                        @error('address.tel')
                            <p class="error">{{ $message }}</p>
                        @enderror
                    </td>
                </tr> 
            </table>
            <div>
                <input type="checkbox" name="save_address" id="save_address" value="1"><label for="save_address">このお客様情報を保存して、次回も使用する</label>
                <input type="submit" value="決済入力へ">
            </div>
        </section>
        {{ Form::close() }}
    </div>
</main>

@endsection
