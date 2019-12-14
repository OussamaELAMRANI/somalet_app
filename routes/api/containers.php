<?php

Route::prefix('containers')->group(function () {

   Route::group(['middleware' => 'auth:api'], function () {
      Route::post('', 'Api\ArrivalController@newContainer');
   });

});
