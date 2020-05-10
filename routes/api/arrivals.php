<?php

Route::prefix('arrivals')->group(function () {

   Route::get('', 'Api\ArrivalController@index');
   Route::get('facture', 'Api\ArrivalController@getArrivalByFactureNumber');
   Route::get('{id}', 'Api\ArrivalController@show');
   Route::get('users', 'Api\AuthController@allUsers');

   Route::post('products', 'Api\ArrivalController@products');

   Route::group(['middleware' => 'auth:api'], function () {
      Route::post('', 'Api\ArrivalController@store');
      Route::put('', 'Api\ArrivalController@update');
      Route::put('{arrival_id}/valid-reception', 'Api\ArrivalController@validateReception');
      Route::put('state/{arr}', 'Api\ArrivalController@state');

      Route::delete('{arrival_id}/delete', 'Api\ArrivalController@destroy');

   });
});
