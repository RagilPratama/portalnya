<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'formulir'], function () {
        Route::get('demografi/{id}', 'Laporan\FormulirController@demografi');
        Route::get('kb1form/{idfrm?}', 'Laporan\FormulirController@kb1form');
        Route::get('pk01form/{idfrm?}', 'Laporan\FormulirController@pk01form');
        Route::get('pk02form/{idfrm?}', 'Laporan\FormulirController@pk02form');
    });
});
