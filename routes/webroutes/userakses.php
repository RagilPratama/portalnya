<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'akses'], function () {
        Route::get('ddwilayah', 'UserAksesController@getDDWilayah');
        Route::get('path/{id}', 'UserAksesController@getPathUser');
        Route::post('update', 'UserAksesController@update');
    });
});


        