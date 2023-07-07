<?php

use Illuminate\Support\Facades\Route;
use Modules\Wallet\Http\Controllers\WalletController;


Route::prefix('wallet')->middleware(['auth', 'subdomain'])->group(function() {
    Route::post('add-wallet-amount', [WalletController::class, 'addWalletAmount'])->name('wallet.add-wallet-amount');
    Route::get('pending-diposit', [WalletController::class, 'walletPendingDiposit'])->name('wallet.pending-diposit')->middleware('userRolePermission:wallet.pending-diposit');
    Route::get('approve-diposit', [WalletController::class, 'walletApproveDiposit'])->name('wallet.approve-diposit')->middleware('userRolePermission:wallet.approve-diposit');
    Route::get('reject-diposit', [WalletController::class, 'walletRejectDiposit'])->name('wallet.reject-diposit')->middleware('userRolePermission:wallet.reject-diposit');
    Route::post('approve-payment', [WalletController::class, 'walletApprovePayment'])->name('wallet.approve-payment')->middleware('userRolePermission:wallet.approve-payment');
    Route::post('reject-payment', [WalletController::class, 'walletRejectPayment'])->name('wallet.reject-payment')->middleware('userRolePermission:wallet.reject-payment');
    Route::get('wallet-transaction', [WalletController::class, 'walletTransaction'])->name('wallet.wallet-transaction')->middleware('userRolePermission:wallet.wallet-transaction');
    Route::get('wallet-transaction-ajax', [WalletController::class, 'walletTransactionAjax'])->name('wallet.wallet-transaction-ajax');
    Route::get('wallet-diposit-datatable', [WalletController::class, 'walletDipositDatatable'])->name('wallet.wallet-diposit-datatable')->middleware('userRolePermission:wallet.wallet-diposit-datatable');

    Route::get('wallet-refund-request', [WalletController::class, 'walletRefundRequest'])->name('wallet.wallet-refund-request')->middleware('userRolePermission:wallet.wallet-refund-request');

    Route::get('wallet-refund-request-ajax', [WalletController::class, 'walletRefundRequestAjax'])->name('wallet.wallet-refund-request-ajax');

    Route::post('wallet-refund-request-store', [WalletController::class, 'walletRefundRequestStore'])->name('wallet.wallet-refund-request-store');
    Route::post('approve-refund', [WalletController::class, 'walletApproveRefund'])->name('wallet.approve-refund');
    Route::post('reject-refund', [WalletController::class, 'walletRejectRefund'])->name('wallet.reject-refund');
    Route::get('wallet-report', [WalletController::class, 'walletReport'])->name('wallet.wallet-report')->middleware('userRolePermission:wallet.wallet-report');
    Route::post('wallet-report-search', [WalletController::class, 'walletReportSearch'])->name('wallet.wallet-report-search');

    Route::get('my-wallet', [WalletController::class, 'myWallet'])->name('wallet.my-wallet');
});
