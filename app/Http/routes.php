<?php

Route::get('/', 'StaticController@home');
Route::get('home', 'StaticController@home');

Route::get('contact', 'StaticController@contact');
Route::get('about', 'StaticController@about');