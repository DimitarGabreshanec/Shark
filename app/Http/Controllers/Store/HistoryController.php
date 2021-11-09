<?php

namespace App\Http\Controllers\Store;
use Auth;
use Session;
use App\Models\Product;
use App\Models\StoreImg;
use App\Models\ProductImg;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use App\Service\OrderService;
use Illuminate\Support\Carbon; 

class HistoryController extends StoreController
{
    public function fix(Request $request){
        $this->f_menu = config('const.footer_store_menu_kind_en.history');
        return view("store.history.fix", [
            'order_products' => OrderService::doGetStoreOrderProducts(config('const.order_type_code.fix'), Auth::guard('store')->user()->id)
        ]);
    }

    public function ec(Request $request){ 
        $this->f_menu = config('const.footer_store_menu_kind_en.history');
        return view("store.history.ec", [
            'order_products' => OrderService::doGetStoreOrderProducts(config('const.order_type_code.ec'), Auth::guard('store')->user()->id)
        ]);
    }

    public function setComplete(Request $request, OrderProduct $order_product){
        if(is_object($order_product)) {
            $order_product->status = config('const.order_product_status_code.complete');
            $order_product->completed_at = Carbon::now()->format('Y-m-d H:i:s'); 
            $order_product->save();
        }
        return back();
    }

}
