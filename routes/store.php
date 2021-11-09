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
Route::get('/', 'Auth\LoginController@showLoginForm')->name('store.login.form');

//会員ログイン
Route::group(['prefix' => 'login'], function () {
    Route::get('/', 'Auth\LoginController@loginBefore')->name('store.login.before');
    Route::post('/', 'Auth\LoginController@login')->name('store.login');
});

//会員登録
Route::group(['prefix' => 'register'], function () {
    Route::get('/', 'Auth\RegisterController@showRegisterForm')->name('store.register.form');
    Route::post('/', 'Auth\RegisterController@register')->name('store.register');
    Route::get('/sent_mail', 'Auth\RegisterController@sentMail')->name('store.register.sent_mail');
    Route::get('/user_info', 'Auth\RegisterController@showUserForm')->name('store.register.user_info');
    Route::post('/set_store/{store}', 'Auth\RegisterController@registerStoreData')->name('store.register.set_store');
    Route::get('/complete', 'Auth\RegisterController@complete')->name('store.register.complete');
});

Route::group(['prefix' => 'password'], function () {
    Route::get('/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('store.password.change');
    Route::post('/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('store.password.email');
    Route::get('/sent_mail', 'Auth\ForgotPasswordController@sentMail')->name('store.password.sent_mail');

    Route::get('/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('store.password.reset');
    Route::post('/reset', 'Auth\ResetPasswordController@reset')->name('store.password.update');

    Route::get('/completed', 'Auth\ResetPasswordController@completed')->name('store.password.reset.completed');
});

Route::group(['middleware' => 'auth:store'], function () {

    Route::post('logout', 'Auth\LoginController@logout')->name('store.logout');

    //店舗
    Route::group(['prefix' => 'my_account'], function () {
        Route::get('/edit', 'MyAccountController@edit')->name('store.my_account.edit');
        Route::get('/info', 'MyAccountController@info')->name('store.my_account.info');
        Route::put('/update', 'MyAccountController@update')->name('store.my_account.update');
        Route::post('/add_img', 'MyAccountController@addImg')->name('store.my_account.add_img');
        Route::post('/delete_img', 'MyAccountController@deleteImg')->name('store.my_account.delete_img');
    });

    //商品
    Route::group(['prefix' => 'fix_products'], function () {
        Route::get('/create/{product_type}', 'ProductController@create')->name('store.fix_products.create');
        Route::get('/{product_type}', 'ProductController@index')->name('store.fix_products.index');
        Route::put('/store', 'ProductController@store')->name('store.fix_products.store');
        Route::put('/update/{product}', 'ProductController@update')->name('store.fix_products.update');
        Route::get('/edit/{product}/{product_type}', 'ProductController@edit')->name('store.fix_products.edit');
        Route::post('/add_img', 'ProductController@addImg')->name('store.fix_products.add_img');
        Route::post('/delete_img', 'ProductController@deleteImg')->name('store.fix_products.delete_img');
    });

    Route::group(['prefix' => 'ec_products'], function () {
        Route::get('/create/{product_type}', 'ProductController@create')->name('store.ec_products.create');
        Route::get('/{product_type}', 'ProductController@index')->name('store.ec_products.index');
        Route::put('/store', 'ProductController@store')->name('store.ec_products.store');
        Route::put('/update/{product}', 'ProductController@update')->name('store.ec_products.update');
        Route::get('/edit/{product}/{product_type}', 'ProductController@edit')->name('store.ec_products.edit');
        Route::post('/add_img', 'ProductController@addImg')->name('store.ec_products.add_img');
        Route::post('/delete_img', 'ProductController@deleteImg')->name('store.ec_products.delete_img');
    });

    Route::group(['prefix' => 'history'], function () {
        Route::get('/fix', 'HistoryController@fix')->name('store.history.fix');
        Route::get('/ec', 'HistoryController@ec')->name('store.history.ec');
        Route::post('/set_complete/{order_product}', 'HistoryController@setComplete')->name('store.history.set_complete');
    });

    Route::group(['prefix' => 'my_menu'], function () {
        Route::get('/', 'MyMenuController@index')->name('store.my_menu.index');
        Route::get('/transfer_history', 'MyMenuController@transferHistory')->name('store.my_menu.transfer_history');
        Route::get('/select_transfer_history', 'MyMenuController@selectTransferHistory')->name('store.my_menu.select_transfer_history');
        Route::get('/info', 'MyMenuController@info')->name('store.my_menu.info');
        Route::get('/mail_edit', 'MyMenuController@mailEdit')->name('store.my_menu.mail_edit');
        Route::put('/mail_update', 'MyMenuController@mailUpdate')->name('store.my_menu.mail_update');
        Route::get('/password_edit', 'MyMenuController@passwordEdit')->name('store.my_menu.password_edit');
        Route::put('/password_update', 'MyMenuController@passwordUpdate')->name('store.my_menu.password_update');
        Route::get('/bank_transfer_info', 'MyMenuController@bankTransferInfo')->name('store.my_menu.bank_transfer_info');
        Route::post('/bank_transfer_update', 'MyMenuController@bankTransferUpdate')->name('store.my_menu.bank_transfer_update');
        Route::post('/get_bank_branch', 'MyMenuController@getBankBranch')->name('store.my_menu.get_bank_branch');

        Route::get('/flow_of_use', 'MyMenuController@flowOfUse')->name('store.my_menu.flow_of_use');
        Route::get('/fag', 'MyMenuController@fag')->name('store.my_menu.fag');
        Route::get('/terms_of_service', 'MyMenuController@termsOfService')->name('store.my_menu.terms_of_service');
        Route::get('/contact_us', 'MyMenuController@contactUs')->name('store.my_menu.contact_us');

    });

    Route::group(['prefix' => 'bill'], function () {
        Route::get('/total_sales', 'BillController@totalSales')->name('store.bill.total_sales');
        Route::get('/select_sale', 'BillController@selectBill')->name('store.bill.select_sale');
        Route::post('/order_deposite', 'BillController@orderDeposite')->name('store.bill.order_deposite');
        Route::post('/order_deposite_confirm', 'BillController@orderDepositeConfirm')->name('store.bill.order_deposite_confirm');
    });

});
