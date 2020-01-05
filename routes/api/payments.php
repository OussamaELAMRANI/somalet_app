<?php

Route::prefix('payments')->group(function () {

   Route::get('', 'Api\PaymentController@getPayments');
   Route::get('checkout', 'Api\PaymentController@checkout');
   Route::get('cheques', 'Api\PaymentController@cheques');
   Route::get('valid-invalid', 'Api\PaymentController@getDiffPayment');
   Route::get('invalid', 'Api\PaymentController@getInvalidPayments');
   Route::get('outstanding', 'Api\PaymentController@getOutstandingPayments');
   Route::get('{impayed_id}/isoutstanding', 'Api\PaymentController@isOutstandingPayments');
   Route::get('{id}', 'Api\PaymentController@show');

   Route::post('validate', 'Api\PaymentController@validatePayments');
   Route::post('transfer', 'Api\PaymentController@transfer');
   Route::post('operation', 'Api\PaymentController@operation');
   Route::post('{cmd}', 'Api\PaymentController@store');

   Route::put('transfer-to/{bank_id}', 'Api\PaymentController@transferToBank');
   Route::put('{id}/impaye', 'Api\PaymentController@makeImpaye');
   Route::put('{payment}/deadline', 'Api\PaymentController@updateDeadline');
   Route::put('{payment}/adjust', 'Api\PaymentController@adjustPayment');

});

Route::prefix('payments-type')->group(function () {

   Route::get('', 'PaymentTypeController@index');
   Route::post('', 'PaymentTypeController@store');

});
