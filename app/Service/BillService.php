<?php

namespace App\Service; 
use Carbon\Carbon; 
use App\Models\Bill;
use App\Models\OrderProduct;

class BillService
{  
    public static function doCreate($data) {    
        if ($obj_bill = Bill::create($data)) {
            return $obj_bill;
        }
        return null;
    }
 
    public static function doSearch($search_params){

        $bills = Bill::orderByDesc('updated_at');  
        if(isset($search_params['store_id']) && !empty($search_params['store_id'])) {
            $bills = $bills->where('store_id', '=', $search_params['store_id']);
        } 
        if(isset($search_params['year']) && !empty($search_params['year'])) {
            $bills = $bills->where('year', '=', $search_params['year']);
        }
        if(isset($search_params['month']) && !empty($search_params['month'])) {
            $bills = $bills->where('month', '=', $search_params['month']);
        }

        if(isset($search_params['status']) && !empty($search_params['status'])) {
            $bills = $bills->where('status', '=', $search_params['status']);
        }
        return $bills->get();
    }

    public static function getMinDateOfBillProduct($store_id) {
        $minDate = Bill::orderByDesc('applied_at')->where('store_id', '=', $store_id)
            ->min('applied_at');   
        return $minDate;
    } 

    public static function getMinDateOfBillProducts() {
        $minDate = Bill::orderByDesc('applied_at')->select('applied_at')->min('applied_at');   
        return $minDate;
    }

    public static function getCurDateArrayOfBillProducts() {
        $minDate = self::getMinDateOfBillProducts(); 
        $min_year = Carbon::parse($minDate)->format('Y');
        $min_month = Carbon::parse($minDate)->format('m');
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('m'); 
        $date_array = [];
        for($year = $min_year; $year <= $current_year; $year++){
            $month_start = 1; 
            if($year == $min_year){
                $month_start = $min_month;
            }
            $month_end = 12;
            if($year == $min_year){
                $month_end = $current_month;
            } 
            for ($month = $month_start; $month <= $month_end; $month++) {  
                $date = date(DATE_ATOM, mktime(0, 0, 0, $month, 1, $year)); 
                array_push($date_array, Carbon::parse($date)->format('Y-m'));
            } 
        } 
        return $date_array;
    } 
 
    public static function getCurDateArrayOfBillProduct($store_id) {
        $minDate = Bill::orderByDesc('applied_at')->where('store_id', '=', $store_id)
            ->min('applied_at'); 
        $min_year = Carbon::parse($minDate)->format('Y');
        $min_month = Carbon::parse($minDate)->format('m');
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('m'); 
        $date_array = [];
        for($year = $min_year; $year <= $current_year; $year++){
            $month_start = 1; 
            if($year == $min_year){
                $month_start = $min_month;
            }
            $month_end = 12;
            if($year == $min_year){
                $month_end = $current_month;
            } 
            for ($month = $month_start; $month <= $month_end; $month++) {  
                $date = date(DATE_ATOM, mktime(0, 0, 0, $month, 1, $year)); 
                array_push($date_array, Carbon::parse($date)->format('Y-m'));
            } 
        } 
        return $date_array;
    } 

    
    public static function doGetStoreBillProductsInDate($store_id, $index_params){ 
        if (isset($index_params['year'])) {
            $current_year = $index_params['year'];
        } else {
            $current_year = Carbon::now()->format('Y');
        }

        if (isset($index_params['month'])) {
            $current_month = $index_params['month'];
        } else {
            $current_month = Carbon::now()->format('m');
        } 

        if($current_month == 0) {
            $current_year -= 1;
            $current_month = 12;
        } else if($current_month == 13) {
            $current_year += 1;
            $current_month = 1;
        } 
         
        $bill_products = Bill::orderByDesc('updated_at');
        $bill_products = $bill_products->where('year', $current_year)
            ->where('month', $current_month)
            ->where('store_id', '=', $store_id)->first();  
  
        return $bill_products; 
    }

    public static function getStoresInOrderProduct($index_params){    
        $order_products = OrderProduct::orderBy('store_id')->whereYear('created_at', $index_params['year']);
        if($index_params['month'] != null){
            $order_products = $order_products->whereMonth('created_at', $index_params['month']);
        }
        $stores = $order_products->select('store_id')->distinct()->pluck('store_id');
        return $stores;
    }

    public static function getTotalPrice($order_products){ 
        if(is_object($order_products)){
            return $order_products->sum('total_price');
        }
        return 0;
    }

    public static function getTotalTaxPrice($order_products){
        if(is_object($order_products)){
            return $order_products->sum('tax_price');
        }
        return 0;
    }

    public static function getOrderproducts($order_products){
        if(is_object($order_products)){ 
            $products = [];
            $products_arry = $order_products->select('id')->distinct()->pluck('id');
            foreach($products_arry as $key => $value){
                array_push($products, $value);
            }  
            return json_encode($products);
        }
        return null;
    }
 
    public static function getDataArrayTransferHistory($store_id) { 
        $minDate = OrderProduct::orderByDesc('created_at')->where('store_id', '=', $store_id)    
            ->min('created_at');  
        $min_year = Carbon::parse($minDate)->format('Y');
        $min_month = Carbon::parse($minDate)->format('m');
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('m'); 
        $date_array = [];
        for($year = $min_year; $year <= $current_year; $year++){
            $month_start = 1; 
            if($year == $min_year){
                $month_start = $min_month;
            }
            $month_end = 12;
            if($year == $min_year){
                $month_end = $current_month;
            } 
            for ($month = $month_start; $month <= $month_end; $month++) {  
                $date = date(DATE_ATOM, mktime(0, 0, 0, $month, 1, $year)); 
                array_push($date_array, Carbon::parse($date)->format('Y-m'));
            } 
        }  
        return $date_array;
    } 

    public static function getMinDateOfTransferProducts() { 
        return OrderProduct::orderByDesc('created_at')->min('created_at');  
    }

    public static function getDataArrayTransfer() { 
        $minDate = self::getMinDateOfTransferProducts();
        $min_year = Carbon::parse($minDate)->format('Y');
        $min_month = Carbon::parse($minDate)->format('m');
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('m'); 
        $date_array = [];
        for($year = $min_year; $year <= $current_year; $year++){
            $month_start = 1; 
            if($year == $min_year){
                $month_start = $min_month;
            }
            $month_end = 12;
            if($year == $min_year){
                $month_end = $current_month;
            } 
            for ($month = $month_start; $month <= $month_end; $month++) {  
                $date = date(DATE_ATOM, mktime(0, 0, 0, $month, 1, $year)); 
                array_push($date_array, Carbon::parse($date)->format('Y-m'));
            } 
        }  
        return $date_array;
    } 
    
    public static function getCurDateArrayOfOrderProduct($store_id) { 
        $minDate = self::getMinDateOfOrderProduct($store_id);
        $min_year = Carbon::parse($minDate)->format('Y');
        $min_month = Carbon::parse($minDate)->format('m');
        $current_year = Carbon::now()->format('Y');
        $current_month = Carbon::now()->format('m'); 
        $date_array = [];
        for($year = $min_year; $year <= $current_year; $year++){
            $month_start = 1; 
            if($year == $min_year){
                $month_start = $min_month;
            }
            $month_end = 12;
            if($year == $min_year){
                $month_end = $current_month;
            } 
            for ($month = $month_start; $month <= $month_end; $month++) {  
                $date = date(DATE_ATOM, mktime(0, 0, 0, $month, 1, $year)); 
                array_push($date_array, Carbon::parse($date)->format('Y-m'));
            } 
        }  
        return $date_array;
    } 

    public static function getMinDateOfOrderProduct($store_id) {
        $minDate = OrderProduct::orderByDesc('completed_at')->where('status', '=', config('const.order_product_status_code.complete'))
            ->where('store_id', '=', $store_id)    
            ->min('completed_at');  
        return $minDate;
    } 

    public static function getMinDateOfTransferOrderProduct($store_id) {
        $minDate = OrderProduct::orderByDesc('created_at')->where('status', '=', config('const.order_product_status_code.temp'))
            ->where('store_id', '=', $store_id)    
            ->min('created_at');  
        return $minDate;
    }  
    
    public static function getBillProductByStatus($store_id, $year, $month, $transer_status){
        $bills = Bill::orderBy('updated_at')->where('status', '=', $transer_status);  
        if(isset($store_id) && !empty($store_id)){
            $bills = $bills->where("store_id", "=", $store_id); 
        }
        
        if(isset($year) && !empty($year)){
            $bills = $bills->where("year", "=", $year);
        }
         
        if(isset($month) && !empty($month)){
            $bills = $bills->where("month", "=", $month);
        }  
        
        
        return $bills->get();
    }

    public static function doGetStoreOrderProductsInDate($product_type, $store_id, $index_params){  
        $current_year = $index_params['year'];
        $current_month = $index_params['month']; 
        
        if($current_month === 0) {
            $current_year -= 1;
            $current_month = 12;
        } else if($current_month == 13) {
            $current_year += 1;
            $current_month = 1;
        }
         
        $order_products = OrderProduct::orderByDesc('created_at')->where('status', '=', config('const.order_product_status_code.temp'));
        $order_products = $order_products->whereYear('created_at', $index_params['year']) 
            ->where('store_id', '=', $store_id);  

        if($current_month != null){
            $order_products = $order_products->whereMonth('created_at', $index_params['month']);
        } 

        if($product_type == config('const.order_type_code.fix')){ 
            $order_products = $order_products->where('cart_id', '=', null);  
        }else{  
            $order_products = $order_products->where('cart_id', '>', 0);   
        }   
        return $order_products; 
    }

    public static function doGetStoreNoProductsInDate($product_type, $store_id, $index_params){  
        $current_year = $index_params['year'];
        $current_month = $index_params['month']; 
        
        if($current_month === 0) {
            $current_year -= 1;
            $current_month = 12;
        } else if($current_month == 13) {
            $current_year += 1;
            $current_month = 1;
        }
         
        $order_products = OrderProduct::orderByDesc('created_at')->where('status', '=', config('const.order_product_status_code.temp'));
        $order_products = $order_products->whereYear('created_at', $index_params['year']) 
            ->where('store_id', '=', $store_id);  

        if($current_month != null){
            $order_products = $order_products->whereMonth('created_at', $index_params['month']);
        }

        if($product_type == config('const.order_type_code.fix')){ 
            $order_products = $order_products->where('cart_id', '=', null); 
            if(isset($index_params['fix_order_products']) && !empty($index_params['fix_order_products'])){
                $order_products->whereNotIn('id', $index_params['fix_order_products']);   
            }
        }else{  
            $order_products = $order_products->where('cart_id', '>', 0);  
            if(isset($index_params['ec_order_products']) && !empty($index_params['ec_order_products'])){
                $order_products->whereNotIn('id', $index_params['ec_order_products']); 
                
            }  
        }   
        return $order_products; 
    }
}
