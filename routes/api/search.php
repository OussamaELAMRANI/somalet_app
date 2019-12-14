<?php

use Illuminate\Http\Request;

Route::prefix('search')->group(function () {

   Route::get('', function (Request $req) {
      $query = $req->query('provider');
      $result = \App\Provider::where('steName', 'like', "%{$query}%")->get();
      return response()->json($result);
   });

   Route::get('products', function (Request $req) {
      $query = $req->query('products');
//        $rejectId = explode(',', $req->query('reject'));
//        $rejectId = $req->query('reject');
      $result = App\Product::where('name', 'like', "%{$query}%")->get();
      return response()->json($result);
   });
});
