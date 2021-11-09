<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderProduct;
use Carbon\Carbon;

use Storage;
use Auth;

class OrderService
{
    public static function doSearch($search_params)
    {
        $orders = Order::orderByDesc('updated_at')->where('order_status', '>=', config('const.order_status_code.pay_failed'));
        if (isset($search_params['order_no']) && $search_params['order_no']) {
            $orders = $orders->where('order_no', 'like', '%'. $search_params['order_no'].'%');
        }

        if (isset($search_params['user_name']) && $search_params['user_name']) {
            $orders = $orders->whereHas('user', function($query) use ($search_params) {
                $query->where('name', 'like', '%'. $search_params['user_name'].'%');
            });
        }

        if (isset($search_params['order_start_at']) && $search_params['order_start_at']) {
            $orders = $orders->where(function ($query) use($search_params){
                $query->where('ordered_at', '>=' , $search_params['order_start_at']);
            });
        }
        if (isset($search_params['order_end_at']) && $search_params['order_end_at']) {
            $orders = $orders->where(function ($query) use($search_params){
                $query->where('ordered_at', '<=' , $search_params['order_end_at']);
            });
        }

        if (isset($search_params['completed_start_at']) && $search_params['completed_start_at']) {
            $orders = $orders->where(function ($query) use($search_params){
                $query->where('completed_at', '>=' , $search_params['completed_start_at']);
            });
        }
        if (isset($search_params['completed_end_at']) && $search_params['completed_end_at']) {
            $orders = $orders->where(function ($query) use($search_params){
                $query->where('completed_at', '<=' , $search_params['completed_end_at']);
            });
        }

        return $orders;
    }

    public static function doCreateOrder($order_type, $order_data)
    {
        $input_params = [];

        $input_params['order_no'] =  self::generateOrderNumber($order_type);
        $input_params['order_type'] = $order_type;

        if($order_type == config('const.order_type_code.ec')) {
            $input_params['cart_id'] = Auth::user()->opened_cart->id;
            $input_params['last_name'] = $order_data['last_name'];
            $input_params['first_name'] = $order_data['first_name'];
            $input_params['last_name_kana'] = $order_data['last_name_kana'];
            $input_params['first_name_kana'] = $order_data['first_name_kana'];
            $input_params['post_first'] = $order_data['post_first'];
            $input_params['post_second'] = $order_data['post_second'];
            $input_params['prefecture'] = $order_data['prefecture'];
            $input_params['address1'] = $order_data['address1'];
            $input_params['address2'] = $order_data['address2'];
            $input_params['address3'] = $order_data['address3'];
            $input_params['tel'] = $order_data['tel'];
        }

        $input_params['order_status'] = config('const.order_status_code.temp');
        $input_params['user_id'] = auth()->id();
        $input_params['products_price'] = $order_data['products_price'];
        $input_params['discounted_price'] = $order_data['discounted_price'];
        $input_params['tax_price'] = $order_data['tax_price'];
        $input_params['ship_price'] = $order_data['ship_price'];
        $input_params['order_price'] = $order_data['order_price'];
        $input_params['ordered_at'] = Carbon::now()->format('Y-m-d H:i:s');

        if ($order = Order::create($input_params)){
            if(isset($order_data['products'])) {
                foreach ($order_data['products'] as $key=>$product_data) {
                    $product_data['order_id'] = $order->id;
                    $product_data['product_id'] = $key;
                    $product_data['user_id'] = auth()->id();
                    if($order_type == config('const.order_type_code.ec')) {
                        $product_data['cart_id'] = Auth::user()->opened_cart->id;
                    }
                    OrderProduct::create($product_data);
                }
            }
            return $order;
        } else {
            return false;
        }
    }


    public static function doGetStoreOrderProducts($order_type, $store_id) {
        return  OrderProduct::orderBy('status')->orderByDesc('created_at')
                ->where('store_id', $store_id)
                ->whereHas('obj_order', function($query) use ($order_type) {
                    $query->where('order_status', '>=', config('const.order_status_code.ordered'));
                    $query->where('order_type', $order_type);
                })->get();
    }

    public static function doGetUserOrderProducts($order_type, $user_id) {
        return  OrderProduct::orderBy('status')->orderByDesc('created_at')
                ->where('user_id', $user_id)
                ->whereHas('obj_order', function($query) use ($order_type) {
                    $query->where('order_status', '>=', config('const.order_status_code.ordered'));
                    $query->where('order_type', $order_type);
                })->get();
    }

    public static function getOrderPrices($order_data) {
        $arr_prices = [];
        $arr_prices['products_price'] = 0;
        $arr_prices['discounted_price'] = 0;
        $arr_prices['ship_price'] = 0;
        $arr_prices['tax_price'] = 0;
        $arr_prices['order_price'] = 0;
        if(isset($order_data)){
            foreach ($order_data as $store_data) {
                if(isset($store_data['products']) && count($store_data['products']) > 0) {
                    foreach ($store_data['products'] as $order_product) {
                        if(is_object($obj_product = $order_product['obj_product']) && $order_product['quantity']) {
                            $product_price = $obj_product->price * $order_product['quantity'];
                            $discounted_price = $obj_product->getRealPrice() * $order_product['quantity'] ;
                            $tax_price =  \App\Service\ConfigurationService::getTaxPrice($discounted_price);
                            $arr_prices['products'][$obj_product->id]['product_name'] = $obj_product->product_name;
                            $arr_prices['products'][$obj_product->id]['store_id'] = $obj_product->store_id;
                            $arr_prices['products'][$obj_product->id]['quantity'] = $order_product['quantity'];
                            $arr_prices['products'][$obj_product->id]['price'] = $obj_product->price;
                            $arr_prices['products'][$obj_product->id]['discount_type'] = $obj_product->discount_type;
                            $arr_prices['products'][$obj_product->id]['discount'] = $obj_product->discount;
                            $arr_prices['products'][$obj_product->id]['discounted_price'] = $discounted_price;
                            $arr_prices['products'][$obj_product->id]['tax_price'] = (int)$tax_price;
                            $arr_prices['products'][$obj_product->id]['ship_price'] = $obj_product->ship_price;
                            $arr_prices['products'][$obj_product->id]['products_price'] = $product_price;
                            $arr_prices['products'][$obj_product->id]['total_price'] = $discounted_price + $tax_price + $obj_product->ship_price;

                            $arr_prices['products_price'] += $product_price;
                            $arr_prices['discounted_price'] += $discounted_price;
                            $arr_prices['ship_price'] += $obj_product->ship_price;
                            $arr_prices['tax_price'] += $tax_price;
                            $arr_prices['order_price'] += $discounted_price + $tax_price + $obj_product->ship_price;
                        }
                    }
                }
            }
        }
        return $arr_prices;
    }


    public static function doCreate(Cart $cart, $input_params)
    {
        $input_params['order_no'] =  self::generateOrderNumber($input_params['order_type']);
        $input_params['order_status'] = config('const.order_status_code.temp');
        $input_params['cart_id'] =  $cart->id;
        $input_params['user_id'] = auth()->id();
        $input_params['cart_price'] = $cart->cart_price;
        $input_params['ship_price'] = 0;
        $input_params['order_price'] = $cart->cart_price - $input_params['ship_price'];
        $input_params['ordered_at'] = Carbon::now()->format('Y-m-d H:i:s');
        $order = Order::create($input_params);

        if ($order->save()){
            $cart->cart_status = config('const.cart_status_code.close');
            $cart->save();
            return $order;
        } else {
            return false;
        }
    }

    public static function doUpdate(Order $order, $input_params)
    {
        if($input_params['order_status']  != $order->getOriginal('order_status')
            && $input_params['order_status'] == config('const.order_status_code.completed')
            && is_null($order->completed_at)) {
            $input_params['completed_at'] = Carbon::now()->format('Y-m-d H:i:s');
        }
       return $order->update($input_params);

    }

    public static function doGetOrdersUser($date)
    {
        if (isset($date['year'])) {
            $current_year = $date['year'];
        } else {
            $current_year = Carbon::now()->format('Y');
        }

        if (isset($date['month'])) {
            $current_month = $date['month'];
        } else {
            $current_month = Carbon::now()->format('n');
        }

        if($current_month == 0) {
            $current_year -= 1;
            $current_month = 12;
        } else if($current_month == 13) {
            $current_year += 1;
            $current_month = 1;
        }
        return Order::orderByDesc('paid_at')
            ->where('user_id', auth()->id());
    }

    public static function getUserOrders($user) {
        return Order::orderByDesc('created_at')
            ->where('user_id', $user->id)
            ->where('order_status', '!=', config('const.order_status_code.temp'))
            ->get();
    }

    public static function generateOrderNumber($oder_type) {
        if($oder_type == config('const.order_type_code.fix')) {
            $prefix_code = config('const.f_order_code') ;
        } else {
            $prefix_code = config('const.e_order_code');
        }
        $latest_code = Order::orderByDesc('id')->where('order_type', $oder_type)->first();
        if(is_object($latest_code)) {
            $latest_code_no = (int) str_replace($prefix_code, "", ltrim($latest_code->order_no, '0')) ;
            $new_number = $latest_code_no + 1;
            $new_code_no = $prefix_code . str_pad($new_number, 10, "0", STR_PAD_LEFT) ;
        } else {
            $new_code_no = $prefix_code . '0000000001';
        }
        return $new_code_no;
    }


    public static function getDataByYear($year)
    {
        return Order::where('paid_at', 'like', '%'.$year.'%');
    }

    public static function getDataByDate($date)
    {
        if (isset($date['year'])) {
            $current_year = $date['year'];
        } else {
            $current_year = Carbon::now()->format('Y');
        }

        if (isset($date['month'])) {
            $current_month = $date['month'];
        } else {
            $current_month = Carbon::now()->format('n');
        }


        if (isset($date['day'])) {
            $current_day = $date['day'];
        } else {
            $current_day = Carbon::now()->format('d');
        }

        return Order::whereYear('paid_at', $current_year)
            ->whereMonth('paid_at', $current_month)
            ->whereDay('paid_at', $current_day);
    }

    public static function getOrderProduct($id)
    {
        return Order::findOrFail($id);
    }
}
