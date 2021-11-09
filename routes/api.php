<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//会員ログイン
Route::group(['prefix' => 'login'], function () {
    Route::post('/', 'AuthController@login')->name('api.login');
    Route::get('/unauthorized', 'AuthController@unauthorized')->name('api.unauthorized');
}); 

//会員登録
Route::group(['prefix' => 'register'], function () {
    Route::post('/', 'AuthController@register')->name('api.register');
});

Route::middleware('auth:api')->group( function () {
    Route::get('logout', 'AuthController@logout');
    Route::post('set_firebase_token', 'AuthController@saveFirebaseToken');  
    Route::get('product_info/{store}/{product}', 'AuthController@productInfo');
});


