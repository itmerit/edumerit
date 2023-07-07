<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_notifications', function (Blueprint $table) {
            
            $table->increments('id');
            $table->date('date')->nullable();
            $table->string('message')->nullable();
            $table->string('url')->nullable();
            $table->tinyInteger('is_read')->default(0);
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('user_id')->default(1)->nullable()->unsigned();

            $table->integer('role_id')->default(1)->unsigned();

            $table->integer('created_by')->default(1)->unsigned();

            $table->integer('updated_by')->default(1)->unsigned();

            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_notifications');
    }
}
