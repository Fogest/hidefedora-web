<?php

Route::get('/', 'StaticController@home');
Route::get('home', 'StaticController@home');

Route::get('contact', 'StaticController@contact');
Route::get('about', 'StaticController@about');

Route::get('reports', 'ReportsController@index');
Route::get('history', 'ReportsController@history');

Route::post('reports/update', 'ReportsController@update');
Route::get('reports/create', 'ReportsController@create');

Route::get('reports/profiles.json', 'ReportsController@getJson');
//Temp route:
Route::get('getJSON.php', 'ReportsController@getJson');
Route::post('reports', 'ReportsController@store');
//Temp route:
Route::post('html/submit/submit.php', 'ReportsController@store');

//Route::resource('users', 'UsersController');
//Route::resource('reports', 'ReportsController');
//Route::resource('reportslog', 'ReportsLogController');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
