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

Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('studentabsentnotification')->group(function () {
        Route::get('/', 'StudentAbsentNotificationController@index')->name('notification_time_setup');
        Route::post('/', 'StudentAbsentNotificationController@store')->name('absent_time_setup');
        Route::get('edit/{id}', 'StudentAbsentNotificationController@edit')->name('absent_time_edit');
        Route::get('delete/{id}', 'StudentAbsentNotificationController@delete')->name('time_setup_delete');
        Route::post('update', 'StudentAbsentNotificationController@update')->name('absent_time_setup_update');
    });
});

