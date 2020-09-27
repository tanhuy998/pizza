<?php

use Illuminate\Support\Facades\Route;
use App\Product as Product;

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

Route::middleware([])->prefix('admin')->group(function () {

    Route::post('/category/create', 'CategoryController@Create');
    //Route::post('/category/create', 'CategoryController@Create');

});
