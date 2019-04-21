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
Route::prefix('auth')->group(function (){

    Route::post('sign-up','Api\AuthController@signUp');
    Route::post('login','Api\AuthController@logIn');


});
