<?php

namespace App\Console\Commands;

use App\SmSchool;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'payment:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

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
        $schools = SmSchool::all();
        if(moduleStatusCheck('University')){
            foreach($schools as $school){
                paymentRemainder($school->id);
            }
        }elseif(directFees()){
            foreach($schools as $school){
                smPaymentRemainder($school->id);
            }
        }else{
            return ;
        }

        
        
        return true;
    }

    
}
