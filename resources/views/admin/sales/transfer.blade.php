@extends('admin.layouts.app')

@section('content')
<h2 class="title">振込履歴状況</h2>

<div class="hscroll mt40" id="pane"> 

    <div class="contents_wrap"> 
        <div class="date-list">
            <ul> 
                @php 
                    $minDate = \App\Service\BillService::getMinDateOfTransferProducts(); 
                    $min_year = Carbon::parse($minDate)->format('Y');
                    $min_month = Carbon::parse($minDate)->format('m');
                    $now_year = Carbon::now()->format('Y'); 
                    $now_month = Carbon::now()->format('m');  
                @endphp
                <li class="li_sale">  
                    <a id="prev_sale" style="display:{{ ($index_params['year']==$min_year && $index_params['month']==$min_month) || ($index_params['year']==$now_year && $index_params['month'] == null) ? 'none' : '' }}" onclick="onMinusDate();">‹</a>
                </li>
                <li>
                @php
                $dateArray = \App\Service\BillService::getDataArrayTransfer(); 
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
                <li class="li_sale">
                    <a id="prev_sale" style="display:{{ ($index_params['year']==$now_year && $index_params['month']==$now_month) || (($index_params['year']==$now_year && $index_params['month'] == null)) ? 'none' : '' }}" onclick="onPlusDate();">›</a>
                </li>
            </ul>
        </div>
        <div class="table_wrap"> 
            <div class="table_inner">
                <table class="normal type3">
                    <thead>
                    <tr> 
                        <th rowspan="2">店舗名</th>  
                        <th rowspan="2">振込状況</th>
                        <th colspan="3">取引件数</th>
                        <th colspan="3">販売金額</th>
                        <th colspan="3">販売手数料</th>
                        <th rowspan="2">売上金額</th>
                        <th rowspan="2">振込金額</th>
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
                    </thead>
                    <tbody>  
                        @foreach ($stores as $store_id)   
                        @php 
                        $data = $index_params; 
                        $fix_order_products = 0;
                        $ec_order_products = 0;
                        $fix_price = 0;
                        $ec_price = 0;
                        $fix_fee = 0;
                        $ec_fee = 0;
                        $total_price = 0; 
                        $store_name = isset($store_id) ? \App\Service\StoreService::getStoreName($store_id) : '';
                        $deposited_products = \App\Service\BillService::getBillProductByStatus($store_id, $index_params['year'], $index_params['month'], config('const.bill_product_type.transfer_completed'));
                        foreach($deposited_products as $deposited_product){
                            if(!empty($deposited_product) && isset($deposited_product)){
                                $fix_order_products += sizeof(json_decode($deposited_product->fix_order_products));
                                $data['fix_order_products'] = json_decode($deposited_product->fix_order_products); 
                                $ec_order_products += sizeof(json_decode($deposited_product->ec_order_products)); 
                                $data['ec_order_products'] = json_decode($deposited_product->ec_order_products);
                                $fix_price += $deposited_product->fix_price;
                                $ec_price += $deposited_product->ec_price;
                                $fix_fee += $deposited_product->fix_fee;
                                $ec_fee += $deposited_product->ec_fee;
                                $total_price += $deposited_product->total_price;
                                $completed_at = Carbon::parse($deposited_product->completed_at)->format('Y/m/d');
                            } 
                        }  
                        if($index_params['month'] == null){
                            $completed_at = null;
                        }
                        $depositable_product = \App\Service\BillService::doGetStoreNoProductsInDate(config('const.order_type_code.fix'), $store_id, $data);
                        if(!empty($depositable_product) && isset($depositable_product)){
                            
                            $fix_price_able = \App\Service\BillService::getTotalPrice($depositable_product);
                            $fix_fee_able = \App\Service\BillService::getTotalTaxPrice($depositable_product);
                            $transactions_fix_able = sizeof(json_decode(\App\Service\BillService::getOrderproducts($depositable_product))); 
                            $total_price_able = $fix_price_able - $fix_fee_able;
                        }
                        
                        $depositable_product = \App\Service\BillService::doGetStoreNoProductsInDate(config('const.order_type_code.ec'), $store_id, $data);
                        if (!empty($depositable_product) && isset($depositable_product)) {
                            $ec_price_able = \App\Service\BillService::getTotalPrice($depositable_product);
                            $ec_fee_able = \App\Service\BillService::getTotalTaxPrice($depositable_product); 
                            $transactions_ec_able = sizeof(json_decode(\App\Service\BillService::getOrderproducts($depositable_product))); 
                            $total_price_able += $ec_price_able - $ec_fee_able;
                        }    
                        @endphp  
                        <tr> 
                            <td rowspan="2">  
                                <p>{{ $store_name }}</p>
                            </td> 
                            <td> 
                                <p>振込済み</p> 
                            </td> 
                            <td> 
                                <p>{{ $fix_order_products + $ec_order_products > 0 ? $fix_order_products+$ec_order_products : '' }}</p>
                            </td> 
                            <td> 
                                <p>{{ $fix_order_products > 0 ? $fix_order_products : '' }}</p>
                            </td>
                            <td> 
                                <p>{{ $ec_order_products > 0 ? $ec_order_products : '' }}</p>
                            </td>
                            <td> 
                                <p id="total_price">{{ ($fix_price + $ec_price > 0) ? '￥'.number_format($fix_price + $ec_price)  : ''}}</p> 
                            </td>
                            <td> 
                                <p>{{ ($fix_price > 0) ? '￥'.number_format($fix_price)  : ''}}</p>  
                            </td>
                            <td> 
                                <p>{{ ($ec_price > 0) ? '￥'.number_format($ec_price)  : ''}}</p>  
                            </td>
                            <td> 
                                <p id="total_price">{{ ($fix_fee + $ec_fee > 0) ? '￥'.number_format($fix_fee + $ec_fee)  : ''}}</p> 
                            </td>
                            <td> 
                                <p>{{ ($fix_fee > 0) ? '￥'.number_format($fix_fee)  : ''}}</p>   
                            </td>
                            <td> 
                                <p>{{ ($ec_fee > 0) ? '￥'.number_format($ec_fee)  : ''}}</p>   
                            </td>
                            <td> 
                                <p id="total_price">{{ ($fix_fee + $ec_fee > 0) ? '￥'.number_format($fix_fee + $ec_fee)  : ''}}</p> 
                            </td>
                            <td> 
                                <p id="sum_price">{{ ($total_price > 0) ? '￥'.number_format($total_price)  : ''}}</p>    
                            </td>
                            <td> 
                                <p>{{ isset($completed_at) ? $completed_at : ''}}</p> 
                            </td>
                        </tr>  
                        <tr>
                            <td> 
                                <p>入金依頼なし</p>
                            </td> 
                            <td> 
                                <p>{{ $transactions_fix_able + $transactions_ec_able > 0 ? $transactions_fix_able + $transactions_ec_able : '' }}</p>
                            </td> 
                            <td> 
                                <p>{{ $transactions_fix_able > 0 ? $transactions_fix_able : '' }}</p>
                            </td>
                            <td> 
                                <p>{{ $transactions_ec_able > 0 ? $transactions_ec_able : '' }}</p>
                            </td>
                            <td> 
                                <p id="total_price">{{ ($fix_price_able + $ec_price_able > 0) ? '￥'.number_format($fix_price_able + $ec_price_able)  : ''}}</p> 
                            </td>
                            <td> 
                                <p>{{ ($fix_price_able > 0) ? '￥'.number_format($fix_price_able)  : ''}}</p>  
                            </td>
                            <td> 
                                <p>{{ ($ec_price_able > 0) ? '￥'.number_format($ec_price_able)  : ''}}</p>  
                            </td>
                            <td> 
                                <p id="total_price">{{ ($fix_fee_able + $ec_fee_able > 0) ? '￥'.number_format($fix_fee_able + $ec_fee_able)  : ''}}</p> 
                            </td>
                            <td> 
                                <p>{{ ($fix_fee_able > 0) ? '￥'.number_format($fix_fee_able)  : ''}}</p>   
                            </td>
                            <td> 
                                <p>{{ ($ec_fee_able > 0) ? '￥'.number_format($ec_fee_able)  : ''}}</p>   
                            </td>
                            <td> 
                                <p id="total_price">{{ ($fix_fee_able + $ec_fee_able > 0) ? '￥'.number_format($fix_fee_able + $ec_fee_able)  : ''}}</p> 
                            </td>
                            <td> 
                                <p id="sum_price">{{ ($total_price_able > 0) ? '￥'.number_format($total_price_able)  : ''}}</p>    
                            </td>
                            <td> 
                                 
                            </td>
                        </tr>
                        @endforeach 
                        @if($stores->count() == 0)
                            <tr>
                                <td class="text-center" colspan="14">データが存在しません。</td>
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
            url : '{{ route("admin.sales.search_index_transfer") }}',
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


