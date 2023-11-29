<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_students', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('admission_no')->nullable();
            $table->integer('roll_no')->nullable();
            $table->string('first_name', 200)->nullable();
            $table->string('last_name', 200)->nullable();
            $table->string('full_name', 200)->nullable();
            $table->date('date_of_birth')->nullable();

            $table->string('caste', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('mobile', 200)->nullable();
            $table->date('admission_date')->nullable();
            $table->string('student_photo')->nullable();

            $table->string('age', 200)->nullable();
            $table->string('height', 200)->nullable();
            $table->string('weight', 200)->nullable();
            $table->string('current_address', 500)->nullable();
            $table->string('permanent_address', 500)->nullable();

            $table->string('driver_id', 200)->nullable();
            $table->string('national_id_no', 200)->nullable();
            $table->string('local_id_no', 200)->nullable();
            $table->string('bank_account_no', 200)->nullable();
            $table->string('bank_name', 200)->nullable();
            $table->string('previous_school_details', 500)->nullable();
            $table->string('aditional_notes', 500)->nullable();
            $table->string('ifsc_code', 50)->nullable();
            $table->string('document_title_1', 200)->nullable();
            $table->string('document_file_1', 200)->nullable();
            $table->string('document_title_2', 200)->nullable();
            $table->string('document_file_2', 200)->nullable();
            $table->string('document_title_3', 200)->nullable();
            $table->string('document_file_3', 200)->nullable();
            $table->string('document_title_4', 200)->nullable();
            $table->string('document_file_4', 200)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->text('custom_field')->nullable();
            $table->string('custom_field_form_name')->nullable();
            $table->timestamps();

            $table->integer('bloodgroup_id')->nullable()->unsigned();
            $table->foreign('bloodgroup_id')->references('id')->on('sm_base_setups')->onDelete('set null');

            $table->integer('religion_id')->nullable()->unsigned();
            $table->foreign('religion_id')->references('id')->on('sm_base_setups')->onDelete('set null');

            $table->integer('route_list_id')->nullable()->unsigned();
            $table->foreign('route_list_id')->references('id')->on('sm_routes')->onDelete('set null');

            $table->integer('dormitory_id')->nullable()->unsigned();
            $table->foreign('dormitory_id')->references('id')->on('sm_dormitory_lists')->onDelete('set null');

            $table->integer('vechile_id')->nullable()->unsigned();
            $table->foreign('vechile_id')->references('id')->on('sm_vehicles')->onDelete('set null');

            $table->integer('room_id')->nullable()->unsigned();
            $table->foreign('room_id')->references('id')->on('sm_room_lists')->onDelete('set null');

            $table->integer('student_category_id')->nullable()->unsigned();
            $table->foreign('student_category_id')->references('id')->on('sm_student_categories')->onDelete('set null');

            $table->integer('student_group_id')->nullable()->unsigned();
            $table->foreign('student_group_id')->references('id')->on('sm_student_groups')->onDelete('set null');

            $table->integer('class_id')->nullable()->unsigned();
            $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('set null');

            $table->integer('section_id')->nullable()->unsigned();
            $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('set null');

            $table->integer('session_id')->nullable()->unsigned();
            $table->foreign('session_id')->references('id')->on('sm_academic_years')->onDelete('set null');

            $table->integer('parent_id')->nullable()->nullable()->unsigned();
            $table->foreign('parent_id')->references('id')->on('sm_parents')->onDelete('set null');

            $table->integer('user_id')->nullable()->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('role_id')->nullable()->unsigned();
            $table->foreign('role_id')->references('id')->on('infix_roles')->onDelete('cascade');

            $table->integer('gender_id')->nullable()->unsigned();
            $table->foreign('gender_id')->references('id')->on('sm_base_setups')->onDelete('set null');


            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->unsigned();
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
        Schema::table('sm_students', function (Blueprint $table) {
            $table->dropForeign(['bloodgroup_id']);
            $table->dropForeign(['religion_id']);
            $table->dropForeign(['route_list_id']);
            $table->dropForeign(['dormitory_id']);
            $table->dropForeign(['vechile_id']);
            $table->dropForeign(['room_id']);
            $table->dropForeign(['student_category_id']);
            $table->dropForeign(['student_group_id']);
            $table->dropForeign(['class_id']);
            $table->dropForeign(['section_id']);
            $table->dropForeign(['session_id']);
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['user_id']);
            $table->dropForeign(['role_id']);
            $table->dropForeign(['gender_id']);
            $table->dropForeign(['school_id']);
            $table->dropForeign(['academic_id']);
        });
        Schema::dropIfExists('sm_students');
    }
}