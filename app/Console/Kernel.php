<?php

namespace App\Console;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Modules\Lead\Console\ReminderLead;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\University\Console\PaymentReminder;

class Kernel extends ConsoleKernel
{
    public function __construct(Application $app, Dispatcher $events)
    {
        parent::__construct($app, $events);
        // if (moduleStatusCheck("Lead")) {
        //     $this->commands = array_merge($this->commands, [ReminderLead::class]);
        // }
        // if (moduleStatusCheck("University")) {
        //     $this->commands = array_merge($this->commands, [PaymentReminder::class]);
        // }

    }

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    protected $commands = [
        Commands\DemoCron::class
    ];


    protected $PurchaseVerificationMiddleware = [
        'purchaseVerification' => \App\Http\Middleware\PurchaseVerification::class,
    ];

    //    protected $middleware = [
    //     \App\http\Middleware\CustomerMiddleware::class,
    // ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('demo:cron')->everyMinute();
        $schedule->command('absent_notification:sms')->everyMinute();

        /**
         *  Example command of cron job
         * /opt/cpanel/ea-php74/root/usr/bin/php /home/uxseqmbj/public_html/infix_5/artisan absent_notification:sms > /dev/null 2>&1
         */
//         $schedule->command('absent_notification:sms')->everyMinute();
//         $schedule->command('absent_notification:sms')->dailyAt('13:00');

        $schedule->command('queue:work')->everyMinute()->withoutOverlapping();
        if (moduleStatusCheck("Lead") == true) {
            $schedule->command('lead:reminder')->everyTenMinutes()->withoutOverlapping();
        }

        if (moduleStatusCheck("University") == true) {
            $schedule->command('payment:reminder')->everyTenMinutes()->withoutOverlapping();
        }


        if (config('app.app_sync')) {
            $schedule->command('reset:application')->dailyAt('02:00')->withoutOverlapping();
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
