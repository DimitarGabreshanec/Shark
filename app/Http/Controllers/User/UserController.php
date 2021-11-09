<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Auth;
use Session;

class UserController extends Controller
{
    protected $platform = 'user';
    protected $per_page = 1000;
    protected $f_menu = ''; //お店で探す
    protected $fp_type = 1;
    protected $f_from_sp = false;

    public function __construct()
    {
        $this->middleware('auth:web');
        app('view')->composer('user.*', function ($view) {
            $f_menu = $this->f_menu;
            $fp_type = $this->fp_type;
            $p_arr_favorite_stores = Auth::user()->arr_favorite_stores($fp_type);
            $f_cart_product_count = Auth::user()->getUserCartProductCount();
            $fp_from_sp = Session::get('from_sp');
            $view->with(compact('f_menu', 'p_arr_favorite_stores', 'fp_type', 'f_cart_product_count', 'fp_from_sp'));
        });
    }

}
