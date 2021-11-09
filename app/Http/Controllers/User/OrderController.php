<?php

namespace App\Http\Controllers\User;
use Auth;
use Session;
use App\Models\Store;
use App\Models\Product;
use App\Service\CartService;
use Illuminate\Http\Request;
use App\Service\OrderService;
use App\Service\AddressService;
use App\Service\PaymentService;
use App\Http\Requests\User\AddressRequest;

class OrderController extends UserController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function setFixProducts(Request $request, Store $store) {
        $products = $request->input('products', []);
        foreach ($products  as $id => $product) {
            if(!isset($product['checked'])) {
                unset($products[$id]);
            }
        }
        if(!empty($products)) {
            Session::put("user.order.fix_products.store", $store);
            Session::put("user.order.fix_products.products", $products);
            return redirect(route('user.order.cart', ['order_type' => config('const.order_type_code.fix')]));
        } else {
            return back();
        }
    }

    public function cart(Request $request, $order_type)
    {
        $cart_view_data = [];
        if($order_type == config('const.order_type_code.fix')) {//
            if(is_object(Session::get("user.order.fix_products.store"))) {
               $cart_view_data = CartService::getCartViewData($order_type, Session::get("user.order.fix_products"));
            } else {
                abort(404);
            }
        } else if ($order_type == config('const.order_type_code.ec')) { //通販
            $cart_view_data = CartService::getCartViewData($order_type);

        } else {
            abort(404);
        }
        return view("user.order.cart", [
            'order_type' => $order_type,
            'cart_view_data' => $cart_view_data,
        ]);
    }

    public function addCartProduct(Request $request)
    {
        if($request->has('product_id') && $request->has('quantity')){
            $obj_product = Product::find($request->input('product_id'));
            if(is_object($obj_product)) {
                CartService::setProductToCart($obj_product, $request->input('quantity'), true);
                return response()->json([
                    'result_code'=>'success',
                    'product_count' => Auth::user()->getUserCartProductCount()
                ]);
            }
        }
        return response()->json(['result_code'=>'failed']);
    }

    public function setCartProduct(Request $request)
    {
        if($request->has('order_type') && $request->has('product_id') && $request->has('quantity')) {
            if ($request->input('order_type') == config('const.order_type_code.fix')) {
                Session::put("user.order.fix_products.products.{$request->input('product_id')}.quantity", $request->input('quantity'));
            } else if ($request->input('order_type') == config('const.order_type_code.ec')) {
                $obj_product = Product::find($request->input('product_id'));
                if(is_object($obj_product)) {
                    CartService::setProductToCart($obj_product, $request->input('quantity'));
                }
            }
        }
        return response()->json(['result_code'=>'success']);
    }

    public function getCartProductCount(Request $request)
    {
        return response()->json(['product_count'=>Auth::user()->getUserCartProductCount()]);
    }

    public function removeCartProduct(Request $request, $order_type, Product $product)
    {
        if($order_type == config('const.order_type_code.fix')) {
           Session::forget("user.order.fix_products.products.{$product->id}");
        } else if ($order_type == config('const.order_type_code.ec')) {
            CartService::setProductToCart($product, 0);
        }
        return back();
    }

    public function cartConfirm(Request $request, $order_type)
    {
        $cart_view_data = [];
        if($order_type == config('const.order_type_code.fix')) {
            if(is_object(Session::get("user.order.fix_products.store"))) {
                $cart_view_data = CartService::getCartViewData($order_type, Session::get("user.order.fix_products"));
                //Session::forget("user.order.fix.{$store_id}");
                Session::put("user.order.fix_data", $cart_view_data);
            } else {
                abort(404);
            }
        } else if ($order_type == config('const.order_type_code.ec')) {
            $cart_view_data = CartService::getCartViewData($order_type);
        } else {
            abort(404);
        }

        return view("user.order.cart_confirm", [
            'order_type' => $order_type,
            'cart_view_data' => $cart_view_data,
        ]);
    }

    public function address(Request $request, $order_type)
    {
        $index_params['user_id'] = Auth::guard('web')->user()->id;
        $address = AddressService::doSearch($index_params);
        return view("user.order.address", [
            'order_type' => $order_type,
            'address' => $address,
        ]);
    }

    public function setAddress(AddressRequest $request, $order_type)
    {
        $ec_order_data = $request->input('address');
        $ec_order_data['user_id'] = Auth::guard('web')->user()->id;
        if($request->input('save_address')){
            Session::put("user.order.ec_data.address", $ec_order_data);
        }

        AddressService::doCreate($ec_order_data );
        return redirect()->route('user.order.credit_card', [
            'order_type' => $order_type
        ]);
    }

    public function creditCard(Request $request, $order_type)
    {
        $user = Auth::user();
        if(!isset($user->gmo_card_info)) {
            return redirect()->route('user.order.credit_card', [
                'order_type' => $order_type,
            ]);
        }

        return view("user.order.credit_card_info",[
            'order_type' => $order_type,
            'card_info' => $user->gmo_card_info,
        ]);
    }

    public function creditCardForm(Request $request, $order_type)
    {
        return view("user.order.credit_card",[
            'order_type' => $order_type
        ]);
    }

    public function setOrder(Request $request, $order_type)
    {
        if($order_type == config('const.order_type_code.fix')) {
            $fix_order_data = Session::get("user.order.fix_data");
            $arr_full_order_data = OrderService::getOrderPrices($fix_order_data);
        } else if ($order_type == config('const.order_type_code.ec')) {
            $ec_order_data = CartService::getCartViewData($order_type);
            $address_data = Session::get("user.order.ec_data.address");
            $arr_full_order_data = array_merge($address_data, OrderService::getOrderPrices($ec_order_data));
        } else {
            return abort(404);
        }

        if($obj_order = OrderService::doCreateOrder($order_type, $arr_full_order_data)) {
            $obj_user = Auth::user();
            $obj_gmo = new PaymentService();

            if(!isset($user->gmo_member_id)) {
                $gmo_user = $obj_gmo->searchMember($obj_user->member_no);
                if(!isset($gmo_user['MemberID'])) {
                    $gmo_user = $obj_gmo->registerMember($obj_user->member_no);
                }
                if(isset($gmo_user['MemberID'])) {
                    $member_id = isset($gmo_user['MemberID']) ? $gmo_user['MemberID'] : null;
                    $obj_user->gmo_member_id = $member_id;
                    $obj_user->save();
                }
            }

            $tran_result = $obj_gmo->payForOrder($obj_user, $obj_order->order_no, $obj_order->order_price, $request->input('card_token', null));

            if(isset($tran_result['ErrInfo'])) {
                $obj_order->payment_log = $tran_result;
                $obj_order->order_status = config('const.order_status_code.pay_failed');
                $obj_order->save();
                $request->session()->flash('error', '決済処理中にエラーが発生しました。(' . $tran_result['ErrInfo'] . ')');
                return back();
            } else {
                $obj_order->payment_log = $tran_result;
                $obj_order->paid_at = Carbon::now()->format('Y-m-d H:i:s');
                $obj_order->order_status = config('const.order_status_code.ordered');
                $obj_order->save();

                if($obj_order->order_type == config('const.order_type_code.ec')) {
                    CartService::closeCart();
                }

                Session::forget("user.order");
                return redirect(route('user.order.completed', ['order_type' => $order_type]));
            }
        }
        $request->session()->flash('error', '注文作成に失敗しました。');
        return back();
    }

    public function completed(Request $request, $order_type)
    {
        return view("user.order.completed", [
            'order_type' => $order_type
        ]);
    }

    public function history(Request $request, $product_type=1)
    {
        $this->f_menu = config('const.footer_menu_kind_en.order');
        if($product_type == config('const.order_type_code.fix')) {
            return view("user.order.history_fix", [
                'product_type' => $product_type,
                'order_products' => OrderService::doGetUserOrderProducts(config('const.order_type_code.fix'), Auth::guard('web')->user()->id)
            ]);
        } else {
            return view("user.order.history_ec", [
                'product_type' => $product_type,
                'order_products' => OrderService::doGetUserOrderProducts(config('const.order_type_code.ec'), Auth::guard('web')->user()->id)
            ]);
        }
    }


}
