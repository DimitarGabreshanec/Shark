<?php

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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');

Route::post('login', 'Auth\LoginController@login');


Route::group(['middleware' => 'auth:admin'], function () {
    //default admin routing
    Route::get('/', 'DashboardController@index')->name('admin.dashboard.index');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');

});

Route::group(['prefix' => 'users'], function () {
    Route::get('/', 'UsersController@index')->name('admin.users.index');
    Route::get('create', 'UsersController@create')->name('admin.users.create');
    Route::get('show/{user}', 'UsersController@show')->name('admin.users.show');
    Route::post('store', 'UsersController@store')->name('admin.users.store');
    Route::get('edit/{user}', 'UsersController@edit')->name('admin.users.edit');
    Route::put('update/{user}', 'UsersController@update')->name('admin.users.update');
    Route::delete('delete/{user}', 'UsersController@destroy')->name('admin.users.destroy');
});

Route::group(['prefix' => 'stores'], function () {
    Route::get('/', 'StoreController@index')->name('admin.stores.index');
    Route::get('create', 'StoreController@create')->name('admin.stores.create');
    Route::get('show/{store}', 'StoreController@show')->name('admin.stores.show');
    Route::post('store', 'StoreController@store')->name('admin.stores.store');
    Route::get('edit/{store}', 'StoreController@edit')->name('admin.stores.edit');
    Route::put('update/{store}', 'StoreController@update')->name('admin.stores.update');
    Route::delete('delete/{store}', 'StoreController@destroy')->name('admin.stores.destroy');

    Route::post('upload_img', 'StoreController@uploadImage')->name('admin.stores.upload_img');
    Route::post('delete_img', 'StoreController@deleteImg')->name('admin.stores.delete_img');
});

Route::group(['prefix' => 'products'], function () {
    Route::get('/', 'ProductController@index')->name('admin.products.index');
    Route::get('create', 'ProductController@create')->name('admin.products.create');
    Route::get('show/{product}', 'ProductController@show')->name('admin.products.show');
    Route::post('product', 'ProductController@store')->name('admin.products.store');
    Route::get('edit/{product}', 'ProductController@edit')->name('admin.products.edit');
    Route::put('update/{product}', 'ProductController@update')->name('admin.products.update');
    Route::delete('delete/{product}', 'ProductController@destroy')->name('admin.products.destroy');

    Route::post('upload_img', 'ProductController@uploadImage')->name('admin.products.upload_img');
    Route::post('delete_img', 'ProductController@deleteImg')->name('admin.products.delete_img');
});

Route::group(['prefix' => 'category'], function () {
    Route::get('/', 'CategoryController@index')->name('admin.category.index');
    Route::post('/sequence_update', 'CategoryController@sequenceUpdate')->name('admin.category.sequence_update'); 
    Route::post('/add', 'CategoryController@add')->name('admin.category.add');
    Route::post('/delete', 'CategoryController@delete')->name('admin.category.delete');
    Route::post('/update', 'CategoryController@update')->name('admin.category.update');
});

//注文管理
Route::group(['prefix' => 'orders'], function () {
    Route::get('/', 'OrderController@index')->name('admin.orders.index');
    Route::get('/detail/{order}', 'OrderController@detail')->name('admin.orders.detail');
});

Route::group(['prefix' => 'configuration'], function () { 
    Route::get('/edit', 'ConfigurationController@edit')->name('admin.configuration.edit');
    Route::put('/update', 'ConfigurationController@update')->name('admin.configuration.update');
});

Route::group(['prefix' => 'sales'], function () {
    Route::get('/index', 'SalesContorller@index')->name('admin.sales.index');
    Route::get('/detail/{store_id}', 'SalesContorller@detail')->name('admin.sales.detail');
    Route::get('/select_date_sale', 'SalesContorller@selectDateSale')->name('admin.sales.select_date_sale');
    Route::get('/search_index_sales', 'SalesContorller@searchIndexSales')->name('admin.sales.search_index_sales');  
    Route::get('/completed/{bill_product}', 'SalesContorller@completed')->name('admin.sales.completed'); 

    Route::get('/transfer', 'SalesContorller@transfer')->name('admin.sales.transfer');
    Route::get('/search_index_transfer', 'SalesContorller@searchIndexTransfer')->name('admin.sales.search_index_transfer'); 

});
 
