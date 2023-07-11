<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmHumanDepartmentsTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('sm_human_departments', function (Blueprint $table) {
         $table->increments('id');
         $table->string('name')->nullable();
         $table->tinyInteger('active_status')->default(1);
         $table->timestamps();

         $table->integer('created_by')->nullable()->default(1)->unsigned();
         $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

         $table->integer('updated_by')->nullable()->default(1)->unsigned();
         $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

         $table->integer('school_id')->nullable()->default(1)->unsigned();
         $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

        //  $table->integer('academic_id')->nullable()->default(1)->unsigned();
        //  $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            $table->integer('is_saas')->nullable()->default(0)->unsigned();
      });


      DB::table('sm_human_departments')->insert([
         [
            'name' => 'Admin',
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
      Schema::dropIfExists('sm_human_departments');
   }
}
