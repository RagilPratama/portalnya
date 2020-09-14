<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'menu'], function () {
        Route::get('/datalist', 'MenuController@dataList');
        Route::get('role/{id}', 'MenuController@role');
    });
    Route::resource('menu', 'MenuController')->except(['create', 'edit']);
});