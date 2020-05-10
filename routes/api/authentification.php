<?php
// url: /api/auth/
Route::prefix('auth')->group(function () {

   Route::post('sign-up', 'Api\AuthController@signUp');
   Route::post('login', 'Api\AuthController@logIn')->name('login');

   // User Should be connected !
   Route::group(['middleware' => 'auth:api'], function () {
      Route::get('/logout', 'Api\AuthController@logout');
      Route::get('/user', 'Api\AuthController@user');

      Route::delete('user/{id}/delete', 'Api\AuthController@destroy');
      Route::post('user/{id}', 'Api\AuthController@update');
      Route::get('allUsers', 'Api\AuthController@allUsers');
   });
});
