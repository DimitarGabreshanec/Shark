@extends('store.layouts.app')

@section('title', '売上集計')

@section('header')
    <h1 id="title">売上集計</h1>
@endsection

@section('content')  
    <main> 
    <div id="wrapper2"> 
      <div class="date-list">
        @include('store.layouts.flash-message')
        <ul>
          @php 
            $minDate = \App\Service\BillService::getMinDateOfOrderProduct($store->id); 
            $min_year = Carbon::parse($minDate)->format('Y');
            $min_month = Carbon::parse($minDate)->format('m');
            $now_year = Carbon::now()->format('Y'); 
            $now_month = Carbon::now()->format('m');  
          @endphp
          <li>
          <button type="button" style="display:{{ $index_params['year']==$min_year && $index_params['month']==$min_month ? 'none' : '' }}" onclick="onMinusDate();">＜</button>
          </li>  
          <li>
            @php
                  $dateArray = \App\Service\BillService::getCurDateArrayOfOrderProduct($store->id); 
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
          <li>
            <button type="button" style="display:{{ $index_params['year']==$now_year && $index_params['month']==$now_month ? 'none' : '' }}" onclick="onPlusDate();">＞</button>
          </li>
        </ul>
      </div>
      <table class="table2 total-detail">
        <thead>
          <tr>
            <th class="sale_head"></th>
            <th class="sale_head">店頭商品 </th>
            <th class="sale_head">通販商品</th>
            <th class="sale_head">合計 </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th>売上 </th>
            @php
              $total_fix_price = isset($index_params['fix_price']) ? ($index_params['fix_price']) : 0;
              $total_ec_price = isset($index_params['ec_price']) ? ($index_params['ec_price']) : 0;
            @endphp
            <td>{{ $total_fix_price > 0 ? number_format($total_fix_price).'円' : ''}} </td>
            <td>{{ $total_ec_price > 0 ? number_format($total_ec_price).'円' : ''}} </td>
            <td class="total">{{ ($total_fix_price + $total_ec_price) > 0 ? number_format($total_fix_price + $total_ec_price).'円'  : ''}}</td>
 
          </tr>
          <tr>
            <th>手数料</th>
            @php
            $total_fix_tax_price = isset($index_params['fix_fee']) ? ($index_params['fix_fee']) : 0;
            $total_ec_tax_price = isset($index_params['ec_fee']) ? ($index_params['ec_fee']) : 0;
            $index_params['total_price'] = $total_fix_price + $total_ec_price - $total_fix_tax_price - $total_ec_tax_price;
            @endphp
            <td>{{ $total_fix_tax_price > 0 ? number_format($total_fix_tax_price).'円' : '' }} </td>
            <td>{{ $total_ec_tax_price > 0 ? number_format($total_ec_tax_price).'円' : '' }} </td>
            <td class="total">{{ ($total_fix_tax_price + $total_ec_tax_price) > 0 ? number_format($total_fix_tax_price + $total_ec_tax_price).'円' : '' }} </td>
          </tr>
          <tr>
            <th>振込金額 </th>
            <td>{{ $total_fix_price - $total_fix_tax_price > 0 ? number_format($total_fix_price - $total_fix_tax_price).'円' : '' }} </td>
            <td>{{ $total_ec_price - $total_ec_tax_price > 0 ? number_format($total_ec_price - $total_ec_tax_price).'円' : '' }} </td>
            <td class="total">{{ $index_params['total_price'] > 0 ? number_format($index_params['total_price']).'円' : '' }} </td>
          </tr>
        </tbody>
        </table> 
        @if(isset($index_params['deposite_enable']) && $index_params['total_price'] !== 0)
        {{ Form::open(["route"=>"store.bill.order_deposite", "method"=>"POSt"]) }}  
            <input type="hidden" name="year" value="{{ $index_params['year'] }}">
            <input type="hidden" name="month" value="{{ $index_params['month'] }}">
            <input type="hidden" name="total_price" value="{{ $index_params['total_price'] }}">
            <input type="submit" id="deposite" value="入金依頼をする">
        {{ Form::close() }}
        @endisset
    </div>
    </main>
    
@endsection


@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/store/css/pages/total_sales.css') }}">
@endsection

@section('page_js')
<script>
  function doSearch(year, month){
    $.ajax({ 
          type: "get",
          url : '{{ route("store.bill.select_sale") }}',
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


