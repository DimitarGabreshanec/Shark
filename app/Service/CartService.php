<?php

namespace App\Service;

use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Product;
use Auth;
use DB;

class CartService {

    public static function setProductToCart($product, $quantity, $is_add=false) {
        $user_id = Auth::user()->id;
        $open_cart = self::getOpenCart($user_id);
        if(is_object($open_cart)) {
            $cart_product = CartProduct::firstOrCreate([
                'cart_id' => $open_cart->id,
                'user_id' => $user_id,
                'product_id' => $product->id,
            ]);

            if($is_add) {
                $cart_product->quantity = $cart_product->quantity + $quantity;
            } else {
                $cart_product->quantity = $quantity;
            }

            if ($cart_product->quantity > 0) {
                $cart_product->store_id = $product->store_id;
                $cart_product->price = $product->price;
                $cart_product->discount_type = $product->discount_type;
                $cart_product->discount = $product->discount;
                $cart_product->products_price = $product->price * $cart_product->quantity;
                $cart_product->discounted_price = $product->getRealPrice() * $cart_product->quantity;
                $cart_product->tax_price = \App\Service\ConfigurationService::getTaxPrice($cart_product->discounted_price);
                $cart_product->ship_price = $product->ship_price;
                $cart_product->total_price = $cart_product->discounted_price +  $cart_product->tax_price + $cart_product->ship_price;
                $cart_product->save();
            } else {
                $cart_product->delete();
            }

            $open_cart->products_price = DB::table('cart_products')
                ->where('cart_id', $open_cart->id)
                ->where('user_id', $user_id)
                ->sum('products_price');

            $open_cart->discounted_price = DB::table('cart_products')
                ->where('cart_id', $open_cart->id)
                ->where('user_id', $user_id)
                ->sum('discounted_price');

            $open_cart->tax_price = DB::table('cart_products')
                ->where('cart_id', $open_cart->id)
                ->where('user_id', $user_id)
                ->sum('tax_price');
            $open_cart->ship_price = DB::table('cart_products')
                ->where('cart_id', $open_cart->id)
                ->where('user_id', $user_id)
                ->sum('ship_price');

            $open_cart->cart_price = DB::table('cart_products')
                ->where('cart_id', $open_cart->id)
                ->where('user_id', $user_id)
                ->sum('total_price');

            if($open_cart->save()) {
                return $open_cart;
            }
        }
        return null;
    }

    public static function getOpenCart($user_id) {
        return Cart::firstOrCreate([
            'user_id' => $user_id,
            'cart_status' => config('const.cart_status_code.active')
        ]);
    }

    public static function closeCart() {
        $opened_cart = Auth::user()->opened_cart;
        if(is_object($opened_cart)) {
            $opened_cart->cart_status = config('const.cart_status_code.close');
            return $opened_cart->save();
        }
        return  false;
    }

    public static function getCartViewData($order_type, $fix_order_data=null) {
        $cart_data = [];
        if($order_type == config('const.order_type_code.fix')) {
            $cart_data[0]['obj_store'] = $fix_order_data['store'];
            foreach ($fix_order_data['products'] as $id => $record) {
                if($obj_product = Product::find($id)) {
                    $cart_product['obj_product'] = $obj_product;
                    $cart_product['quantity'] = $record['quantity'];
                    $cart_data[0]['products'][] = $cart_product;
                }
            }
            if(!isset($cart_data[0]['products'])) {
                $cart_data = [];
            }
        } else if ($order_type == config('const.order_type_code.ec')) {
            $cart_products = Auth::user()->getUserCartProducts();
            if(!is_null($cart_products)) {
                foreach ($cart_products as $c_product) {
                    $cart_data[$c_product->store_id]['obj_store'] = $c_product->obj_store;
                    $cart_product['obj_product'] = $c_product->obj_product;
                    $cart_product['quantity'] = $c_product['quantity'];
                    $cart_data[$c_product->store_id]['products'][] = $cart_product;
                }
            }
        } else {
            abort(404);
        }
        return $cart_data;
    }

}
