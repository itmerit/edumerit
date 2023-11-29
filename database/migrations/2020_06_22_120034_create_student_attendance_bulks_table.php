<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentAttendanceBulksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_attendance_bulks', function (Blueprint $table) {
            $table->id();
            $table->string('attendance_date')->nullable();
            // $table->string('in_time')->nullable();
            // $table->string('out_time')->nullable();
            $table->string('attendance_type')->nullable();
            $table->string('note')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('student_record_id')->nullable();
            $table->integer('class_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
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
        Schema::dropIfExists('student_attendance_bulks');
    }
}