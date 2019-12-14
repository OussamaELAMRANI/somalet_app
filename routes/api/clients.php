<?php
Route::prefix('clients')->group(function () {

   Route::get('by', 'Api\ClientController@getClientBy');
   Route::get('search/{searchValue?}', 'Api\ClientController@search');
   Route::get('{id}/movements', 'Api\ClientController@getMovements');
   Route::get('{id}/sold', 'Api\ClientController@getClientSold');
   Route::get('', 'Api\ClientController@index');
   Route::post('', 'Api\ClientController@store');
   Route::get('{id}', 'Api\ClientController@show');

   Route::post('{client}/discount', 'Api\ClientController@storeDiscount');
   Route::delete('{id}/delete', 'Api\ClientController@destroy');

});
