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

// Route::get('/', function () {
//     return "Hello welcome to my application";
// });

Route::view('/', 'home');
Route::view('sale', 'sales');

// Route::resource('sale', 'SaleController');
Auth::routes();
