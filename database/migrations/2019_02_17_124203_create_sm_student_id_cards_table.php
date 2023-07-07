<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmStudentIdCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_student_id_cards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('logo')->nullable();
            $table->string('signature')->nullable(); 
            $table->string('background_img')->nullable();
            $table->string('profile_image')->nullable();
            $table->text('role_id')->nullable();
            $table->string('page_layout_style')->nullable();
            $table->string('user_photo_style')->nullable();
            $table->string('user_photo_width')->nullable();
            $table->string('user_photo_height')->nullable();
            $table->integer('pl_width')->nullable();
            $table->integer('pl_height')->nullable();
            $table->integer('t_space')->nullable();
            $table->integer('b_space')->nullable();
            $table->integer('r_space')->nullable();
            $table->integer('l_space')->nullable();
            $table->string('admission_no')->default(0)->comment('0 for no 1 for yes');
            $table->string('student_name')->default(0)->comment('0 for no 1 for yes');
            $table->string('class')->default(0)->comment('0 for no 1 for yes');
            $table->string('father_name')->default(0)->comment('0 for no 1 for yes');
            $table->string('mother_name')->default(0)->comment('0 for no 1 for yes');
            $table->string('student_address')->default(0)->comment('0 for no 1 for yes');
            $table->string('phone_number')->default(0)->comment('0 for no 1 for yes');
            $table->string('dob')->default(0)->comment('0 for no 1 for yes');
            $table->string('blood')->default(0)->comment('0 for no 1 for yes');
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();



            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

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
        Schema::dropIfExists('sm_student_id_cards');
    }
}
