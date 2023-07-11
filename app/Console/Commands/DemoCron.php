<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class DemoCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("Cron is working fine!");
        Artisan::call('queue:work --stop-when-empty');
        // Artisan::call('cache:clear');
        // Artisan::call('view:clear');
        // Artisan::call('route:clear');
        // Artisan::call('config:clear');
        // Artisan::call('migrate:refresh');
        // Artisan::call('db:seed');
        $this->info('Demo:Cron Cummand Run successfully!');
    }
}
