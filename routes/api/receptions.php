<?php

Route::prefix('receptions')->group(function () {

   Route::get('search/{product?}', 'ReceptionController@searchProductToOrder');
   Route::get('', 'ReceptionController@index');
   Route::get('no-valid', 'ReceptionController@getNoValidArrivals');
   Route::get('inventories/{product?}', 'ReceptionController@getInventories');


   Route::post('validate', 'ReceptionController@getNoValidArrivals');
   Route::put('validate/{arr}', 'ReceptionController@validateArrival');

});
