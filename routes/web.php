<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index')->name('home');
Route::get('/privacy', 'HomeController@privacy')->name('privacy');
Route::get('/term_use', 'HomeController@termAndUse')->name('term_use');
Route::get('/send_firebase_token', 'HomeController@sendFirebaseToken'); 

Route::get('/sp_login', 'Auth\LoginController@loginFromSP')->name('user.login.sp_login');
Route::get('/sp/login_after', 'SPController@spLoginAfter')->name('user.sp.login_after'); 

//会員ログイン
Route::group(['prefix' => 'login'], function () {
    //Facebook, Google Login
    Route::get('/social/{provider}', 'Auth\SocialController@redirectToProvider')->name('social.login');
    Route::get('/social/{provider}/callback', 'Auth\SocialController@handleProviderCallback');

    Route::get('/before', 'Auth\LoginController@loginBefore')->name('user.login.before');
    Route::get('/', 'Auth\LoginController@showLoginForm')->name('user.login.form');
    Route::post('/', 'Auth\LoginController@login')->name('user.login');

    //SP Login
});

//会員登録
Route::group(['prefix' => 'register'], function () {
    Route::get('/before', 'Auth\RegisterController@registerBefore')->name('user.register.before');
    Route::get('/', 'Auth\RegisterController@showRegisterForm')->name('user.register.form');
    Route::post('/', 'Auth\RegisterController@register')->name('user.register');
    Route::get('/sent_mail', 'Auth\RegisterController@sentMail')->name('user.register.sent_mail');
    Route::get('/user_info', 'Auth\RegisterController@showUserForm')->name('user.register.user_info');
    Route::post('/set_user/{user}', 'Auth\RegisterController@registerUserData')->name('user.register.set_user');
    Route::get('/complete', 'Auth\RegisterController@complete')->name('user.register.complete');
});

Route::group(['prefix' => 'password'], function () {
    Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('user.password.change');
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('user.password.email');
    Route::get('/sent_mail', 'Auth\ForgotPasswordController@sentMail')->name('user.password.sent_mail');

    Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('user.password.reset');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('user.password.update');

    Route::get('/completed', 'Auth\ResetPasswordController@completed')->name('user.password.reset.completed');
});

Route::group(['middleware' => 'auth:web'], function () {
    Route::post('logout', 'Auth\LoginController@logout')->name('user.logout');

    //店舗
    Route::group(['prefix' => 'shop'], function () {
        Route::get('/imasugu/{view_kind?}', 'StoresController@imasugu')->name('user.stores.imasugu');
        Route::get('/imasugu_search/{view_kind}', 'StoresController@imasuguSearch')->name('user.stores.imasugu_search');
        Route::get('/imasugu_cond/{back}', 'StoresController@imasuguCond')->name('user.stores.imasugu_cond');

        Route::get('/search', 'StoresController@search')->name('user.stores.search');
        Route::get('/search_cond', 'StoresController@searchCondition')->name('user.stores.search.condition');
        Route::get('/search_map', 'StoresController@searchMap')->name('user.stores.search.map');

        Route::get('/store_products/{store}/{product_type}', 'StoresController@storeProducts')->name('user.stores.store_products')->where(['product_type'=>'[1,2]']);
        Route::get('/store_info/{store}/{product_type}', 'StoresController@storeInfo')->name('user.stores.store_info')->where(['product_type'=>'[1,2]']);
        Route::get('/ec_list', 'StoresController@ecList')->name('user.stores.ec_list');
        Route::post('/toggle_favorite', 'StoresController@toggleFavorite')->name('user.stores.toggle_favorite');
        Route::get('/product_info/{store}/{product}', 'StoresController@productInfo')->name('user.stores.product_info');

    });

    //注文
    Route::group(['prefix' => 'order'], function () {
        //Cart(fix and ec product)
        Route::post('/set_f_products/{store}', 'OrderController@setFixProducts')->name('user.order.set_f_products');
        Route::get('/cart/detail/{order_type}', 'OrderController@cart')->name('user.order.cart')->where(['order_type'=>'[1,2]']);
        Route::post('/cart/add_product', 'OrderController@addCartProduct')->name('user.cart.add_product');
        Route::post('/cart/set_product', 'OrderController@setCartProduct')->name('user.cart.set_product');
        Route::post('/cart/product_count', 'OrderController@getCartProductCount')->name('user.cart.product_count');
        Route::get('/cart/remove_product/{order_type}/{product}', 'OrderController@removeCartProduct')->name('user.cart.remove_product')->where(['order_type'=>'[1,2]']);
        Route::get('/cart_confirm/{order_type}', 'OrderController@cartConfirm')->name('user.order.cart_confirm')->where(['order_type'=>'[1,2]']);

        //set credit card
        Route::get('/credit_card/{order_type}', 'OrderController@creditCard')->name('user.order.credit_card');
        Route::get('/credit_card_form/{order_type}', 'OrderController@creditCardForm')->name('user.order.credit_card_form');

        //set address
        Route::get('/address/{order_type}', 'OrderController@address')->name('user.order.address');
        Route::post('/set_address/{order_type}', 'OrderController@setAddress')->name('user.order.set_address');

        //Payment Fix Order
        Route::post('/set_order/{order_type}', 'OrderController@setOrder')->name('user.order.set_order');
        Route::get('/completed/{order_type}', 'OrderController@completed')->name('user.order.completed');

        Route::get('/cart_all', 'OrderController@cartAll')->name('user.order.cart_all');
        Route::get('/history/{product_type?}', 'OrderController@history')->name('user.order.history')->where(['product_type'=>'[1,2]']);
    });

    //注文
    Route::group(['prefix' => 'favorite'], function () {
        Route::get('/{product_type?}', 'FavoriteController@index')->name('user.favorite.index')->where(['product_type'=>'[1,2]']);
    });

    //マイアカウント
    Route::group(['prefix' => 'my_account'], function () {
        Route::get('/', 'MyAccountController@index')->name('user.my_account.index');
        Route::get('/info', 'MyAccountController@info')->name('user.my_account.info');
        Route::get('/edit', 'MyAccountController@edit')->name('user.my_account.edit');
        Route::get('/update', 'MyAccountController@update')->name('user.my_account.update');
        Route::get('/mail_edit', 'MyAccountController@mailEdit')->name('user.my_account.mail_edit');
        Route::put('/mail_update', 'MyAccountController@mailUpdate')->name('user.my_account.mail_update');
        Route::get('/password_edit', 'MyAccountController@passwordEdit')->name('user.my_account.password_edit');
        Route::put('/password_update', 'MyAccountController@passwordUpdate')->name('user.my_account.password_update');
        Route::get('/_edit', 'MyAccountController@_Edit')->name('user.my_account._edit');

        //クレジットカード設定
        Route::get('/card_form', 'MyAccountController@cardForm')->name('user.my_account.card_form');
        Route::get('/card_detail', 'MyAccountController@cardDetail')->name('user.my_account.card_detail');
        Route::post('/set_card', 'MyAccountController@setCard')->name('user.my_account.set_card');

        Route::get('/flow_of_use', 'MyAccountController@FlowOfUse')->name('user.my_account.flow_of_use');
        Route::get('/faq', 'MyAccountController@faq')->name('user.my_account.faq');
        Route::get('/terms_of_service', 'MyAccountController@termsOfService')->name('user.my_account.terms_of_service');
        Route::get('/privacy_police', 'MyAccountController@privacyPolice')->name('user.my_account.privacy_police');
        Route::get('/contact_us', 'MyAccountController@contactUs')->name('user.my_account.contact_us');
    });
});
