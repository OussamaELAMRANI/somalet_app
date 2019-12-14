<?php
Route::prefix('unities')->group(function () {
   Route::get('', 'Api\UnityController@index');
   Route::post('', 'Api\UnityController@store');
});
