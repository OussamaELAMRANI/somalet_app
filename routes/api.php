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
   Route::delete('user/{id}/delete', 'Api\AuthController@destroy');
   Route::post('user/{id}', 'Api\AuthController@update');


   // User Should be connected !
   Route::group(['middleware' => 'auth:api'], function () {

      Route::get('/logout', 'Api\AuthController@logout');
      Route::get('/user', 'Api\AuthController@user');

   });

});

Route::prefix('providers')->group(function () {

//    Route::get('search', 'Api\ProviderController@search');
   Route::get('search/{searchValue?}', 'Api\ProviderController@search');

   Route::get('', 'Api\ProviderController@index');
   Route::post('', 'Api\ProviderController@store');
   Route::get('{id}', 'Api\ProviderController@show');
   Route::delete('{id}/delete', 'Api\ProviderController@destroy');

});
Route::prefix('clients')->group(function () {

   Route::get('search/{searchValue?}', 'Api\ClientController@search');
   Route::get('{id}/movements', 'Api\ClientController@getMovements');
   Route::get('{id}/sold', 'Api\ClientController@getClientSold');

   Route::post('{client}/discount', 'Api\ClientController@storeDiscount');

   Route::get('', 'Api\ClientController@index');
   Route::post('', 'Api\ClientController@store');
   Route::get('{id}', 'Api\ClientController@show');
   Route::delete('{id}/delete', 'Api\ClientController@destroy');

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
//        $rejectId = explode(',', $req->query('reject'));
//        $rejectId = $req->query('reject');
      $result = App\Product::where('name', 'like', "%{$query}%")->get();
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
   Route::get('distinct', 'Api\ProductController@distinctProductByName');

   Route::get('search/{searchValue?}', 'Api\ProductController@search');
   Route::post('new', 'Api\ProductController@newProducts');

   Route::get('', 'Api\ProductController@index');

   Route::get('{id}', 'Api\ProductController@show');

   Route::delete('{id}/delete', 'Api\ProductController@destroy');


});

Route::prefix('arrivals')->group(function () {
//    Route::group(['middleware' => 'auth:api'], function () {
   Route::get('', 'Api\ArrivalController@index');
   Route::get('facture', 'Api\ArrivalController@getArrivalByFactureNumber');

   Route::get('{id}', 'Api\ArrivalController@show');

   Route::group(['middleware' => 'auth:api'], function () {
      Route::post('', 'Api\ArrivalController@store');
      Route::put('', 'Api\ArrivalController@update');
      Route::put('{arrival_id}/valid-reception', 'Api\ArrivalController@validateReception');
      Route::put('state/{arr}', 'Api\ArrivalController@state');

   });

   Route::post('products', 'Api\ArrivalController@products');
//        Route::post('', 'Api\ArrivalController@user');
//    });
   Route::get('users', 'Api\AuthController@allUsers');
});

Route::prefix('containers')->group(function () {

   Route::group(['middleware' => 'auth:api'], function () {

      Route::post('', 'Api\ArrivalController@newContainer');
   });

});

Route::prefix('receptions')->group(function () {
   Route::get('', 'ReceptionController@index');
   Route::get('no-valid', 'ReceptionController@getNoValidArrivals');
   Route::get('inventories/{product?}', 'ReceptionController@getInventories');

   Route::get('search/{product?}', 'ReceptionController@searchProductToOrder');

   Route::post('validate', 'ReceptionController@getNoValidArrivals');
   Route::put('validate/{arr}', 'ReceptionController@validateArrival');

});

Route::prefix('orders')->group(function () {

   Route::get('', 'Api\OrderController@index');
   Route::get('cmd_number', 'Api\OrderController@getLastOrderNumber');
   Route::get('{id}', 'Api\OrderController@show');
   Route::post('', 'Api\OrderController@store');


});

Route::prefix('payments')->group(function () {

   Route::post('{cmd}', 'Api\PaymentController@store');

   Route::get('', 'Api\PaymentController@getPayments');
   Route::get('checkout', 'Api\PaymentController@checkout');
   Route::get('cheques', 'Api\PaymentController@cheques');

});

Route::prefix('dashboard')->group(function () {
   Route::get('users', 'Api\AuthController@allUsers');
});

Route::prefix('inventories')->group(function () {

   Route::get('detail', 'ReceptionController@getDetailStock');
   Route::get('search/{search_ref?}', 'ReceptionController@search');
   Route::patch('{id}/validate', 'ReceptionController@validContainer');
   Route::patch('{id}/validate-all', 'ReceptionController@validAllInContainer');
   Route::get('no-valid', 'ReceptionController@notYetValid');
   Route::get('valid-history', 'ReceptionController@validContainersHistory');

});

Route::prefix('banks')->group(function () {
   Route::get('', 'Api\BankController@index');
   Route::get('{name}', 'Api\BankController@show');
});
