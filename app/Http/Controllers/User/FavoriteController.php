<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;

class FavoriteController extends UserController
{
    public function __construct()
    {
        $this->f_menu = config('const.footer_menu_kind_en.favorite');
        $this->fp_type = config('const.product_type_code.fix');
        parent::__construct();
    }

    public function index(Request $request, $product_type=1)
    {
        if($product_type == 1) {
            return view("user.favorite.shop", [
                'product_type' => $product_type
            ]);
        } else {
            return view("user.favorite.ec", [
                'product_type' => $product_type
            ]);
        }
    }
}
