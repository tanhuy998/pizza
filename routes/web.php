<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

Route::middleware(['jwt-auth'])->prefix('admin')->group(function () {

    Route::prefix('category')->group(function() {

        Route::post('/create', 'CategoryController@Create');
        Route::get('/', 'CategoryController@Show');
    });
    //Route::post('/category/create', 'CategoryController@Create');

    Route::prefix('product')->group(function() {

        Route::Put('/update', 'ProductController@Update');
        Route::post('/create', 'ProductController@Create');
        Route::get('/', 'ProductController@Show');
        Route::Delete('/delete/{id}', 'ProductController@Delete');
    });

    Route::prefix('order')->group(function() {

        Route::get('/', 'OrderController@Show');
        Route::get('/{id}', 'OrderController@GetById');
    });

    Route::prefix('user')->group(function() {

        Route::get('/', 'UserController@Show');
        Route::get('/{id}', 'UserController@GetById');
        Route::post('/create', 'UserController@Create');
        Route::put('/update', 'UserController@Update');
        Route::put('/password', 'UserController@Password');
        Route::delete('/{$id}', 'UserController@Delete');
    });

    Route::prefix('role')->group(function() {

        Route::get('/', 'RoleController@Show');
    });

    Route::get('/test', function(Request $_request) {

        echo 1;
    })->name('test');
});
