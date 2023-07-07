<?php

use Modules\BulkPrint\Http\Controllers\BulkPrintController;

Route::group(['middleware' => ['subdomain']], function () {
    Route::prefix('bulkprint')->group(function () {
        //fees bulk print 

        Route::get('student-id-card-bulk-print', ['as' => 'student-id-card-bulk-print', 'uses' => 'BulkPrintController@studentidBulkPrint'])->middleware('userRolePermission:student-id-card-bulk-print');
        Route::post('student-id-card-bulk-print', ['as' => 'student-id-card-bulk-print-search', 'uses' => 'BulkPrintController@studentidBulkPrintSearch'])->middleware('userRolePermission:student-id-card-bulk-print');
        Route::get('staff-id-card-bulk-print', ['as' => 'staff-id-card-bulk-print', 'uses' => 'BulkPrintController@staffidBulkPrint'])->middleware('userRolePermission:staff-id-card-bulk-print');
        Route::post('staff-id-card-bulk-print', ['as' => 'staff-id-card-bulk-print-search', 'uses' => 'BulkPrintController@staffidBulkPrintSearch'])->middleware('userRolePermission:staff-id-card-bulk-print');

        Route::get('fees-bulk-print', ['as' => 'fees-bulk-print', 'uses' => 'BulkPrintController@feeVoucherPrint'])->middleware('userRolePermission:fees-bulk-print');
        Route::post('fees-bulk-print', ['as' => 'fees-bulk-print-search', 'uses' => 'BulkPrintController@feeVoucherPrintSearch'])->middleware('userRolePermission:fees-bulk-print');

        Route::get('invoice-settings', ['as' => 'invoice-settings', 'uses' => 'BulkPrintController@settings'])->middleware('userRolePermission:invoice-settings');
        Route::post('invoice-settings-update', ['as' => 'invoice-settings-update', 'uses' => 'BulkPrintController@settingsUpdate'])->middleware('userRolePermission:invoice-settings');
        Route::get('payroll-bulk-print', ['as' => 'payroll-bulk-print', 'uses' => 'BulkPrintController@payrollBulkPrint'])->middleware('userRolePermission:payroll-bulk-print');
        Route::post('payroll-bulk-print', ['as' => 'payroll-bulk-print-seacrh', 'uses' => 'BulkPrintController@payrollBulkPrintSearch'])->middleware('userRolePermission:payroll-bulk-print');
        Route::get('certificate-bulk-print', ['as' => 'certificate-bulk-print', 'uses' => 'BulkPrintController@certificateBulkPrint'])->middleware('userRolePermission:certificate-bulk-print');
        Route::post('certificate-bulk-print', ['as' => 'certificate-bulk-print-seacrh', 'uses' => 'BulkPrintController@certificateBulkPrintSearch'])->middleware('userRolePermission:certificate-bulk-print');
        Route::get('lms-certificate-bulk-print', ['as' => 'lms-certificate-bulk-print', 'uses' => 'BulkPrintController@lmsCertificateBulkPrint']);
        Route::post('lms-certificate-bulk-print-seacrh', ['as' => 'lms-certificate-bulk-print-seacrh', 'uses' => 'BulkPrintController@lmsCertificateBulkPrintSeacrh']);
        Route::get('ajaxIdCard', ['as' => 'ajaxIdCard', 'uses' => 'BulkPrintController@ajaxIdCard']);
        Route::get('ajaxRoleIdCard', ['as' => 'ajaxRoleIdCard', 'uses' => 'BulkPrintController@ajaxRoleIdCard']);

        Route::get('bulk-generate-certificate-print/{user_id}/{certificate_id}', ['as' => 'bulk-s-certificate-print', 'uses' => 'BulkPrintController@bulkGenerateCertificatePrint']);

        Route::get('fees-invoice-bulk-print', ['as' => 'fees-invoice-bulk-print', 'uses' => 'BulkPrintController@feesInvoiceBulkPrint'])->middleware('userRolePermission:fees-invoice-bulk-print');
        Route::post('fees-invoice-bulk-print-search', ['as' => 'fees-invoice-bulk-print-search', 'uses' => 'BulkPrintController@feesInvoiceBulkPrintSearch']);
        Route::get('fees-invoice-bulk-print-settings', ['as' => 'fees-invoice-bulk-print-settings', 'uses' => 'BulkPrintController@feesInvoiceBulkPrintSettings']);
        Route::post('fees-invoice-settings-update', ['as' => 'fees-invoice-settings-update', 'uses' => 'BulkPrintController@feesInvoiceSettingsUpdate']);
    });

            
    Route::get('get-role-wise-certificate', ['as' => 'get-role-wise-certificate', 'uses' => 'BulkPrintController@getRoleWiseCertificate']);
    
});