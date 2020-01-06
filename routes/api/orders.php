<?php

Route::prefix('orders')->group(function () {

   Route::get('canceled', 'Api\OrderController@canceled');
   Route::get('cmd_number', 'Api\OrderController@getLastOrderNumber');
   Route::get('', 'Api\OrderController@index');
   Route::get('{id}', 'Api\OrderController@show');

   Route::post('annulate/{id}', 'Api\OrderController@canceledOrder');
   Route::post('validate-canceled/{id}', 'Api\OrderController@validateCanceledOrder');
   Route::post('', 'Api\OrderController@store');
});