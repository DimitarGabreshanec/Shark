@extends('user.layouts.app')

@section('title', 'クレジットカード情報')

@section('header')
    <h1 id="title">クレジットカード情報</h1>
@endsection

@section('content')
    <main>
        <div class="gray-contents">

            @include('user.layouts.flash-message')

            {{ Form::open(["route"=>['user.order.set_order',['order_type' => $order_type]], 'id'=>"frm_set_card",  "method"=>"post"]) }}
            <table class="table customer">
                <tbody>
                <tr>
                    <th>クレジットカード番号</th>
                    <td>{{ $card_info->CardNo }}</td>
                </tr>

                </tbody>
            </table>
            <input type="submit" id="btn_purchase" name="use_exist_card" value="支払う">
            <input type="submit" id="btn_to_update" value="カード変更へ">
            {{ Form::close() }}
        </div>
    </main>

@endsection

@section('page_js')
    <script>
        $(document).ready(function(e) {
            $("#btn_to_update").bind("click", function (e) {
                e.preventDefault();
                location.href = "{{ route('user.order.credit_card_form',['order_type' => $order_type]) }}";
            });
        });
    </script>
@endsection
