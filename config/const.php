<?php
return [
    'google_api_key' => 'AIzaSyBoH3g7uvLUAYJWdu59miVi_QtAin271KU',
    'CATEGORY_LAYER_MAX' => 2,

    'user_status' => [
        0 => '仮登録',
        1 => 'メール確認',
        2 => '登録済',
    ],

    'user_status_code' => [
        'temp'      =>  0,
        'verified'  =>  1,
        'registered' => 2,
    ],

    'gender' => [
        '1' => '男',
        '2' => '女',
        '3' => 'その他(法人の場合)'
    ],

    'gender_index' => [
        '1' => 'male',
        '2' => 'female',
        '3' => 'other'
    ],

    'gender_code' => [
        'male' => 1,
        'female' => 2,
        'other' => 3
    ],

    'footer_menu_kind' => [
        1 => 'store',
        2 => 'ec',
        3 => 'favorite',
        4 => 'order'
    ],
    'footer_menu_kind_en' => [
        'store' => 1,
        'ec' => 2,
        'favorite' => 3,
        'order' => 4
    ],


    'store_status' => [
        0 => '仮登録',
        1 => 'メール確認',
        2 => '登録済',
    ],

    'store_status_code' => [
        'temp'      =>  0,
        'verified'  =>  1,
        'registered' => 2,
    ],

    'store_type' => [
        1 => '法人',
        2 => '個人',
    ],

    'store_login_type' => [
        1 => '法人として登録',
        2 => '個人として登録',
    ],

    'product_type' => [
        1 => '店頭商品',
        2 => '通販商品'
    ],
    'product_type_code' => [
        'fix' => 1,
        'ec' => 2
    ],

    'restaurant_kind' => [
        1 => 'コース料理',
        2 => '席だけ予約'
    ],
    'restaurant_kind_code' => [
        'course' => 1,
        'seat' => 2
    ],

    'order_type_code' => [
        'fix' => 1,
        'ec' => 2,
    ],

    'image_kind_code' => [
        'main' => 1,
        'sub' => 2
    ],

    'discount_type'=>[
        'percent'=>'1',
        'amount'=>'2'
    ],

    'imasugu_type'=>[
        '1'=>'受け取る',
        '0'=>'受け取らない',
    ],

    'cart_status_code' => [
        'active' => 0,
        'close' => 1
    ],

    'f_order_code' => 'OF',
    'e_order_code' => 'OE',

    'f_order_product_status' => [
        0 => '受付待ち',
        1 => '受付済み'
    ],

    'e_order_product_status' => [
        0 => '発送待ち',
        1 => '発送完了'
    ],

    'order_product_status_code' => [
        'temp' => 0,
        'complete' => 1
    ],

    'order_status'=>[
        0 => '注文前',
        1 => '決済失敗',
        2 => '注文済',
        3 => '発送中',
        4 => '完了',
    ],

    'order_status_color'=>[
        1 => '#FF6633',
        2 => '#FF66FF',
        3 => '#FFFF66',
        4 => '#00EE33',
    ],

    'order_status_admin'=>[
        1 => '決済失敗',
        2 => '決済成功',
        3 => '処理中',
        4 => '完了',
    ],
    'order_status_code'=>[
        'temp'=> 0,
        'pay_failed'=> 1,
        'ordered'=> 2, //paid
        'processing'=> 3,
        'completed'=> 4,
    ],

    //'tax_rate' => 0.1,

    'footer_store_menu_kind' => [
        1 => 'shop',
        2 => 'front',
        3 => 'ec',
        4 => 'history'
    ],

    'footer_store_menu_kind_en' => [
        'shop' => 1,
        'fix' => 2,
        'ec' => 3,
        'history' => 4
    ],

    'account_type' => [
        1 => '当座',
        2 => '普通',
    ],

    'bill_product_type' => [
        'transfer_applied' => 0,
        'transfer_completed' => 1,
    ]

];
