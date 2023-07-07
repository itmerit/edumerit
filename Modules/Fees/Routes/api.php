<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Fees\Http\Controllers\api\AjaxController;
use Modules\Fees\Http\Controllers\api\FeesController;
use Modules\Fees\Http\Controllers\api\FeesReportController;
use Modules\Fees\Http\Controllers\api\StudentFeesController;

Route::middleware('auth:api')->get('/fees', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    Route::get('fees-group', [FeesController::class, 'feesGroup']);
    Route::post('fees-group-store', [FeesController::class, 'feesGroupStore']);
    Route::get('fees-group-edit/{id}', [FeesController::class, 'feesGroupEdit']);
    Route::post('fees-group-update', [FeesController::class, 'feesGroupUpdate']);
    Route::post('fees-group-delete', [FeesController::class, 'feesGroupDelete']);

    //Fees Type
    Route::get('fees-type', [FeesController::class, 'feesType']);
    Route::post('fees-type-store', [FeesController::class, 'feesTypeStore']);
    Route::get('fees-type-edit/{id}', [FeesController::class, 'feesTypeEdit']);
    Route::post('fees-type-update', [FeesController::class, 'feesTypeUpdate']);
    Route::post('fees-type-delete', [FeesController::class, 'feesTypeDelete']);
    
    //Fees invoice
    Route::get('fees-invoice-list', [FeesController::class, 'feesInvoiceList']);
    Route::get('fees-invoice', [FeesController::class, 'feesInvoice']);
    Route::post('fees-invoice-store', [FeesController::class, 'feesInvoiceStore']);
    Route::get('fees-invoice-edit/{id}', [FeesController::class, 'feesInvoiceEdit']);
    Route::post('fees-invoice-update', [FeesController::class, 'feesInvoiceUpdate']);
    Route::get('fees-invoice-view/{id}/{state}', [FeesController::class, 'feesInvoiceView']);
    Route::post('fees-invoice-delete', [FeesController::class, 'feesInvoiceDelete']);
    Route::get('add-fees-payment/{id}', [FeesController::class, 'addFeesPayment']);
    Route::post('fees-payment-store', [FeesController::class, 'feesPaymentStore']);
    Route::get('single-payment-view/{id}', [FeesController::class, 'singlePaymentView']);
    Route::get('delete-single-fees-transcation/{id}', [FeesController::class, 'deleteSingleFeesTranscation']);
    
    //Bank Payment
    Route::get('bank-payment', [FeesController::class, 'bankPayment']);
    Route::post('search-bank-payment', [FeesController::class, 'searchBankPayment']);
    Route::post('approve-bank-payment', [FeesController::class, 'approveBankPayment']);
    Route::post('reject-bank-payment', [FeesController::class, 'rejectBankPayment']);

    //Fees invoice Settings
    Route::get('fees-invoice-settings', [FeesController::class, 'feesInvoiceSettings']);
    Route::post('fees-invoice-settings-update', [FeesController::class, 'ajaxFeesInvoiceSettingsUpdate']);

    //Fees Report
    Route::get('due-fees', [FeesReportController::class, 'dueFeesView']);
    Route::post('search-due-fees', [FeesReportController::class, 'dueFeesSearch']);
    Route::get('fine-report', [FeesReportController::class, 'fineReportView']);
    Route::post('fine-search', [FeesReportController::class, 'fineReportSearch']);
    Route::get('payment-report', [FeesReportController::class, 'paymentReportView']);
    Route::post('payment-search', [FeesReportController::class, 'paymentReportSearch']);
    Route::get('balance-report', [FeesReportController::class, 'balanceReportView']);
    Route::post('balance-search', [FeesReportController::class, 'balanceReportSearch']);
    Route::get('waiver-report', [FeesReportController::class, 'waiverReportView']);
    Route::post('waiver-search', [FeesReportController::class, 'waiverReportSearch']);

    // Student
    Route::get('student-fees-list/{id}', [StudentFeesController::class, 'studentFeesList']);
    Route::get('student-record-fees-list/{id}/{record_id}', [StudentFeesController::class, 'studentRecordFeesList']);
    Route::get('student-fees-payment/{id}', [StudentFeesController::class, 'studentAddFeesPayment']);
    Route::post('student-fees-payment-store', [StudentFeesController::class, 'studentFeesPaymentStore']);

    //Api Payment SucessCallBack
    Route::get('online-payment-sucess/{type}/{transcationId}', [StudentFeesController::class, 'onlinePaymentSucess']);
    Route::get('user-wallet-balance/{user_id}', [StudentFeesController::class, 'walletBalance']);

    //Ajax Request
    Route::post('fees-view-payment', [AjaxController::class, 'feesViewPayment']);
    Route::get('select-student', [AjaxController::class, 'ajaxSelectStudent']);
    Route::post('select-fees-type', [AjaxController::class, 'ajaxSelectFeesType']);
    Route::get('ajax-get-all-section', [AjaxController::class, 'ajaxGetAllSection']);
    Route::get('ajax-section-all-student', [AjaxController::class, 'ajaxSectionAllStudent']);
    Route::get('ajax-get-all-student', [AjaxController::class, 'ajaxGetAllStudent']);
    Route::get('change-method', [AjaxController::class, 'changeMethod']);
});