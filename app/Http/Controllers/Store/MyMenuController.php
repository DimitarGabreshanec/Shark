<?php

namespace App\Http\Controllers\Store; 
use App\Service\BillService;
use Illuminate\Http\Request;
use App\Service\BankService; 
use App\Service\StoreService; 
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash; 
use App\Http\Requests\Store\MailRequest;
use App\Http\Requests\Store\BankInfoRequest;
use App\Http\Requests\Store\PasswordRequest;
use Session;

class MyMenuController extends StoreController{

    public function index(Request $request)
    {
        return view("store.my_menu.index", [
            // 'stores' => $stores
        ]);
    }

    public function passwordUpdate(PasswordRequest $request)
    {
        $store = $this->getAuthStore();
        $index_params = $request->all();

        if ($store->update(['password'=> Hash::make($request->password)])) {

            $store->sendStorePasswordUpdatedNotification($request->password);
            $request->session()->flash('success', 'パスワードを更新しました。');
        } else {
            $request->session()->flash('success', 'パスワードが失敗しました。');
        }
        return redirect()->route('store.my_menu.password_edit');
    }

    public function mailUpdate(MailRequest $request)
    {
        $store = $this->getAuthStore();
        $index_params = $request->all();
        $old_email = $store->email;
        if ($store->update(['email'=> $request->email])) {
            $store->sendStoreMailUpdatedNotification($old_email, $request->email);
            $request->session()->flash('success', 'メールアドレスを更新しました。');
        } else {
            $request->session()->flash('success', 'メールアドレス更新が失敗しました。');
        }
        return redirect()->route('store.my_menu.mail_edit');
    }

    public function mailEdit(Request $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();
        $request->session()->flash('email', $store['email']);
        return view("store.my_menu.mail_edit", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }

    public function passwordEdit(Request $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();

        return view("store.my_menu.password_edit", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }  

    public function selectTransferHistory(Request $request)
    { 
        $index_params = $request->all();  
        Session::put('store.my_menu.select_history_transfer',$index_params); 
        return response()->json(['result_code'=>'success']);  
    }

    public function transferHistory(Request $request)
    { 
        $index_params = $request->all();
        $store = $this->getAuthStore(); 

        $params = Session::get('store.my_menu.select_history_transfer'); 
         
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
        $data = $index_params; 
        $index_params['transactions_fix'] = 0;
        $index_params['transactions_ec'] = 0;
        $index_params['fix_price'] = 0;
        $index_params['ec_price'] = 0;
        $index_params['fix_fee'] = 0;
        $index_params['ec_fee'] = 0;
        $index_params['total_price'] = 0;   

        $deposited_products = BillService::getBillProductByStatus($store->id, $index_params['year'], $index_params['month'], config('const.bill_product_type.transfer_completed'));
        foreach($deposited_products as $deposited_product){
            if(!empty($deposited_product) && isset($deposited_product)){
                $index_params['transactions_fix'] += sizeof(json_decode($deposited_product->fix_order_products));
                $data['fix_order_products'] = json_decode($deposited_product->fix_order_products); 
                $index_params['transactions_ec'] += sizeof(json_decode($deposited_product->ec_order_products)); 
                $data['ec_order_products'] = json_decode($deposited_product->ec_order_products);
                $index_params['fix_price'] += $deposited_product->fix_price;
                $index_params['ec_price'] += $deposited_product->ec_price;
                $index_params['fix_fee'] += $deposited_product->fix_fee;
                $index_params['ec_fee'] += $deposited_product->ec_fee;
                $index_params['total_price'] += $deposited_product->total_price;
                $index_params['completed_at'] = Carbon::parse($deposited_product->completed_at)->format('Y/m/d');
            } 
        }  
        if($index_params['month'] == null){
            $index_params['completed_at'] = null;
        }
        $index_params['transactions_total'] = $index_params['transactions_fix'] + $index_params['transactions_ec']; 
        $index_params['price_add'] = $index_params['fix_price'] + $index_params['ec_price'];
        $index_params['fee_add'] = $index_params['fix_fee'] + $index_params['ec_fee'];

          
        $depositable_product = BillService::doGetStoreNoProductsInDate(config('const.order_type_code.fix'), $store->id, $data);
        //dd($depositable_product->get());
        if(!empty($depositable_product) && isset($depositable_product)){
            
            $index_params['fix_price_able'] = BillService::getTotalPrice($depositable_product);
            $index_params['fix_fee_able'] = BillService::getTotalTaxPrice($depositable_product);
            $index_params['transactions_fix_able'] = sizeof(json_decode(BillService::getOrderproducts($depositable_product))); 
            $index_params['total_price_able'] = $index_params['fix_price_able'] - $index_params['fix_fee_able'];
        }
        
        $depositable_product = BillService::doGetStoreNoProductsInDate(config('const.order_type_code.ec'), $store->id, $data);
        if (!empty($depositable_product) && isset($depositable_product)) {
            $index_params['ec_price_able'] = BillService::getTotalPrice($depositable_product);
            $index_params['ec_fee_able'] = BillService::getTotalTaxPrice($depositable_product); 
            $index_params['transactions_ec_able'] = sizeof(json_decode(BillService::getOrderproducts($depositable_product))); 
            $index_params['total_price_able'] += $index_params['ec_price_able'] - $index_params['ec_fee_able'];
        }   

        $index_params['transactions_total_able'] = $index_params['transactions_fix_able'] + $index_params['transactions_ec_able']; 
        $index_params['price_add_able'] = $index_params['fix_price_able'] + $index_params['ec_price_able'];
        $index_params['fee_add_able'] = $index_params['fix_fee_able'] + $index_params['ec_fee_able'];
 
        return view("store.my_menu.transfer_history", [
            'store' => $store,
            'index_params' => $index_params, 
        ]);
    }

    public function bankTransferInfo(Request $request)
    {
        $store = $this->getAuthStore();

        return view("store.my_menu.bank_transfer_info", [
            'store' => $store,
        ]);
    }

    public function bankTransferUpdate(BankInfoRequest $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();

        if (StoreService::doUpdateBank($store, $index_params)) {
            $request->session()->flash('success', '銀行振込口座を保存しました。');
        } else {
            $request->session()->flash('error', '銀行振込口座保存が失敗しました。');
        }
        return redirect()->route('store.my_menu.bank_transfer_info', $store);

    }

    public function getBankBranch(Request $request)
    {
        if($request->has('bank_code')){
            return response()->json([
                'result_code'=>'success',
                'branch' => BankService::getBranch($request->input('bank_code'))
            ]);
        }
        return response()->json(['result_code'=>'failed']);
    }

    public function flowOfUse(Request $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();

        return view("store.my_menu.flow_of_use", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }

    public function fag(Request $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();

        return view("store.my_menu.fag", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }

    public function contactUs(Request $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();

        return view("store.my_menu.contact_us", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    }
    public function termsOfService(Request $request)
    {
        $index_params = $request->all();
        $store = $this->getAuthStore();

        return view("store.my_menu.terms_of_service", [
            'store' => $store,
            'index_params' => $index_params,
        ]);
    } 
    
}
