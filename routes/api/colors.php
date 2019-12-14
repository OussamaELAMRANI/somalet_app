<?php

Route::prefix('colors')->group(function () {
   Route::get('', 'Api\ColorController@index');
   Route::post('', 'Api\ColorController@store');
});
