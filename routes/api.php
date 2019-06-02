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

// url: /api/auth/
Route::prefix('auth')->group(function () {

    Route::post('sign-up', 'Api\AuthController@signUp');
    Route::post('login', 'Api\AuthController@logIn');
    Route::get('allUsers', 'Api\AuthController@allUsers');


    // User Should be connected !
    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('/logout', 'Api\AuthController@logout');
        Route::get('/user', 'Api\AuthController@user');

    });

});

Route::prefix('providers')->group(function () {

    Route::get('', 'Api\ProviderController@index');
    Route::post('', 'Api\ProviderController@store');
    Route::get('{id}', 'Api\ProviderController@show');
    Route::delete('{id}/delete', 'Api\ProviderController@destroy');

});
//Categories
Route::prefix('categories')->group(function () {

    Route::get('', 'Api\CategoryController@index');
    Route::get('{id}', 'Api\CategoryController@show');
    Route::post('', 'Api\CategoryController@store');

});

Route::prefix('search')->group(function () {
    Route::get('', function (Request $req) {
        $query = $req->query('provider');
        $result = \App\Provider::where('steName', 'like', "%{$query}%")->get();
        return response()->json($result);
    });
    Route::get('products', function (Request $req) {
        $query = $req->query('products');
        $rejectId = explode(',', $req->query('reject'));
//        $rejectId = $req->query('reject');
        $result = \App\Product::where('reference', 'like', "%{$query}%")->whereNotIn('id', $rejectId)->get();
        return response()->json($result);
    });
});
Route::prefix('colors')->group(function () {
    Route::get('', 'Api\ColorController@index');
    Route::post('', 'Api\ColorController@store');
});

Route::prefix('unities')->group(function () {
    Route::get('', 'Api\UnityController@index');
    Route::post('', 'Api\UnityController@store');
});

Route::prefix('products')->group(function () {

    Route::get('', 'Api\ProductController@index');
    Route::get('{id}', 'Api\ProductController@show');
    Route::post('', 'Api\ProductController@store');
    Route::delete('{id}/delete', 'Api\ProductController@destroy');
});
Route::prefix('arrivals')->group(function () {
//    Route::group(['middleware' => 'auth:api'], function () {
    Route::get('', 'Api\ArrivalController@index');
    Route::get('{id}', 'Api\ArrivalController@show');
    Route::post('', 'Api\ArrivalController@store');

    Route::post('products', 'Api\ArrivalController@products');
//        Route::post('', 'Api\ArrivalController@user');
//    });
    Route::get('users', 'Api\AuthController@allUsers');
});
Route::prefix('dashboard')->group(function () {
    Route::get('users', 'Api\AuthController@allUsers');
});
