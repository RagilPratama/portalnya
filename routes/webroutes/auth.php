<?php
Route::get('login', 'UserController@showLoginForm')->name('login');
Route::post('login', 'UserController@login');
Route::post('logout', 'UserController@logout')->name('logout');
Route::post('generateOTP', 'UserController@generateOTP')->name('generateOTP');
Route::post('checkMobileNumber/{username}', 'UserController@checkMobileNumber')->name('checkmobilenumber');
Route::put('forgotPassword/{id}', 'UserController@forgotPassword')->name('forgotPassword/{id}');
Route::post('verifikasiOTP/{id}', 'UserController@verifikasiOTP')->name('verifikasiOTP/{id}');
