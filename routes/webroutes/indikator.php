<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'indikator'], function () {
        Route::get('proses', 'IndikatorController@index');
        Route::get('data', 'IndikatorController@data');
        Route::post('update', 'IndikatorController@update');
        Route::get('mondata', 'IndikatorController@mondata');
        Route::get('mondatalatih', 'IndikatorController@mondatalatih');
        Route::get('mondatalengkap', 'IndikatorController@mondatalengkap');
    });
});
