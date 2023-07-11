<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOnlineExamStudentAnswerMarkingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('online_exam_student_answer_markings', function (Blueprint $table) {
            $table->id();
            $table->integer('online_exam_id')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('question_id')->nullable();
            $table->string('user_answer')->nullable();
            $table->string('answer_status')->nullable();
            $table->integer('obtain_marks')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('marked_by')->default(0);
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
        Schema::dropIfExists('online_exam_student_answer_markings');
    }
}
