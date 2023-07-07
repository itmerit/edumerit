<?php

use Illuminate\Support\Facades\Route;

if (moduleStatusCheck('Saas')) {
    Route::group(['middleware' => ['subdomain'], 'domain' => '{subdomain}.' . config('app.short_url')], function ($routes) {
        require ('tenant.php');
    });

    Route::group(['middleware' => ['subdomain'], 'domain' => '{subdomain}'], function ($routes) {
        require ('tenant.php');
    });
}

Route::group(['middleware' => ['subdomain']], function ($routes) {
    require ('tenant.php');
});

