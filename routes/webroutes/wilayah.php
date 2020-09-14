<?php
Route::group(['middleware'=>'auth'], function () {
    Route::group(['prefix'=>'wilayah'], function () {
        Route::get('akses', 'WilayahController@akses');
        Route::post('update', 'WilayahController@updateAkses');
        Route::get('provinsi/{id}', 'WilayahController@provinsi');
        Route::get('kotakab/{id}', 'WilayahController@kotakab');
        Route::get('kecamatan/{id}', 'WilayahController@kecamatan');
        Route::get('kelurahan/{id}', 'WilayahController@kelurahan');
        
        Route::get('kelurahanbyid/{ids}', 'WilayahController@getDataKelurahans');
        
        Route::get('rw/{id}', 'WilayahController@rw');
        
        Route::get('getrw/{id}/{ids}', 'WilayahController@getDataRwby');
        
        Route::get('rws', 'WilayahController@rws');
        Route::get('rt/{id}', 'WilayahController@rt');
        Route::get('rts', 'WilayahController@rts');
        Route::get('/', 'WilayahController@index');
        Route::get('getDataProvinsi', 'WilayahController@getDataProvinsi');
        Route::get('getDataKabupaten/{id}', 'WilayahController@getDataKabupaten');
        Route::get('getDataKecamatan/{id}', 'WilayahController@getDataKecamatan');
        Route::get('getDataKelurahan/{id}', 'WilayahController@getDataKelurahan');
        Route::get('getDataRw/{id}', 'WilayahController@getDataRw');
        Route::get('getDataRt/{id}', 'WilayahController@getDataRt');
        Route::get('AddPostRW', 'WilayahController@AddPostRW');
        
        Route::get('AddPostRT', 'WilayahController@AddPostRT');
        Route::get('DeleteRT', 'WilayahController@DeletePostRT');
        Route::get('DeleteRW', 'WilayahController@DeletePostRW');

        Route::get('EditPostRW', 'WilayahController@EditPostRW');
        Route::get('EditPostRT', 'WilayahController@EditPostRT');
        Route::get('UbahWilayahParent/{id}/{key}/{kondisi}', 'WilayahController@UbahWilayahParent');
        Route::get('mkecamatan', 'WilayahController@mkecamatan');
        Route::get('target', 'WilayahController@target');
        Route::get('treetarget', 'WilayahController@treeTarget');
        Route::post('updatetarget', 'WilayahController@updateTarget');


        Route::get('cekDobelRT', 'WilayahController@cekDobelKodeRT');
        Route::get('cekDobelRW', 'WilayahController@cekDobelKodeRW');

        Route::get('avprovinsi/{parentid?}', 'WilayahController@avprovinsi');
        Route::get('avkabupaten/{parentid?}', 'WilayahController@avkabupaten');
        Route::get('avkecamatan/{parentid?}', 'WilayahController@avkecamatan');
        Route::get('avkelurahan/{parentid?}', 'WilayahController@avkelurahan');
    });
});
