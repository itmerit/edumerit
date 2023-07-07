<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ResetApplicationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:application';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset application to default state';

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
     * @return int
     */
    public function handle()
    {
        $this->call('down');
        $this->call('migrate:fresh', ['--force' => true, '--seed' => true]);
        $this->call('optimize:clear');
        $this->call('clear:log-files');
        $this->call('up');
    }
}
