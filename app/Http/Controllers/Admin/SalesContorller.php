<?php

namespace App\Http\Controllers\Admin;

use App\Models\Bill;
use App\Models\Store;
use App\Service\BillService;
use App\Service\StoreService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Session;
 
class SalesContorller extends AdminController
{

    public function index(Request $request)
    {      
        $index_param = $request->all();
        $params = Session::get('admin.sales.search_index_sales'); 
        if(isset($params['year'])){
            $index_params['year'] = $params['year']; 
        }else{
            $index_params['year'] = Carbon::now()->format('Y'); 
        } 
        
        if(isset($params['month'])){
            $index_params['month'] = $params['month']; 
        }else{
            $index_params['month'] = Carbon::now()->format('m');  
        }  
        $bill_products = BillService::doSearch($index_params);  
        return view('admin.sales.index', [
            'bills' => $bill_products,
            'index_params' => $index_params,
        ]);
    }

    public function transfer(Request $request)
    {
        $index_param = $request->all();
        $params = Session::get('admin.sales.search_index_transfer'); 
        if(isset($params['year'])){
            $index_params['year'] = $params['year']; 
        }else{
            $index_params['year'] = Carbon::now()->format('Y'); 
        } 
        
        if(isset($params['month'])){
            $index_params['month'] = $params['month']; 
        }else{
            $index_params['month'] = Carbon::now()->format('m');  
        }  

        if($index_params['year'] == 'all'){
            $index_params['year'] = Carbon::now()->format('Y');
            $index_params['month'] = null; 
        }
 
        $stores = BillService::getStoresInOrderProduct($index_params);   
        return view('admin.sales.transfer', [
            'stores' => $stores,
            'index_params' => $index_params,
        ]);
    }

    public function completed(Request $request, Bill $bill_product){
        $index_params = $request->all(); 
        if(isset($bill_product)){ 
            $bill_product->status = config('const.bill_product_type.transfer_completed'); 
            $bill_product->completed_at = Carbon::now()->format('Y-m-d H:i:s'); 
            $bill_product->updated_at = Carbon::now()->format('Y-m-d H:i:s'); 
            $bill_product->year = Carbon::now()->format('Y');
            $bill_product->month = Carbon::now()->format('m');
            $bill_product->update();
        } 
        $index_params['year'] = Carbon::parse($bill_product->applied_at)->format('Y');
        $index_params['month'] = Carbon::parse($bill_product->applied_at)->format('M');
 
        $request->session()->flash('success', '振込申請が成功しました。');

        return redirect()->route('admin.sales.detail', ['store_id' => $bill_product->store_id]); 
    }    

    public function searchIndexSales(Request $request){ 
        $index_params = $request->all();   
        Session::put('admin.sales.search_index_sales', $index_params);
        return response()->json(['result_code'=>'success']); 
    } 

    public function searchIndexTransfer(Request $request){ 
        $index_params = $request->all();   
        Session::put('admin.sales.search_index_transfer', $index_params);
        return response()->json(['result_code'=>'success']); 
    } 

    public function selectDateSale(Request $request){ 
        $index_params = $request->all();  
        Session::put('admin.sales.detail'.$index_params['store_id'],$index_params);
        return response()->json(['result_code'=>'success']); 
    } 

    public function detail(Request $request, $store_id)
    { 
        $index_params = $request->all();  
        $params = Session::get('admin.sales.detail'.$store_id);    
        if(isset($params['year'])){
            $index_params['year'] = $params['year']; 
        }else{
            $index_params['year'] = Carbon::now()->format('Y'); 
        } 
        
        if(isset($params['month'])){
            $index_params['month'] = $params['month']; 
        }else{
            $index_params['month'] = Carbon::now()->format('m');  
        }     
        $store_name = StoreService::getStoreName($store_id);
        $bill_product = BillService::doGetStoreBillProductsInDate($store_id, $index_params);    
        return view('admin.sales.detail', [
            'bill_product' => $bill_product,
            'index_params' => $index_params,
            'store_id' => $store_id,
            'store_name' => $store_name,
        ]);
    }
}
