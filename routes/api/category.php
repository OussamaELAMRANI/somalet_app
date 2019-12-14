<?php

Route::prefix('categories')->group(function () {

   Route::get('', 'Api\CategoryController@index');
   Route::get('{id}', 'Api\CategoryController@show');
   Route::post('', 'Api\CategoryController@store');

});
