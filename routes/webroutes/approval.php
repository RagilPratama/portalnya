<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'approval'], function () {
        Route::get('kecamatan', 'ApprovalController@kecamatanIndex');
        Route::get('provinsi', 'ApprovalController@provinsiIndex');
        
        Route::get('kecamatan/data', 'ApprovalController@kecamatanData');
        Route::get('provinsi/data', 'ApprovalController@provinsiData');
        
        Route::post('kecamatan/close', 'ApprovalController@kecamatanClose');
        Route::post('kecamatan/open', 'ApprovalController@kecamatanOpen');
        Route::post('kecamatan/close/target', 'ApprovalController@kecamatanCloseTarget');
        Route::post('kecamatan/open/target', 'ApprovalController@kecamatanOpenTarget');
        
        Route::post('provinsi/open/targets', 'ApprovalController@provinsiOpen');
        Route::post('provinsi/close/targets', 'ApprovalController@provinsiClose');
    });
});
