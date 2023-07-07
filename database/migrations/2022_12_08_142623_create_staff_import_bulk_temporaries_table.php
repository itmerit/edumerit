<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffImportBulkTemporariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_import_bulk_temporaries', function (Blueprint $table) {
            $table->id();
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
            $table->string('maternity_leave', 15)->nullable();
            $table->string('bank_account_name', 50)->nullable();
            $table->string('bank_account_no', 50)->nullable();
            $table->string('bank_name', 20)->nullable();
            $table->string('bank_brach', 30)->nullable();
            $table->string('facebook_url', 100)->nullable();
            $table->string('twitter_url', 100)->nullable();
            $table->string('linkedin_url', 100)->nullable();
            $table->string('instagram_url', 100)->nullable();
            $table->string('joining_letter', 500)->nullable();
            $table->string('resume', 500)->nullable();
            $table->string('other_document', 500)->nullable();
            $table->string('notes', 500)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->string('driving_license', 255)->nullable();
            $table->date('driving_license_ex_date')->nullable();
            $table->string('role')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->integer('gender_id')->nullable(); 
            $table->integer('user_id')->nullable()->unsigned()->default(1);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('parent_id')->nullable();
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
        Schema::dropIfExists('staff_import_bulk_temoraries');
    }
}
