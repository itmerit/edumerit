<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonPlannersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_planners', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('day')->nullable()->comment('1=sat,2=sun,7=fri');
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('lesson_id')->nullable();
            $table->integer('topic_id')->nullable();

            $table->integer('lesson_detail_id');
            $table->integer('topic_detail_id')->nullable();
            $table->string('sub_topic')->nullable();
            $table->text('lecture_youube_link')->nullable();
            $table->text('lecture_vedio')->nullable();
            $table->text('attachment')->nullable();
            $table->text('teaching_method')->nullable();
            $table->text('general_objectives')->nullable();

            $table->text('previous_knowlege')->nullable();
            $table->text('comp_question')->nullable();
            $table->text('zoom_setup')->nullable();
            $table->text('presentation')->nullable();
            $table->text('note')->nullable();          
            $table->date('lesson_date');
            $table->date('competed_date')->nullable();
            $table->string('completed_status')->nullable();

            $table->integer('room_id')->nullable()->unsigned();
            $table->foreign('room_id')->references('id')->on('sm_class_rooms')->onDelete('cascade');

            $table->integer('teacher_id')->nullable()->unsigned();
            $table->foreign('teacher_id')->references('id')->on('sm_staffs')->onDelete('cascade');

            $table->integer('class_period_id')->nullable()->unsigned();
            $table->foreign('class_period_id')->references('id')->on('sm_class_times')->onDelete('cascade');


            $table->integer('subject_id')->nullable()->unsigned();
            $table->foreign('subject_id')->references('id')->on('sm_subjects')->onDelete('cascade');

            $table->integer('class_id')->nullable()->unsigned();
            $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('cascade');


            $table->integer('section_id')->nullable()->unsigned();
            $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('routine_id')->nullable()->unsigned();

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
        Schema::dropIfExists('lesson_planners');
    }
}
