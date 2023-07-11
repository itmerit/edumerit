<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmSmsGateway;

class CreateSmSmsGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_sms_gateways', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gateway_name', 255)->nullable();
            $table->string('type', 5)->nullable()->default('com');
            // $table->integer('gateway_id')->nullable();
            $table->string('clickatell_username', 255)->nullable();
            $table->string('clickatell_password', 255)->nullable();
            $table->string('clickatell_api_id', 255)->nullable();
            $table->string('twilio_account_sid', 255)->nullable();
            $table->string('twilio_authentication_token', 255)->nullable();
            $table->string('twilio_registered_no', 255)->nullable();
            $table->string('msg91_authentication_key_sid', 255)->nullable();
            $table->string('msg91_sender_id', 255)->nullable();
            $table->string('msg91_route', 255)->nullable();
            $table->string('msg91_country_code', 255)->nullable();

            $table->string('textlocal_username', 255)->nullable();
            $table->string('textlocal_hash', 255)->nullable();
            $table->string('textlocal_sender', 255)->nullable();
            $table->text('device_info')->nullable();

            $table->string('africatalking_username', 255)->nullable();
            $table->string('africatalking_api_key', 255)->nullable();

            $table->tinyInteger('active_status')->default(0);
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');  
        });


        $gateway = new SmSmsGateway();
        $gateway->gateway_name = 'Twilio';
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->gateway_name = 'Msg91';
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->gateway_name = 'TextLocal';
        $gateway->textlocal_sender = 'TXTLCL';
        $gateway->save();

        $gateway = new SmSmsGateway();
        $gateway->gateway_name = 'AfricaTalking';
        $gateway->africatalking_username = 'sandbox';
        $gateway->save();
        
        $gateway = new SmSmsGateway();
        $gateway->gateway_name = 'Mobile SMS';
        $gateway->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_sms_gateways');
    }
}
