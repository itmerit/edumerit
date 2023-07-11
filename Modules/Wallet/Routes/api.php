<?php

use Illuminate\Http\Request;
use Modules\Wallet\Http\Controllers\api\WalletApiController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->group(function () {
    Route::get('my-wallet', [WalletApiController::class, 'myWallet']);
    Route::post('add-wallet', [WalletApiController::class, 'addWalletAmount']);
    Route::post('confirm-wallet-payment', [WalletApiController::class, 'confirmWalletPayment']);
});