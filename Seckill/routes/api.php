<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// 商品写入缓存
Route::post('/product/create', 'ProductController@createCache');

// 订单
Route::get('/order/find', 'OrderController@find');
Route::post('/order/create', 'OrderController@create');
Route::get('/order/add', 'OrderController@add');
