<?php
Route::prefix('banks')->group(function () {
   Route::get('', 'Api\BankController@index');
   Route::get('external', 'Api\BankController@externals');
   Route::get('internals', 'Api\BankController@internals');
   Route::get('{name}', 'Api\BankController@show');

   Route::post('{bank?}', 'Api\BankController@storeOn');
});
