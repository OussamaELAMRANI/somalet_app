<?php

Route::prefix('products')->group(function () {

   Route::get('sizes', 'SizesController@index');
   Route::get('distinct', 'Api\ProductController@distinctProductByName');
   Route::get('search/{searchValue?}', 'Api\ProductController@search');

   Route::get('{name}/search', 'Api\ProductController@searchProductByName')
      ->where('name', '[\/\w\.-]*');

   Route::get('searchBy/{name?}', 'Api\ProductController@searchProductByName')
      ->where('name', '[\/\w\.-]*');

   Route::get('', 'Api\ProductController@index');
   Route::get('{id}', 'Api\ProductController@show');

   Route::put('{product}/price', 'Api\ProductController@setPrice');
   Route::post('new', 'Api\ProductController@newProducts');
   Route::delete('{id}/delete', 'Api\ProductController@destroy');
});
