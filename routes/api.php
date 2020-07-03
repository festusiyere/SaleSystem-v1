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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => ['auth:api', 'json']], function () {

    Route::resource('product', 'ProductController');
    Route::resource('sale', 'SaleController');

    Route::resource('profile', 'ProfileController');

    Route::post('sale/reverse/{sale}', 'SaleController@reverseSale')->name('reverse');
    Route::post('sale/edit', 'SaleController@editSale')->name('editSale');

    Route::apiResource('products', 'ProductApiController');
    Route::apiResource('sales', 'SaleApiController');
    Route::get('user', 'Api\AuthController@user');

});

Route::post('register', 'Api\AuthController@register');

Route::post('login', 'Api\AuthController@login')->name('login');
Route::post('email', 'Api\AuthController@emailCheck');
Route::post('userLogout', 'Api\AuthController@userLogout');

