<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmFeesTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fm_fees_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 230)->nullable();
            $table->text('description')->nullable();
            $table->integer('fees_group_id')->nullable()->default(1)->unsigned();
            $table->string('type')->nullable()->default("fees")->comment('fees, lms');
            $table->integer('course_id')->nullable()->comment('Only For Lms');
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
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
        Schema::dropIfExists('fm_fees_types');
    }
}
