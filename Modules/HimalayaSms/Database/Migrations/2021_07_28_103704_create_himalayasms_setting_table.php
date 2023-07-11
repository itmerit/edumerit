<?php

use App\SmSmsGateway;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHimalayasmsSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { try{
            $gateway = SmSmsGateway::where('gateway_name','Himalayasms')->first();
            if(!$gateway){
                $gateway = new SmSmsGateway();
                $gateway->gateway_name = 'Himalayasms';
                $gateway->save();

                $name ="himalayasms_senderId";
                    if (!Schema::hasColumn('sm_sms_gateways', $name)) {
                        Schema::table('sm_sms_gateways', function ($table) use ($name) {
                            $table->string('himalayasms_senderId',100)->default("KalashAlert"); 
                        });
                    }
                    $name ="himalayasms_key";
                    if (!Schema::hasColumn('sm_sms_gateways', $name)) {
                        Schema::table('sm_sms_gateways', function ($table) use ($name) {
                            $table->string('himalayasms_key',100)->default("002610127E0935F1"); 
                        });
                    }
                    $name ="himalayasms_campaign";
                    if (!Schema::hasColumn('sm_sms_gateways', $name)) {
                        Schema::table('sm_sms_gateways', function ($table) use ($name) {
                            $table->string('himalayasms_campaign',40)->default("5317"); 
                        });
                    }
                    $name ="himalayasms_routeId";
                    if (!Schema::hasColumn('sm_sms_gateways', $name)) {
                        Schema::table('sm_sms_gateways', function ($table) use ($name) {
                            $table->string('himalayasms_routeId',91)->default("125"); 
                        });
                    }

                    $HimalayaSms ="HimalayaSms";
                    if (!Schema::hasColumn('sm_general_settings', $HimalayaSms)) {
                        Schema::table('sm_general_settings', function ($table) use ($HimalayaSms) {
                            $table->integer('HimalayaSms')->default(0)->nullable();
                        });
                    }
            }

    }catch (\Exception $e) {
        Log::info($e->getMessage());
    }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('himalayasms_setting');
    }
}
