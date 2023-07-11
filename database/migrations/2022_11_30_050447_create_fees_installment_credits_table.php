<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeesInstallmentCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees_installment_credits', function (Blueprint $table) {
            $table->id();
            $table->integer('student_id');
            $table->integer('student_record_id');
            $table->boolean('active_status')->default(1);
            $table->integer('school_id');
            $table->float('amount')->default(0);
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
        Schema::dropIfExists('fees_installment_credits');
    }
}
