<?php

use Modules\TwoFactorAuth\Http\Controllers\TwoFactorAuthController;



Route::middleware('subdomain')->group(function() {
    Route::get('verify_two_factor_code', [TwoFactorAuthController::class, 'index'])->name('2fa.index');
    Route::post('2fa', [TwoFactorAuthController::class, 'verifyCode'])->name('2fa.verify');
    Route::get('two_factor_code_resend', [TwoFactorAuthController::class, 'resend_code'])->name('2fa.resend');
    
    Route::get('two_factor_auth_setting', [TwoFactorAuthController::class, 'setting'])->name('two_factor_auth_setting');
    Route::post('two_factor_auth_setting', [TwoFactorAuthController::class, 'settingUpdate'])->name('two_factor_auth_setting_update');
});
