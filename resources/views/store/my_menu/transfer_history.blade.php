@extends('store.layouts.app')

@section('title', '振込履歴')

@section('header')
    <h1 id="title">振込履歴</h1>
@endsection

@section('content')
    <main>
    <div id="wrapper2">
        <div class="date-list">  
            <ul>
              @php 
                $minDate = \App\Service\BillService::getMinDateOfTransferOrderProduct($store->id); 
                $min_year = Carbon::parse($minDate)->format('Y');
                $min_month = Carbon::parse($minDate)->format('m');
                $now_year = Carbon::now()->format('Y'); 
                $now_month = Carbon::now()->format('m');  
              @endphp   
              <li>
                <button type="button" style="display:{{ ($index_params['year']==$min_year && $index_params['month']==$min_month) || ($index_params['year']==$now_year && $index_params['month'] == null) ? 'none' : '' }}" onclick="onMinusDate();">＜</button>
              </li> 
              <li>
                @php
                      $dateArray = \App\Service\BillService::getDataArrayTransferHistory($store->id);  
                @endphp 
                <select name="date_id" id="date_id" onchange="onSelectDate();"> 
                    <option value="all">すべての月</option>
                    @foreach ($dateArray as $key => $value) 
                    @php
                        $select_index = 0;
                        if(Carbon::parse($value)->isoFormat('Y') == $index_params['year'] &&
                            Carbon::parse($value)->isoFormat('M') == $index_params['month']){
                            $select_index = $key + 1;
                        }
                    @endphp
                    <option value="{{ Carbon::parse($value)->format('Ym') }}"  {{ old('date_id', $select_index)  == $key + 1 ?  'selected' : '' }} >{{ Carbon::parse($value)->format('Y年m月') }}</option>
                    @endforeach 
                </select>   
              </li> 
              <li>
                <button type="button" style="display:{{ ($index_params['year']==$now_year && $index_params['month']==$now_month) || (($index_params['year']==$now_year && $index_params['month'] == null)) ? 'none' : '' }}" onclick="onPlusDate();">＞</button>
              </li>
            </ul> 
       </div>
		<div class="table-scroll">
			 <table>
            <tbody>
                <tr>
                <th rowspan="2">振込状況</th>
                <th colspan="3">取引件数</th>
                <th colspan="3">販売金額</th>
                <th colspan="3">販売手数料</th>
                <th rowspan="2">売上金額</th>
                <th colspan="3" style="display:none;">振込金額</th>
                <th rowspan="2">振込日</th>
                <th rowspan="2" style="display:none;">繰越残高</th>
                </tr>
                <tr>
                <th>合計</th>
                <th>店頭</th>
                <th>通販</th>
                <th>合計</th>
                <th>店頭</th>
                <th>通販</th>
                <th>合計</th>
                <th>店頭</th>
                <th>通販</th>
                <th style="display:none;">合計</th>
                <th style="display:none;">売上金額</th>
                <th style="display:none;">振込手数料</th>
                </tr>
                <tr>
                <td>振込済み</td>
                <td>{{ isset($index_params['transactions_total']) && ($index_params['transactions_total'] > 0) ? $index_params['transactions_total'] : '' }}</td>
                <td>{{ isset($index_params['transactions_fix']) && ($index_params['transactions_fix'] > 0) ? $index_params['transactions_fix'] : '' }}</td>
                <td>{{ isset($index_params['transactions_ec']) && ($index_params['transactions_ec'] > 0)  ? $index_params['transactions_ec'] : '' }}</td>
                <td>{{ isset($index_params['price_add']) && ($index_params['price_add'] > 0) ? '￥'.number_format($index_params['price_add']) : '' }}</td>
                <td>{{ isset($index_params['fix_price']) && ($index_params['fix_price'] > 0) ? number_format($index_params['fix_price']) : '' }}</td>
                <td>{{ isset($index_params['ec_price']) && ($index_params['ec_price'] > 0) ? number_format($index_params['ec_price']) : '' }}</td>
                <td>{{ isset($index_params['fee_add']) && ($index_params['fee_add'] > 0) ? '￥'.number_format($index_params['fee_add']) : '' }}</td> 
                <td>{{ isset($index_params['fix_fee']) && ($index_params['fix_fee'] > 0) ? number_format($index_params['fix_fee']) : '' }}</td> 
                <td>{{ isset($index_params['ec_fee']) && ($index_params['ec_fee'] > 0) ? number_format($index_params['ec_fee']) : '' }}</td> 
                <td>{{ isset($index_params['total_price']) && ($index_params['total_price'] > 0) ? '￥'.number_format($index_params['total_price']) : '' }}</td>  
                <td style="display:none;">2339</td>
                <td style="display:none;">2000</td>
                <td style="display:none;">330</td>
                <td>{{ isset($index_params['completed_at']) ? $index_params['completed_at'] : '' }}</td>
                <td style="display:none;">985</td>
                </tr>
                <tr>
                <td>入金依頼なし</td>
                <td>{{ isset($index_params['transactions_total_able']) && ($index_params['transactions_total_able'] > 0) ? ($index_params['transactions_total_able']) : '' }}</td>  
                <td>{{ isset($index_params['transactions_fix_able']) && ($index_params['transactions_fix_able'] > 0) ? ($index_params['transactions_fix_able']) : '' }}</td>  
                <td>{{ isset($index_params['transactions_ec_able']) && ($index_params['transactions_ec_able'] > 0) ? ($index_params['transactions_ec_able']) : '' }}</td>  
                <td>{{ isset($index_params['price_add_able']) && ($index_params['price_add_able'] > 0) ? '￥'.number_format($index_params['price_add_able']) : '' }}</td>  
                <td>{{ isset($index_params['fix_price_able']) && ($index_params['fix_price_able'] > 0) ? number_format($index_params['fix_price_able']) : '' }}</td>  
                <td>{{ isset($index_params['ec_price_able']) && ($index_params['ec_price_able'] > 0) ? number_format($index_params['ec_price_able']) : '' }}</td>  
                <td>{{ isset($index_params['fee_add_able']) && ($index_params['fee_add_able'] > 0) ? '￥'.number_format($index_params['fee_add_able']) : '' }}</td>  
                <td>{{ isset($index_params['fix_fee_able']) && ($index_params['fix_fee_able'] > 0) ? number_format($index_params['fix_fee_able']) : '' }}</td>  
                <td>{{ isset($index_params['ec_fee_able']) && ($index_params['ec_fee_able'] > 0) ? number_format($index_params['ec_fee_able']) : '' }}</td>  
                <td>{{ isset($index_params['total_price_able']) && ($index_params['total_price_able'] > 0) ? '￥'.number_format($index_params['total_price_able']) : '' }}</td>  
                <td></td>
                </tr>
            </tbody>
            </table> 
	  </div>

    </div>
    </main>

@endsection


@section('page_css') 
<link rel="stylesheet" href="{{ asset('assets/store/css/pages/my_menu_transerHistory.css') }}">
@endsection

@section('page_js')
<script>
    function doSearch(year, month){
        $.ajax({ 
            type: "get",
            url : '{{ route("store.my_menu.select_transfer_history") }}',
            data: {
                    _token: '{{ csrf_token() }}',
                    year: year,
                    month: month,
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


