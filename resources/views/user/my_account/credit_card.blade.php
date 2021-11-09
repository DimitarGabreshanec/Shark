@extends('user.layouts.app')

@section('title', 'クレジットカードの登録')

@section('header')
    <h1 id="title">クレジットカードの登録</h1>
@endsection 

@section('content')
    <main> 
    <div class="gray-contents">

    @include('user.layouts.flash-message')

    {{ Form::open(["route"=>'user.my_account.credit_card',  "method"=>"get"]) }}
    <p class="mb20">商品やサービス決済用のクレジットカード情報を登録してください。</p>
    <table class="table customer">
        <tbody>
            <tr>
                <th>クレジットカード番号<span>※</span></th>
                <td>
                    <input type="text" id="card_number">&nbsp;
                    {{--<input type="text" class="card-number" id="card_number_2">&nbsp;-
                    <input type="text" class="card-number" id="card_number_3">&nbsp;-
                    <input type="text" class="card-number" id="card_number_4">--}}
                    <!--<p class="error">エラー表示エラー表示エラー表示エラー表示</p-->
                </td>
            </tr>
            <tr>
                <th>有効期限<span>※</span></th>
                <td>
                    <select id="exp_month">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ str_pad($month,2, "0", STR_PAD_LEFT) }}">{{ $month }}月</option>
                        @endforeach
                    </select>
                    <select id="exp_year">
                        @foreach(range(date('Y'), date('Y') +10) as $year)
                            <option value="{{ $year }}">{{ $year }}年</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <th>セキュリティコード<span>※</span></th>
                <td>
                    <input type="text" id="cvv">
                    <!--<p class="error">エラー表示エラー表示エラー表示エラー表示</p--></td>
                </tr>
        </tbody>
    </table>
    <input type="submit" id="btn_save" value="保存する">
    <input type="hidden" name="card_token" id="card_token">
    {{ Form::close() }}
    </div>
    </main>

@endsection

@section('page_css')

@endsection
  