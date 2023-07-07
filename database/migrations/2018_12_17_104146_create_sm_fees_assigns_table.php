<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmFeesAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_fees_assigns', function (Blueprint $table) {
            $table->increments('id');            
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();
            $table->float('fees_amount', 10, 2)->nullable();
            $table->float('applied_discount', 10, 2)->nullable();            
            $table->integer('fees_master_id')->nullable()->unsigned();
            $table->foreign('fees_master_id')->references('id')->on('sm_fees_masters')->onDelete('cascade');
            $table->integer('fees_discount_id')->nullable()->unsigned();
            $table->foreign('fees_discount_id')->references('id')->on('sm_fees_discounts')->onDelete('cascade');
            $table->integer('record_id')->nullable()->unsigned();
            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            $table->integer('class_id')->nullable()->unsigned();
            $table->integer('section_id')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_fees_assigns');
    }
}
