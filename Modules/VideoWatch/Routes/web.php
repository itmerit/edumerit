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

Route::group(['middleware' => ['auth', 'subdomain'], 'prefix' => 'videowatch'], function () {
    Route::get('view/{id}', 'VideoWatchController@view');
    Route::get('trace', 'VideoWatchController@traceData');
    Route::get('view-log/{id}', 'VideoWatchController@watchLog');
});
