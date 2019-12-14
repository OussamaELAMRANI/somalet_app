<?php

Route::prefix('providers')->group(function () {

   Route::get('search/{searchValue?}', 'Api\ProviderController@search');
   Route::get('', 'Api\ProviderController@index');
   Route::post('', 'Api\ProviderController@store');
   Route::get('{id}', 'Api\ProviderController@show');

   Route::delete('{id}/delete', 'Api\ProviderController@destroy');
});
