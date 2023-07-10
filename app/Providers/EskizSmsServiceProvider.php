<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\EskizSmsService;

class EskizSmsServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind('eskizsms', function () {
            // Replace with your implementation of EskizSmsService
            return new EskizSmsService();
        });
    }
}
