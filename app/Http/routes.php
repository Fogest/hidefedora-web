<?php

Route::get('/', 'StaticController@home');
Route::get('home', 'StaticController@home');

Route::get('contact', 'StaticController@contact');
Route::get('about', 'StaticController@about');

Route::get('reports', 'ReportController@reports');

Route::resource('users', 'UsersController');
Route::resource('reports', 'ReportsController');
Route::resource('reportslog', 'ReportsLogController');
