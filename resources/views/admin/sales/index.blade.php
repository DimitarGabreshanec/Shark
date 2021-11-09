@extends('admin.layouts.app')

@section('content')
<h2 class="title">振込履歴管理</h2>

<div class="hscroll mt40" id="pane"> 

    <div class="contents_wrap"> 
        <div class="date-list">
            <ul> 
                @php 
                    $minDate = \App\Service\BillService::getMinDateOfBillProducts(); 
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
                $dateArray = \App\Service\BillService::getCurDateArrayOfBillProducts(); 
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
                        <th colspan=2></th> 
                        <th>店舗名</th>
                        <th>店頭売上</th>
                        <th>通販売上</th>
                        <th>売上合計</th>
                        <th>店頭手数料</th>
                        <th>通販手数料</th> 
                        <th>手数料合計</th> 
                        <th>合計</th>  
                    </tr>
                    </thead>
                    <tbody> 
                        @foreach ($bills as $bill)
                        <tr> 
                            <td id="row_header" class="w100">
                                <p class="detail"><a href="{{ route("admin.sales.detail", ["store_id"=>$bill->store_id]) }}">詳細</a></p> 
                            </td>
                            <td id="row_header" class="w100">
                                @if ($bill->status == config('const.bill_product_type.transfer_applied'))
                                    <p id="deposite_ready">振込申請</p>
                                @else
                                    <p id="complete">振込済</p>
                                @endif
                                
                            </td>
                            <td>
                                @php
                                    $store_name = \App\Service\StoreService::getStoreName($bill->store_id);
                                @endphp
                                <p>{{ $store_name }}</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill->fix_price) }}円</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill->ec_price) }}円</p>
                            </td>
                            <td>
                                <p id="total_price">{{ number_format($bill->fix_price + $bill->ec_price) }}円</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill->fix_fee) }}円</p>
                            </td>
                            <td>
                                <p>{{ number_format($bill->ec_fee) }}円</p>
                            </td>
                            <td>
                                <p id="total_price">{{ number_format($bill->fix_fee + $bill->ec_fee) }}円</p>
                            </td>
                            <td>
                                <p id="sum_price">{{ number_format($bill->fix_price + $bill->ec_price - $bill->fix_fee - $bill->ec_fee) }}円</p>
                            </td>
                        </tr>
                        @endforeach 
                        @if($bills->count() == 0)
                            <tr>
                                <td class="text-center" colspan="10">データが存在しません。</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>  
        </div>
    </div>
</div>

@endsection


@section('page_css')
<link rel="stylesheet" href="{{ asset('assets/admin/css/pages/total_sales.css') }}">
@endsection

@section('page_js')
<script>
    function doSearch(year, month){ 
        $.ajax({ 
            type: "get",
            url : '{{ route("admin.sales.search_index_sales") }}',
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


