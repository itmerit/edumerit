<?php

use App\SmLanguagePhrase;
use App\SmPaymentMethhod;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWmWalletSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    // User Table Add New Column 
        $columnName ="wallet_balance";
        if (!Schema::hasColumn('users', $columnName)) {
            Schema::table('users', function ($table) use ($columnName) {
                $table->float('wallet_balance')->default(0);
            });
        }

    // Add New Payment Method In Payment Method Settings

        if (moduleStatusCheck('Saas')){
            $schools = \App\SmSchool::all();
        } else{
            $schools = \App\SmSchool::where('id', 1)->get();
        }

   foreach ($schools as $school){
       $payment_method = SmPaymentMethhod::where('method', 'Wallet')->where('school_id', $school->id)->first();
       if (!$payment_method){
           $storePaymentMethod = new SmPaymentMethhod ();
           $storePaymentMethod->method = 'Wallet';
           $storePaymentMethod->type = 'System';
           $storePaymentMethod->active_status = 1;
           $storePaymentMethod->created_by = 1;
           $storePaymentMethod->updated_by = 1;
           $storePaymentMethod->school_id = $school->id;
           $storePaymentMethod->save();
       }
   }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wm__wallet_settings');
    }
}
