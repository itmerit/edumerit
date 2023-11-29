<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAllExamWisePositionsTable extends Migration
{
    public function up()
    {
        Schema::create('all_exam_wise_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->float('total_mark')->nullable();
            $table->integer('position')->nullable();
            $table->integer('roll_no')->nullable();
            $table->integer('admission_no')->nullable();
            $table->float('gpa')->nullable();
            $table->float('grade')->nullable();
            $table->integer('record_id')->nullable();
            $table->integer('school_id');
            $table->integer('academic_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('all_exam_wise_positions');
    }
}
