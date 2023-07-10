<?php

namespace App\Eskiz;

use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->publishConfig();
        if (!$this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/sms.php', 'sms');
        }
        $this->app->singleton('sms', EskizSmsClient::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function publishConfig()
    {
        $this->publishes([
            __DIR__.'/../config/sms.php' => config_path('sms.php'),
        ], 'sms-config');
    }
}
