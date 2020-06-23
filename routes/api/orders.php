<?php

Route::prefix('orders')->group(function () {

   Route::prefix('productions')->group(function () {

      Route::get('', 'Api\OrderController@getAllProductionCommand');
      Route::post('', 'Api\OrderController@addProductionCommand');

   });


   Route::get('canceled', 'Api\OrderController@canceled');
   Route::get('cmd_number', 'Api\OrderController@getLastOrderNumber');
   Route::get('', 'Api\OrderController@index');
   Route::get('{order}', 'Api\OrderController@show');
   Route::get('{id}/print', 'Api\OrderController@toPrint');

   Route::post('annulate/{order}', 'Api\OrderController@canceledOrder');
   Route::post('validate-canceled/{order}', 'Api\OrderController@validateCanceledOrder');
   Route::post('', 'Api\OrderController@store');

   Route::delete('{order}/delete', 'Api\OrderController@destroy');
});
