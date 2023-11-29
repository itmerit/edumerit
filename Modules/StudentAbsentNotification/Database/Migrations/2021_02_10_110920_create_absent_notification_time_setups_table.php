<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAbsentNotificationTimeSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absent_notification_time_setups', function (Blueprint $table) {
            $table->id();
            $table->string('time_from')->nullable();
            $table->string('time_to')->nullable();
            $table->integer('active_status')->default(1);
            $table->integer('school_id')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absent_notification_time_setups');
    }
}
