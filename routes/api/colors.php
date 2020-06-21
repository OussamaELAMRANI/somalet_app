<?php

Route::prefix('colors')->group(function () {
   Route::get('search/{name}', 'Api\ColorController@searchByName');
   Route::get('', 'Api\ColorController@index');
   Route::get('{color}', 'Api\ColorController@show');
   Route::put('{color}', 'Api\ColorController@edit');
   Route::post('', 'Api\ColorController@store');
});
