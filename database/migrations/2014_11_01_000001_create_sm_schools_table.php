<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmSchoolsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_schools', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_name', 200)->nullable();
            $table->tinyInteger('created_by')->default(1);
            $table->tinyInteger('updated_by')->default(1);
            $table->string('email', 200)->nullable();
            $table->string('domain', 191)->default('school');
            $table->text('address', 200)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('school_code', 200)->nullable();
            $table->boolean('is_email_verified')->default(0);
            $table->date('starting_date')->nullable();
            $table->date('ending_date')->nullable();
            $table->integer('package_id')->nullable();
            $table->string('plan_type', 200)->nullable();
            $table->enum('contact_type', ['yearly', 'monthly', 'once'])->nullable();
            $table->tinyInteger('active_status')->default(1)->comment("1 approved, 0 pending");
            $table->string('is_enabled', 20)->default('yes')->comment("yes=Login enable, no=Login disable");
            $table->timestamps();
        });

        DB::table('sm_schools')->insert([
            [
                'school_name' => 'InfixEdu',
                'created_by' => 1,
                'updated_by' => 1,
                'active_status' => 1,
                'is_enabled' => "yes",
                'email'=>'admin@infixedu.com',
                'starting_date'=>date('Y-m-d')
            ],
        ]);
    }

    // update 6.1 to 6.2 
    


    // update 6.2 to 6.4



    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_schools');
    }
}
