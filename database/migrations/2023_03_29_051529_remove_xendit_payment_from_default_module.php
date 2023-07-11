<?php

use App\InfixModuleManager;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveXenditPaymentFromDefaultModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $xenditPayment = InfixModuleManager::where('name', 'XenditPayment')->first();
        if($xenditPayment){
            $xenditPayment->is_default = 0;
            $xenditPayment->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $xenditPayment = InfixModuleManager::where('name', 'XenditPayment')->first();
        if($xenditPayment){
            $xenditPayment->is_default = 1;
            $xenditPayment->save();
        }
    }
}
