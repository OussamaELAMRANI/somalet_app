<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// url: /api/auth/
Route::prefix('auth')->group(function () {

    Route::post('sign-up', 'Api\AuthController@signUp');
    Route::post('login', 'Api\AuthController@logIn');

    // User Should be connected !
    Route::group(['middleware' => 'auth:api'], function () {

        Route::get('/logout', 'Api\AuthController@logout');
        Route::get('/user', 'Api\AuthController@user');

    });

});

Route::prefix('providers')->group(function () {

    Route::get('', 'Api\ProviderController@index');
    Route::post('', 'Api\ProviderController@store');

});