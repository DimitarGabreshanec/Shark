@extends('admin.layouts.app')

@section('content')
<h2 class="title">振込履歴ー{{ $store_name }}</h2>

<div class="hscroll mt40" id="pane">
    @include('admin.layouts.flash-message')

    <div class="contents_wrap">
        <div class="date-list">
            <ul> 
                @php 
                    $minDate = \App\Service\BillService::getMinDateOfBillProduct($store_id); 
                    $min_year = Carbon::parse($minDate)->format('Y');
                    $min_month = Carbon::parse($minDate)->format('m');
                    $now_year = Carbon::now()->format('Y'); 
                    $now_month = Carbon::now()->format('m');  
                @endphp
                <li class="li_sale"> 
                    <a id="prev_sale" style="display:{{ $index_params['year']==$min_year && $index_params['month']==$min_month ? 'none' : '' }}" onclick="onMinusDate();">‹</a>
                </li>
                <li>
                @php
                $dateArray = \App\Service\BillService::getCurDateArrayOfBillProduct($store_id); 
                @endphp  
                <select name="date_id" id="date_id" onchange="onSelectDate();">  
                    @foreach ($dateArray as $key => $value) 
                    @php
                        $select_index = 0;
                        if(Carbon::parse($value)->isoFormat('Y') == $index_params['year'] &&
                            Carbon::parse($value)->isoFormat('M') == $index_params['month']){
                            $select_index = $key;
                        }
                    @endphp
                    <option value="{{ Carbon::parse($value)->format('Ym') }}"  {{ old('date_id', $select_index)  == $key ?  'selected' : '' }} >{{ Carbon::parse($value)->format('Y年m月') }}</option>
                @endforeach 
                </select>   
                </li>
                <li class="li_sale">
                <a id="prev_sale" style="display:{{ $index_params['year']==$now_year && $index_params['month']==$now_month ? 'none' : '' }}" onclick="onPlusDate();">›</a>
                </li>
            </ul>
          </div>
        <div class="table_wrap"> 
            <div class="table_inner">
                <table class="normal type3">
                    <thead>
                    <tr>
                        <th></th>
                        <th>店頭商品</th>
                        <th>通販商品</th>
                        <th class="sale_head">合計</th> 
                    </tr>
                    </thead>
                    <tbody> 
                    @isset($bill_product)
                        <tr>
                            <td id="row_header">
                                <p>売上</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill_product->fix_price) }}円</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill_product->ec_price) }}円</p>
                            </td>
                            <td>
                                <p id="total_price">{{ number_format($bill_product->fix_price + $bill_product->ec_price) }}円</p>
                            </td> 
                        </tr>

                        <tr>
                            <td id="row_header">
                                <p>手数料</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill_product->fix_fee) }}円</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill_product->ec_fee) }}円</p>
                            </td>
                            <td>
                                <p id="total_price">{{ number_format($bill_product->fix_fee + $bill_product->ec_fee) }}円</p>
                            </td>
                        </tr>

                        <tr>
                            <td id="row_header">
                                <p>振込金額</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill_product->fix_price - $bill_product->fix_fee) }}円</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill_product->ec_price - $bill_product->ec_fee) }}円</p>
                            </td>
                            <td>
                                <p id="total_price">{{ number_format($bill_product->fix_price + $bill_product->ec_price - $bill_product->fix_fee - $bill_product->ec_fee) }}円</p>
                            </td>
                        </tr> 
                     @else 
                     <tr>
                        <td class="text-center" colspan="4">データが存在しません。</td> 
                     </tr>
                     @endisset
                    </tbody>
                </table>
            </div>  
            <div class="center-submit double center">
                @if(isset($bill_product) && $bill_product->status == config('const.bill_product_type.transfer_applied'))
                    <input type="submit" id="deposite" value="振込完了" onclick="onDeposite();">
                @endif  
                <input type="button" class="btn-back-index" data-url="{{ route("admin.sales.index") }}" value="戻る">
            </div>
        </div>
    </div>
</div>

@endsection


@section('page_css')
<link rel="stylesheet" href="{{ asset('assets/admin/css/pages/sale_detail.css') }}">
@endsection

@section('page_js')
<script>
    function onDeposite(){ 
        helper_confirm("dialog-confirm", "情報", "振込完了をしますか？<br>よろしいですか？", 250, "確認", "閉じる", function(){
            location.href='{{ route('admin.sales.completed', ['bill_product' => isset($bill_product) ? $bill_product->id : 0]) }}'; 
        });
    }
    function doSearch(year, month){
        var store_id = {{ $store_id }};
        $.ajax({ 
            type: "get",
            url : '{{ route("admin.sales.select_date_sale") }}',
            data: {
                  _token: '{{ csrf_token() }}',
                  year: year,
                  month: month,
                  store_id: store_id,
              },
            dataType: "json", 
            success: function(result) { 
                location.reload();
                return true;
            },
            error: function(){
                alert('検索をされた結果はありません。');
            },
        });  
    }
    function onSelectDate(){ 
        var selectDate = document.getElementById('date_id').value;  
        var year = selectDate.substring(0, 4);
        var month = selectDate.substring(4, 6);   
        doSearch(year, month);
       
      }
  
      function onMinusDate(){
        var selectDate = document.getElementById('date_id').value;  
        var year = selectDate.substring(0, 4);
        var month = selectDate.substring(4, 6);  

        if(month == 1){
          year--;
          month = 12;
        } else{
          month--;
        }   
        doSearch(year, month); 
      }
  
      function onPlusDate(){
        var selectDate = document.getElementById('date_id').value;  
        var year = selectDate.substring(0, 4);
        var month = selectDate.substring(4, 6);  

        if(month == 12){
          year++;
          month = 1;
        } else{
          month++;
        }   
        doSearch(year, month);
      }
  
  </script>
@endsection


