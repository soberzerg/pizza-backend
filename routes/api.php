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


Route::group([
    'middleware' => ['cors'],
], function () {

    Route::resource('products', 'ProductsController')->only(['index']);

    Route::get('delivery-cost', function () {
        return response()->json(['cost' => rand(99,699) / 100]);
    });

    Route::group([
        'middleware' => ['auth:api'],
    ], function () {
        Route::resource('orders', 'OrdersController')->only(['index', 'store']);
    });
});
