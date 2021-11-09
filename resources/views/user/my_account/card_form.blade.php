@extends('user.layouts.app')

@section('title', 'クレジットカードの登録')

@section('header')
    <h1 id="title">クレジットカードの登録</h1>
@endsection
@section('content')
    <main>
        <div class="gray-contents">

            @include('user.layouts.flash-message')

            {{ Form::open(["route"=>'user.my_account.set_card',  "method"=>"post", 'id'=>'frm_card']) }}
            <p class="mb20">商品やサービス決済用のクレジットカード情報を登録してください。</p>
            <table class="table customer">
                <tbody>
                <tr>
                    <th>クレジットカード番号<span>※</span></th>
                    <td>
                        <input type="text" class="card-number" id="card_number1">&nbsp;-
                        <input type="text" class="card-number" id="card_number2">&nbsp;-
                        <input type="text" class="card-number" id="card_number3">&nbsp;-
                        <input type="text" class="card-number" id="card_number4">
                        <p class="error hide"></p>
                    </td>
                </tr>
                <tr>
                    <th>有効期限<span>※</span></th>
                    <td>
                        <select id="exp_month">
                            <option value="">--</option>
                            @foreach(range(1, 12) as $month)
                                <option value="{{ str_pad($month,2, "0", STR_PAD_LEFT) }}">{{ $month }}月</option>
                            @endforeach
                        </select>
                        <select id="exp_year">
                            <option value="">--</option>
                            @foreach(range(date('Y'), date('Y') +10) as $year)
                                <option value="{{ $year }}">{{ $year }}年</option>
                            @endforeach
                        </select>
                        <p class="error hide"></p>
                    </td>
                </tr>
                <tr>
                    <th>セキュリティコード</th>
                    <td>
                        <input type="text" id="cvv">
                        <p class="error hide"></p>
                    </td>
                </tr>
                </tbody>
            </table>
            <input type="submit" id="btn_save" value="保存する">
            <input type="hidden" name="card_token" id="card_token">
            {{ Form::close() }}
        </div>
    </main>

@endsection

@section('page_js')
    <script src="{{ config('payment.gmo_js_url') }}"></script>
    <script>
        $(document).ready(function(e) {
            $("#btn_save").bind("click", function (e) {
                e.preventDefault();
                getToken();
            });
        });

        var Multipayment, CryptoJS, JSEncryptExports, JSEncrypt, ASN1, Base64, Hex, KJUR;
        function parseTokenResponse(response) {
            var tokenError = {!! json_encode(config('payment.token_error_msgs'), false)  !!};
            if (response.resultCode == "{{ config('payment.token_success_code') }}") {
                $("#card_token").val(response.tokenObject.token);
                $("#frm_card").submit();
            } else if (response.resultCode in tokenError) {
                alert(tokenError[response.resultCode]);
            } else {
                alert('エラー発生しました。');
            }
        }

        function getToken() {
            var cardnum1 = document.getElementById("card_number1").value;
            var cardnum2 = document.getElementById("card_number2").value;
            var cardnum3 = document.getElementById("card_number3").value;
            var cardnum4 = document.getElementById("card_number4").value;
            var cardno = cardnum1 + cardnum2 + cardnum3 + cardnum4;
            var expire = document.getElementById("exp_year").value + document.getElementById("exp_month").value;
            var securitycode = document.getElementById("cvv").value;
            var tokennumber = 1;
            Multipayment.init("{{ config('payment.gmo_shop_id') }}");
            Multipayment.getToken({
                cardno: cardno,
                expire: expire,
                securitycode: securitycode,
                holdername: '',
                tokennumber: tokennumber
            }, parseTokenResponse);
        }
    </script>
@endsection

@section('page_css')
    <style>
        input.card-number {
            width: 100px;
        }
    </style>
@endsection

