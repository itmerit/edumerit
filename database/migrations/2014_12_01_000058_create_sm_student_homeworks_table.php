<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmStudentHomeworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_student_homeworks', function (Blueprint $table) {
            $table->increments('id');
            $table->date('homework_date')->nullable();
            $table->date('submission_date')->nullable();
            $table->string('description', 500)->nullable();
            $table->string('percentage', 200)->nullable();
            $table->string('status', 200)->nullable();
            $table->timestamps();

            $table->integer('evaluated_by')->nullable()->unsigned();
            $table->foreign('evaluated_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->integer('subject_id')->nullable()->unsigned();
            $table->foreign('subject_id')->references('id')->on('sm_subjects')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });

        //  Schema::table('sm_student_homeworks', function($table) {
        //      $table->foreign('student_id')->references('id')->on('sm_students');
        //      $table->foreign('subject_id')->references('id')->on('sm_subjects');

        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_student_homeworks');
    }
}
