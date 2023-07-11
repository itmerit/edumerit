<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('staff_no')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('full_name', 200)->nullable();
            $table->string('fathers_name', 100)->nullable();
            $table->string('mothers_name', 100)->nullable();
            $table->date('date_of_birth')->nullable()->default(date('Y-m-d'));
            $table->date('date_of_joining')->nullable()->default(date('Y-m-d'));
            $table->string('email', 50)->nullable();
            $table->string('mobile', 50)->nullable();
            $table->string('emergency_mobile', 50)->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->string('merital_status', 30)->nullable();
            $table->string('staff_photo')->nullable();
            $table->string('current_address', 500)->nullable();
            $table->string('permanent_address', 500)->nullable();
            $table->string('qualification', 200)->nullable();
            $table->string('experience', 200)->nullable();
            $table->string('epf_no', 20)->nullable();
            $table->string('basic_salary', 200)->nullable();
            $table->string('contract_type', 200)->nullable();
            $table->string('location', 50)->nullable();
            $table->string('casual_leave', 15)->nullable();
            $table->string('medical_leave', 15)->nullable();
            $table->string('metarnity_leave', 15)->nullable();
            $table->string('bank_account_name', 50)->nullable();
            $table->string('bank_account_no', 50)->nullable();
            $table->string('bank_name', 20)->nullable();
            $table->string('bank_brach', 30)->nullable();
            $table->string('facebook_url', 100)->nullable();
            $table->string('twiteer_url', 100)->nullable();
            $table->string('linkedin_url', 100)->nullable();
            $table->string('instragram_url', 100)->nullable();
            $table->string('joining_letter', 500)->nullable();
            $table->string('resume', 500)->nullable();
            $table->string('other_document', 500)->nullable();
            $table->string('notes', 500)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->string('driving_license', 255)->nullable();
            $table->date('driving_license_ex_date')->nullable();
            $table->text('custom_field')->nullable();
            $table->string('custom_field_form_name')->nullable();
            $table->timestamps();


            $table->integer('designation_id')->nullable()->unsigned()->default(1);
            $table->foreign('designation_id')->references('id')->on('sm_designations')->onDelete('set null');

            $table->integer('department_id')->nullable()->unsigned()->default(1);
            $table->foreign('department_id')->references('id')->on('sm_human_departments')->onDelete('set null');

            $table->integer('user_id')->nullable()->unsigned()->default(1);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('parent_id')->nullable();
            
            $table->integer('role_id')->nullable()->unsigned()->default(1);
            $table->foreign('role_id')->references('id')->on('infix_roles')->onDelete('set null');

            $table->integer('previous_role_id')->nullable();
           

            $table->integer('gender_id')->nullable()->unsigned()->default(1);
            $table->foreign('gender_id')->references('id')->on('sm_base_setups')->onDelete('set null');

           
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('is_saas')->nullable()->default(0)->unsigned();
        });


        DB::table('sm_staffs')->insert([
            [
                'staff_no'       => '1',
                'first_name'       => 'Super',
                'last_name'        => 'Admin',
                'full_name'        => 'Super Admin',
                'email'            => 'admin@infixedu.com',
                'created_at' => date('Y-m-d h:i:s')
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_staffs');
    }
}
