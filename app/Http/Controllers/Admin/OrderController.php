<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\OrderService;
use Illuminate\Http\Request;
use App\Models\Order;
use Auth;
use Storage;
use Session;

class OrderController extends AdminController
{
    public function __construct()
    {
        $this->per_page = 30;
        parent::__construct()
;    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search_params = $request->input('search_params', []);

        Session::put('admin.orders.index', $request->all());

        $orders = OrderService::doSearch($search_params)->paginate($this->per_page );

        return view('admin.orders.index', [
            'orders' => $orders,
            'search_params' => $search_params
        ]);
    }

    public function detail(Request $request, Order $order)
    {
        $index_params =  Session::get('admin.orders.index');

        return view('admin.orders.detail', [
            'index_params' => $index_params,
            'order' => $order
        ]);
    }


}
