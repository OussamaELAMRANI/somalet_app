<?php

Route::prefix('inventories')->group(function () {

   Route::get('detail', 'ReceptionController@getDetailStock');
   Route::get('alert', 'ReceptionController@getStockAlert');
   Route::get('search/{search_ref?}', 'ReceptionController@search')->where('search_ref', '[\/\w\.-]*');;
   Route::get('no-valid', 'ReceptionController@notYetValid');
   Route::get('valid-history', 'ReceptionController@validContainersHistory');

   Route::patch('{id}/validate-all', 'ReceptionController@validAllInContainer');
   Route::patch('{id}/validate', 'ReceptionController@validContainer');

});
