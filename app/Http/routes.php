<?php

/*=========== Static pages ===========*/
Route::get('/', 'StaticController@home');
Route::get('home', 'StaticController@home');

Route::get('contact', 'StaticController@contact');
Route::get('about', 'StaticController@about');

/*=========== Reports ===========*/
Route::get('reports', 'ReportsController@index');
Route::get('history', 'ReportsController@history');

//Creating and updating reports.
Route::post('reports/update', 'ReportsController@update');
Route::get('reports/create', 'ReportsController@create');
Route::post('reports', 'ReportsController@store');
//Temp route:
Route::post('html/submit/submit.php', 'ReportsController@store');


//Returning JSON for the profiles.
Route::get('reports/profiles.json', 'ReportsController@getJson');
//Temp route:
Route::get('getJSON.php', 'ReportsController@getJson');




/*=========== Appeals ===========*/
Route::get('appeal', 'AppealController@index');
Route::post('appeal', 'AppealController@store');
Route::post('appeal/{id}', 'AppealController@update');
Route::get('appeal/{id}', 'AppealController@show');

/*=========== Dev Routes ===========*/
Route::get('dev/checkCache', 'ReportsController@checkCache');

Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
