<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomSmsSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_sms_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('gateway_id');
            $table->string('gateway_name');
            $table->string('set_auth')->nullable();
            $table->string('gateway_url');
            $table->string('request_method');
            $table->string('send_to_parameter_name');
            $table->string('messege_to_parameter_name');

            $table->string('param_key_1')->nullable();
            $table->string('param_value_1')->nullable();

            $table->string('param_key_2')->nullable();
            $table->string('param_value_2')->nullable();

            $table->string('param_key_3')->nullable();
            $table->string('param_value_3')->nullable();

            $table->string('param_key_4')->nullable();
            $table->string('param_value_4')->nullable();

            $table->string('param_key_5')->nullable();
            $table->string('param_value_5')->nullable();

            $table->string('param_key_6')->nullable();
            $table->string('param_value_6')->nullable();

            $table->string('param_key_7')->nullable();
            $table->string('param_value_7')->nullable();

            $table->string('param_key_8')->nullable();
            $table->string('param_value_8')->nullable();
            $table->integer('school_id')->default(1);
            $table->timestamps();
        });

        
        $gateway_type = "gateway_type";    

            if (!Schema::hasColumn('sm_sms_gateways', $gateway_type)) {
                Schema::table('sm_sms_gateways', function ($table) use ($gateway_type) {
                    $table->string('gateway_type')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_sms_settings');
    }
}
