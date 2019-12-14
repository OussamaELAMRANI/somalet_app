<?php

use Illuminate\Http\Request;

$path = __DIR__ . "/api";
$api_resources = scandir($path);

foreach ($api_resources as $file) {
   if ($file == "." || $file == "..") {
      continue;
   }
   include_once "{$path}/{$file}";
}


Route::prefix('dashboard')->group(function () {
   Route::get('users', 'Api\AuthController@allUsers');
});
