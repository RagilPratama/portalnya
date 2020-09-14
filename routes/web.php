<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@home');
Route::get('/home/datasensus', 'HomeController@datasensus');
Route::get('/home/dailysumdata', 'HomeController@dailysumdata');
Route::get('/home/statussensus', 'HomeController@statussensus');
Route::get('/home/statpendata', 'HomeController@statPendata');
Route::get('/home/chart1', 'HomeController@chart1');
Route::get('/home/chart2', 'HomeController@chart2');
Route::get('/home/chart3', 'HomeController@chart3');
Route::get('/home/dailysumdataTable', 'HomeController@dailysumdataTable');
Route::get('/invalidprofile', function () {
    $field = session()->get('field');
    return view('invalidprofile')->with(compact('field'));
});
if (is_dir(__DIR__.'/'.'webroutes')) {
    foreach (new DirectoryIterator(__DIR__.'/'.'webroutes') as $file) {
        if (!$file->isDot() && !$file->isDir() && $file->getFilename() != '.gitignore') {
            require_once __DIR__.'/'.'webroutes'.'/'.$file->getFilename();
        }
    }
}

Route::get('/sub/{method?}', 'SandboxController@sub');