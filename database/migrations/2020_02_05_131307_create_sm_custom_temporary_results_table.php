<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmCustomTemporaryResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_custom_temporary_results', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_id')->nullable();
            $table->string('admission_no', 200)->nullable();
            $table->string('full_name', 200)->nullable();
            $table->string('term1', 200)->nullable();
            $table->string('gpa1', 200)->nullable();
            $table->string('term2', 200)->nullable();
            $table->string('gpa2', 200)->nullable(); 
            $table->string('term3', 200)->nullable();
            $table->string('gpa3', 200)->nullable();
            $table->string('final_result', 200)->nullable();
            $table->string('final_grade', 200)->nullable(); 

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('restrict');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            
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
        Schema::dropIfExists('sm_custom_temporary_results');
    }
}
