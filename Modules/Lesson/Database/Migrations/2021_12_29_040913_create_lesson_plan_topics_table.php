<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonPlanTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_plan_topics', function (Blueprint $table) {
            $table->id();
            $table->string('sub_topic_title');

            $table->integer('topic_id')->nullable()->unsigned();
            $table->foreign('topic_id')->references('id')->on('sm_lesson_topic_details')->onDelete('cascade');

            $table->integer('lesson_planner_id')->nullable()->unsigned();
            $table->foreign('lesson_planner_id')->references('id')->on('lesson_planners')->onDelete('cascade'); 
            
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
        Schema::dropIfExists('lesson_plan_topics');
    }
}
