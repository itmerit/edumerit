<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmLessonTopicDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_lesson_topic_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lesson_id')->nullable();
            $table->string('topic_title');
            $table->string('completed_status')->nullable();
            $table->date('competed_date')->nullable();
            
            $table->integer('active_status')->default(1);

            $table->integer('topic_id')->nullable()->default(1)->unsigned();
            $table->foreign('topic_id')->references('id')->on('sm_lesson_topics')->onDelete('cascade');  

            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');  
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('user_id')->nullable();
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
        Schema::dropIfExists('sm_lesson_topic_details');
    }
}
