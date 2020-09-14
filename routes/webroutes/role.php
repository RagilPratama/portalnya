<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'role'], function () {
        Route::get('datapaging', 'RoleController@dataPaging');
        Route::get('datalist', 'RoleController@dataList');
        Route::get('menu', 'RoleController@menu');
        Route::post('{id}/menu', 'RoleController@updateMenu');
    });
    Route::resource('role', 'RoleController')->except(['create', 'edit', 'show']);
});