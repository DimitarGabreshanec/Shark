<?php

namespace App\Http\Controllers\Store;
use App\Service\BillService;
use Illuminate\Http\Request; 
use Illuminate\Support\Carbon; 
use App\Service\ConfigurationService;
use Session;

class BillController extends StoreController
{
    public function totalSales(Request $request)
    {
        $index_params = $request->all();  
        $store = $this->getAuthStore();    
        $params = Session::get('store.bill.sales'); 
         
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

        $index_params['store_id'] = $store->id;
        $billProduct = BillService::doSearch($index_params);   
        
        if(sizeof($billProduct)){
            $index_params['deposite_enable'] = null;
        }else{
            $index_params['deposite_enable'] = 1;
        }  
         
        $order_products = BillService::doGetStoreOrderProductsInDate(config('const.order_type_code.fix'), $store->id, $index_params);
        $index_params['fix_price'] = BillService::getTotalPrice($order_products);
        $index_params['fix_fee'] = BillService::getTotalTaxPrice($order_products); 

        $order_products = BillService::doGetStoreOrderProductsInDate(config('const.order_type_code.ec'), $store->id, $index_params);
        $index_params['ec_price'] = BillService::getTotalPrice($order_products);
        $index_params['ec_fee'] = BillService::getTotalTaxPrice($order_products);  
        return view("store.bill.total_sales", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }

    public function orderDeposite(Request $request){
        $store = $this->getAuthStore();
        $index_params = $request->all();  
        return view("store.bill.order_deposite", [
            'store' => $store, 
            'index_params' => $index_params,
        ]);
    }    

    public function selectBill(Request $request){ 
        $index_params = $request->all();  
        Session::put('store.bill.sales',$index_params);
        return response()->json(['result_code'=>'success']); 
    } 

    public function orderDepositeConfirm(Request $request){
        $store = $this->getAuthStore();
        $index_params = $request->all();
        $data = $index_params['params']; 
        $data['store_id'] = $store->id; 
        $data['created_at'] = Carbon::now()->format('Y-m-d H:i:s'); 
        $data['created_by'] = $store->id;
        $data['applied_at'] = Carbon::now()->format('Y-m-d H:i:s'); 
        $data['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');  
        $data['updated_by'] = $store->id;
        $data['fee_fix'] = ConfigurationService::getFeeFix();
        $data['fee_percent'] = ConfigurationService::getFeePercent(); 

        $order_products = BillService::doGetStoreOrderProductsInDate(config('const.order_type_code.fix'), $store->id, $data);
        $data['fix_price'] = BillService::getTotalPrice($order_products);
        $data['fix_fee'] = BillService::getTotalTaxPrice($order_products);
        $data['fix_order_products'] = BillService::getOrderproducts($order_products);

        $order_products = BillService::doGetStoreOrderProductsInDate(config('const.order_type_code.ec'), $store->id, $data);
        $data['ec_price'] = BillService::getTotalPrice($order_products);
        $data['ec_fee'] = BillService::getTotalTaxPrice($order_products); 
        $data['ec_order_products'] = BillService::getOrderproducts($order_products); 

        if(BillService::doCreate($data)){
            $request->session()->flash('success', '入金依頼が成功しました。'); 
        }else{
            $request->session()->flash('success', '入金依頼が失敗しました。'); 
        }
        return redirect()->route('store.bill.total_sales'); 
        
    }  
}