<?php


/**
 * we have just a single route which send from our Server
 * (with no AJAX calls)
 */
Route::get('/', function () {
    return view('index');
});

//  Used for managing the Vue-router
Route::get('/{vue_routes?}', function () {
    return view('index');
})->where('vue_routes', '[\/\w\.-]*');
