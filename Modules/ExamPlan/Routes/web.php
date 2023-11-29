<?php

Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('examplan')->name('examplan.')->middleware('auth')->group(function () {
        Route::get('admitcard/setting', 'AdmitCardSettingController@setting')->name('admitcard.setting')->middleware('userRolePermission:examplan.admitcard.setting');
        Route::get('admitcard', 'AdmitCardSettingController@admitcard')->name('admitcard.index')->middleware('userRolePermission:examplan.admitcard.index');
        Route::post('admitcard', 'AdmitCardSettingController@admitcardSearch')->name('admitcard.search');
        Route::post('admitcard/generate', 'AdmitCardSettingController@admitcardGenerate')->name('admitcard.generate');
        Route::post('admitcard/settingUpdate', 'AdmitCardSettingController@settingUpdate')->name('admitcard.settingUpdate');
        Route::post('admitcard/settingUpdatetwo', 'AdmitCardSettingController@settingUpdate')->name('admitcard.settingUpdatetwo');
        Route::post('image/upload', 'AdmitCardSettingController@imageUpload')->name('image.upload');


        Route::get('changeAdmitCardLayout', 'AdmitCardSettingController@changeAdmitCardLayout')->name('changeAdmitCardLayout');

        Route::get('seatplan/setting', 'SeatPlanSettingController@setting')->name('seatplan.setting')->middleware('userRolePermission:examplan.seatplan.setting');
        Route::post('seatplan/settingUpdate', 'SeatPlanSettingController@settingUpdate')->name('seatplan.settingUpdate');
        Route::get('seatplan', 'SeatPlanSettingController@seatplan')->name('seatplan.index')->middleware('userRolePermission:examplan.seatplan.index');
        Route::post('seatplan/search', 'SeatPlanSettingController@seatplanSearch')->name('seatplan.search');
        Route::post('seatplan/generate', 'SeatPlanSettingController@seatplanGenerate')->name('seatplan.generate');

        Route::get('student-admit-card', 'StudentExamPlanController@admitCard')->name('admitCard');
        Route::post('student-admit-card-search', 'StudentExamPlanController@admitCardSearch')->name('admitCardSearch');
        Route::get('student-admit-card-download/{id}', 'StudentExamPlanController@admitCardDownload')->name('admitCardDownload');
        Route::get('student-admit-card/{student_id}', 'StudentExamPlanController@admitCardParent')->name('admitCardParent');

    });
});
