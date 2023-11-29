<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamMeritPositionsTable extends Migration
{

    public function up()
    {
        Schema::create('exam_merit_positions', function (Blueprint $table) {
            $table->id();
            $table->integer('class_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('exam_term_id')->nullable();
            $table->integer('total_mark')->nullable();
            $table->integer('position')->nullable();
            $table->integer('admission_no')->nullable();
            $table->float('gpa')->nullable();
            $table->string('grade')->nullable();
            $table->integer('record_id')->nullable();
            $table->integer('school_id');
            $table->integer('academic_id');
            $table->integer('active_status')->nullable()->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_merit_positions');
    }
}
