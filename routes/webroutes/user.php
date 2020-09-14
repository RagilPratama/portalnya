<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'user'], function () {
        Route::get('datapaging', 'UserController@dataPaging');
        Route::get('profile', 'UserController@profile');
        Route::post('updateprofile/{id}', 'UserController@updateProfile');
        Route::get('getrole', 'UserController@getRole');
        Route::get('wilayah/{roleid}', 'UserController@getTingkatWilayah');
        Route::put('resetPassword/{id}', 'UserController@resetPassword');
        Route::put('changepassword', 'UserController@changePassword');
        Route::get('import', 'UserController@importUser');
        Route::post('processimport', 'UserController@processImport');
        Route::post('processdata', 'UserController@processDataImport');        
        Route::get('getcities', 'UserController@getcities');        
        Route::get('datatable', 'UserController@datatable');    
        Route::get('create', 'UserController@create');        
    });
    Route::resource('user', 'UserController')->except(['create', 'edit', 'show']);
    ;
});
