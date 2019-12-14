<?php

Route::prefix('products')->group(function () {

   Route::get('sizes', 'SizesController@index');
   Route::get('distinct', 'Api\ProductController@distinctProductByName');
   Route::get('search/{searchValue?}', 'Api\ProductController@search');
   Route::get('', 'Api\ProductController@index');
   Route::get('{id}', 'Api\ProductController@show');

   Route::post('new', 'Api\ProductController@newProducts');
   Route::delete('{id}/delete', 'Api\ProductController@destroy');
});
