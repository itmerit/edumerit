<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmStudentPromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_student_promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('result_status', 10)->nullable();
            $table->timestamps();

            $table->integer('previous_class_id')->nullable()->unsigned();
            $table->foreign('previous_class_id')->references('id')->on('sm_classes')->onDelete('cascade');

            $table->integer('current_class_id')->nullable()->unsigned();
            $table->foreign('current_class_id')->references('id')->on('sm_classes')->onDelete('cascade');

            $table->integer('previous_section_id')->nullable()->unsigned();
            $table->foreign('previous_section_id')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('current_section_id')->nullable()->unsigned();
            $table->foreign('current_section_id')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('previous_session_id')->nullable()->unsigned();
            $table->foreign('previous_session_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('current_session_id')->nullable()->unsigned();
            $table->foreign('current_session_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->integer('admission_number')->nullable();
            $table->longText('student_info')->nullable();
            $table->longText('merit_student_info')->nullable();

            $table->integer('previous_roll_number')->nullable();
            $table->integer('current_roll_number')->nullable();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });

        //  Schema::table('sm_student_promotions', function($table) {
        //     $table->foreign('student_id')->references('id')->on('sm_students');

        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_student_promotions');
    }
}
