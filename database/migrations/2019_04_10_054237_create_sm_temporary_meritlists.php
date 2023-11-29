<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmTemporaryMeritlists extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_temporary_meritlists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('iid',250)->nullable();
            $table->string('student_id',250)->nullable();
            $table->float('merit_order')->nullable();
            $table->string('student_name',250)->nullable();
            $table->string('admission_no',250)->nullable();
            $table->string('subjects_id_string',250)->nullable();
            $table->string('subjects_string',250)->nullable();
            $table->string('marks_string',250)->nullable();
            $table->float('total_marks')->nullable();
            $table->float('average_mark',20,2)->nullable();
            $table->float('gpa_point',20,2)->nullable();
            $table->string('result',250)->nullable();
            $table->timestamps();

            $table->integer('exam_id')->nullable()->unsigned();
            $table->foreign('exam_id')->references('id')->on('sm_exams')->onDelete('cascade');

            $table->integer('class_id')->nullable()->unsigned();
            $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('cascade');


            $table->integer('section_id')->nullable()->unsigned();
            $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('school_id')->nullable()->default(1)->unsigned();
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
        Schema::dropIfExists('sm_temporary_meritlists');
    }
}
