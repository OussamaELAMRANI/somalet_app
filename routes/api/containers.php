<?php

Route::prefix('containers')->group(function () {

   Route::get('{container}/detail', 'Api\ArrivalController@getContainerDetail');
   Route::put('{container}/update', 'Api\ArrivalController@updateContainer');

   Route::group(['middleware' => 'auth:api'], function () {
      Route::post('', 'Api\ArrivalController@newContainer');
   });

});
