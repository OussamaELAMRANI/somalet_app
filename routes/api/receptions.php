<?php

Route::prefix('receptions')->group(function () {

   Route::get('', 'ReceptionController@index');
   Route::get('search/{product?}', 'ReceptionController@searchProductToOrder');
   Route::get('no-valid', 'ReceptionController@getNoValidArrivals');
   Route::get('inventories/{product?}', 'ReceptionController@getInventories');

   Route::get('not-yet', 'ReceptionController@orderNotFinished');
   Route::get('production', 'ReceptionController@getProductionOrders');
   Route::get('production-list', 'ReceptionController@listProductionOrder');
   Route::get('{order}/detail', 'ReceptionController@getProductionDetail');


   Route::post('production', 'ReceptionController@storeProductionOrder');
   Route::post('validate', 'ReceptionController@getNoValidArrivals');
   Route::put('validate/{arr}', 'ReceptionController@validateArrival');
   Route::put('{order}/state', 'ReceptionController@changeOrderState');
   Route::put('{order}/validate-reception', 'ReceptionController@validReception');

});
