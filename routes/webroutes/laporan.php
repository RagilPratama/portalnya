<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'laporan'], function () {
        Route::get('targetactual', 'Laporan\TargetActualController@index');
        Route::get('targetactual/data', 'Laporan\TargetActualController@data');
        Route::get('targetactual/pendata', 'Laporan\TargetActualController@pendata');
        Route::get('statussensus', 'Laporan\StatusSensusController@index');
        Route::get('statussensus/data', 'Laporan\StatusSensusController@data');
        Route::put('statussensus/anulir/{id}', 'Laporan\StatusSensusController@anulir');
        
        Route::get('cetak', 'Laporan\AnomaliSensusController@cetak');
        Route::get('validme', 'Laporan\AnomaliSensusController@validme');
        Route::get('validmefirst', 'Laporan\AnomaliSensusController@validmefirst');
        Route::get('anomalisensus', 'Laporan\AnomaliSensusController@index');
        
        Route::get('anomalisensus/data', 'Laporan\AnomaliSensusController@data');
        Route::get('data_pdf', 'Laporan\AnomaliSensusController@data_pdf');
        


        Route::get('anomalisensus/indikator/{id}', 'Laporan\AnomaliSensusController@indikator');
        Route::get('anomalisensus/datapaging', 'Laporan\AnomaliSensusController@dataPaging');
        Route::get('summary', 'Laporan\SummaryController@index');
        Route::get('summary/data', 'Laporan\SummaryController@data');

        Route::get('rekapitulasi', 'Laporan\RekapitulasiController@index');
        Route::get('rekapitulasi/data', 'Laporan\RekapitulasiController@data');
        Route::get('rekapitulasi/cetak', 'Laporan\RekapitulasiController@cetak');
        Route::get('approve', 'Laporan\RekapitulasiController@approve');
        
        Route::get('kelurahan/pic', 'Laporan\WilayahController@kelurahan');

        Route::get('summary/cekData', 'Laporan\SummaryController@cekData');
        
        Route::get('indikator', 'IndikatorController@monitoring');

        Route::get('pus', 'Laporan\PusController@index');
        Route::get('pus/data', 'Laporan\PusController@data');

    });
});
