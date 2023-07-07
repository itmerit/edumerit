<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmEmailSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_email_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email_engine_type')->nullable();
            $table->string('from_name')->nullable();
            $table->string('from_email')->nullable();

            $table->string('mail_driver')->nullable();
            $table->string('mail_host')->nullable();
            $table->string('mail_port')->nullable();
            $table->string('mail_username')->nullable();
            $table->string('mail_password')->nullable();
            $table->string('mail_encryption')->nullable();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            
            $table->tinyInteger('active_status')->default(1);

            $table->timestamps();
        });
        DB::table('sm_email_settings')->insert([
            [
                'email_engine_type' => 'smtp',
                'from_name'=>'System Admin',
                'from_email'=>'admin@infixedu.com',
                'mail_driver'=>'smtp',
                'mail_host'=>'smtp.gmail.com',
                'mail_port'=>'587',
                'mail_username'=>'demo@spondonit.com',
                'mail_password'=>'123456',
                'mail_encryption'=>'tls',
                'active_status'=>'0',
                'academic_id' => 1
            ]
        ]);

        DB::table('sm_email_settings')->insert([
            [
                'email_engine_type' => 'php',
                'from_name'=>'System Admin',
                'from_email'=>'admin@infixedu.com',
                'mail_driver'=>'php',
                'mail_host'=>'',
                'mail_port'=>'',
                'mail_username'=>'',
                'mail_password'=>'',
                'mail_encryption'=>'',
                'active_status'=>'1',
                'academic_id' => 1
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
        Schema::dropIfExists('sm_email_settings');
    }
}
