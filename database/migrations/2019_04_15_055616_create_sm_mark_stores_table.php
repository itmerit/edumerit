<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmMarkStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_mark_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_roll_no')->default(1); 
            $table->integer('student_addmission_no')->default(1); 
            $table->float('total_marks')->default(0); 
            $table->tinyInteger('is_absent')->default(1);
            $table->text('teacher_remarks')->nullable();
            $table->timestamps();


            $table->integer('subject_id')->nullable()->unsigned();
            $table->foreign('subject_id')->references('id')->on('sm_subjects')->onDelete('cascade');

            $table->integer('exam_term_id')->nullable()->unsigned();
            $table->foreign('exam_term_id')->references('id')->on('sm_exam_types')->onDelete('cascade');

            $table->integer('exam_setup_id')->nullable()->unsigned();
            $table->foreign('exam_setup_id')->references('id')->on('sm_exam_setups')->onDelete('cascade');

            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->bigInteger('student_record_id')->nullable()->unsigned();
            $table->foreign('student_record_id')->references('id')->on('student_records')->onDelete('cascade');

            $table->integer('class_id')->nullable()->unsigned();
            $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('cascade');


            $table->integer('section_id')->nullable()->unsigned();
            $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('active_status')->nullable()->default(1);
        });

        // $sql ="";
        // DB::insert($sql);
    }
    


     /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_mark_stores');
    }
}
