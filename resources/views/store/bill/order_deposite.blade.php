@extends('store.layouts.app')

@section('title', '入金依頼')

@section('header')
    <h1 id="title">入金依頼</h1>
@endsection

@section('content') 

    <main> 
        <div id="wrapper2">
            <table class="table">
                <tbody>
                    <tr> 
                        <th>入金可能額</th>
                        <td><input type="text" value="{{ isset($index_params['total_price']) ? number_format($index_params['total_price']).'円' : '0円'}}"><p class="red">※入金可能額は振込手数料を差し引いた金額が表示されます。</p></td>
                    </tr>
                    <tr>
                        <th>入金希望額</th>
                        <td><input type="text" readonly value="{{ isset($index_params['total_price']) ? number_format($index_params['total_price']).'円' : '0円'}}"><p class="red">※入金は最短で3営業日後になります。</p></td>
                    </tr>
                </tbody></table>
                {{ Form::open(['route' => ['store.bill.order_deposite_confirm', 'params' => $index_params], 'method' => 'POSt']) }}
                <input type="submit" id="deposite" value="入金希望額を確定する">
                {{ Form::close()}}
             </div>
    </main>

@endsection


@section('page_css')
     
@endsection

@section('page_js')
<script>
$(document).ready(function(e) {
    $('#deposite').click(function(){  
        var f_confirm = confirm('入金依頼を確定します。\nよろしいでしょうか?');
        if(f_confirm){  
            $('#deposite').submit(); 
            return true;
        }
        return false;
    });
});
</script>
@endsection


