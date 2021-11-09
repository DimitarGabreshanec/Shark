<?php

namespace App\Http\Controllers\User;

use Auth;
use Session;
use App\Models\Store;
use App\Models\Product;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Service\StoreService;
use App\Service\ProductService;
use App\Service\CategoryService;

class StoresController extends UserController
{
    public function __construct()
    {
        $this->f_menu = config('const.footer_menu_kind_en.store');
        parent::__construct();
    }

    public function imasuguSearch(Request $request, $view_kind='map')
    { 
        $this->f_menu = config('const.footer_menu_kind_en.store');
        $index_params = $request->all();
        $imasugu = StoreService::updateImasugu($index_params);

        $search_params['type'] = config('const.product_type_code.fix');
        $search_params['check'] = isset($imasugu['check']) ? $imasugu['check'] : 0;
        $search_params['categories'] = isset($imasugu['categories']) ? $imasugu['categories'] : null;

        //$stores = StoreService::doGetImasuguList($search_params);
        $user = Auth::user();
        $self_pos = $user->getPosition();
        if($view_kind == 'account'){
            $request->session()->flash('success', 'イマスグを保存しました。');
            $imasugu = $user['imasugu'];
            $imasugu = json_decode($imasugu, true);
            $check = isset($imasugu['check']) ? $imasugu['check'] : 0;
            $categories = isset($imasugu['categories']) ? $imasugu['categories'] : null;

            return view("user.store.imasugu_change", [
                'check' => $check,
                'selected_categories' => $categories,
                'search_params'=> $search_params,
                'back' => 'account',
            ]);
        }
        else{
            return view("user.store.imasugu_{$view_kind}", [
                'self_pos' => $self_pos,
                'search_params'=> $search_params,
                'user' => $user,
            ]);
        }
    }

    public function imasuguCond(Request $request, $back='map')
    {
        $this->f_menu = config('const.footer_menu_kind_en.fix');
        $search_params['type'] = config('const.product_type_code.fix');
        $index_params = $request->all();
        $search_params = $request->input('search_params');
        $user = Auth::user();
        $imasugu = $user['imasugu'];
        $imasugu = json_decode($imasugu, true);
        $check = isset($imasugu['check']) ? $imasugu['check'] : 0;
        $categories = isset($imasugu['categories']) ? $imasugu['categories'] : null;

        return view("user.store.imasugu_change", [
            'check' => $check,
            'selected_categories' => $categories,
            'search_params'=> $search_params,
            'back' => $back,
        ]);
    }

    public function imasugu(Request $request, $view_kind='map')
    {
        $this->f_menu = config('const.footer_menu_kind_en.store');
        $index_params = $request->all();
        $search_params = $request->input('search_params');
        $search_params['type'] = config('const.product_type_code.fix');

        $user = Auth::user();
        $self_pos = $user->getPosition();
        Session::put('user.store.imasugu_{$view_kind}', $index_params);

        $stores = StoreService::doGetECList($search_params);
        return view("user.store.imasugu_{$view_kind}", [
            'stores' => $stores,
            'search_params'=> $search_params,
            'self_pos' => $self_pos,
            'user' => $user,
        ]);

    }

    public function search(Request $request)
    {
        $this->fp_type = config('const.product_type_code.fix');
        $this->f_menu = config('const.footer_menu_kind_en.store');
        $index_params = $request->all();
        $stores = StoreService::doSearch($index_params);
        return view("user.store.search", [
            'stores' => $stores->get(),
        ]);
    }

    public function searchCondition(Request $request)
    {
        $this->f_menu = config('const.footer_menu_kind_en.fix');
        $index_params = $request->all();
        $search_params = $request->input('search_params');
        $search_params['type'] = config('const.product_type_code.fix');

        if(isset($search_params['category_id'])){
            Session::put('user.stores.search_cond', $search_params);
        }
        else{
            $search_params = Session::get('user.stores.search_cond');
        }
        $user = Auth::user();
        $self_pos = $user->getPosition();
        $stores = StoreService::doGetECList($search_params);
        return view("user.store.search_cond", [
            'stores' => $stores,
            'search_params'=>$search_params,
            'self_pos' => $self_pos,
            'user' => $user,
        ]);

    }

    public function searchMap(Request $request, $search_params='')
    {
        $this->f_menu = config('const.footer_menu_kind_en.fix');
        $index_params = $request->all();
        $search_params = $request->input('search_params');
        $search_params['type'] = config('const.product_type_code.fix');
        $index_params = Session::get('user.stores.search.condition');
        $search_params = Session::get('user.stores.search_cond');

        if(isset($index_params['category_id'])){
            $search_params['category_id'] = $index_params['category_id'];
        }
        if(isset($index_params['price_from'])){
            $search_params['price_from'] = $index_params['price_from'];
        }
        if(isset($index_params['price_to'])){
            $search_params['price_to'] = $index_params['price_to'];
        }
        if(isset($index_params['prefecture'])){
            $search_params['prefecture'] = $index_params['prefecture'];
        }
        $user = Auth::user();
        $search_params['position'] = $user->getPosition();
        $stores = StoreService::doGetECList($search_params);
        $self_pos = $user->getPosition();
        return view("user.store.search_map", [
            'stores' => $stores,
            'search_params'=>$search_params,
            'self_pos' => $self_pos,
            'user' => $user,
        ]);
    }

    public function storeProducts(Request $request, Store $store, $product_type=1)
    {
        if($product_type == config('const.product_type_code.fix')) {
            //お店
            $selected_products =  Session::get("user.order.fix.{$store->id}", []);
            $this->f_menu = config('const.footer_menu_kind_en.store');
            $products = $store->getProductArray(config('const.footer_menu_kind_en.store'));
            return view("user.store.store_f_products", [
                'product_type' => $product_type,
                'products' => $products,
                'store' => $store,
                'selected_products' => $selected_products
            ]);
        } else {
            $this->f_menu = config('const.footer_menu_kind_en.ec');
            $products = $store->getProductArray(config('const.footer_menu_kind_en.ec'));
            return view("user.store.store_e_products", [
                'product_type' => $product_type,
                'products' => $products,
                'store' => $store,
            ]);
        }
    }

    public function productInfo(Request $request, Store $store, Product $product)
    {
        return view("user.store.product_info", [
            'store' => $store,
            'product' => $product,
        ]);
    }

    public function storeInfo(Request $request, Store $store, $product_type=1)
    {
        if($product_type == 1) {
            $this->f_menu = config('const.footer_menu_kind_en.store');
        } else {
            $this->f_menu = config('const.footer_menu_kind_en.ec');
        }
        return view("user.store.store_info", [
            'product_type' => $product_type,
            'store' => $store,
        ]);
    }

    public function ecList(Request $request)
    {
        $this->f_menu = config('const.footer_menu_kind_en.ec');
        $this->fp_type = config('const.product_type_code.ec');
        $index_params = $request->all();
        $search_params = $request->input('search_params');
        $search_params['type'] = config('const.product_type_code.ec');
        if(isset($search_params['category_id'])){
            Session::put('user.stores.ec_list', $search_params);
        } else{
            $search_params = Session::get('user.stores.ec_list');
        }
        $stores = StoreService::doGetECList($search_params);
        return view("user.store.ec_list", [
            'stores' => $stores,
            'search_params'=>$search_params,
        ]);
    }

    public function toggleFavorite(Request $request)
    {
        $store_id = $request->input('store_id');
        $product_type= $request->input('product_type');
        if(Auth::user()->obj_store_favorites()->wherePivot('product_type', $product_type)->toggle([$store_id=>['product_type' => $product_type]])) {
            return response()->json(['result_code'=>'success']);
        } else {
            return response()->json(['result_code'=>'failed']);
        }
    }

}
