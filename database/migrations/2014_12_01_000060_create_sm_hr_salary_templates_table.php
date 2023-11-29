<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmHrSalaryTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_hr_salary_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('salary_grades', 200)->nullable();
            $table->string('salary_basic', 200)->nullable();
            $table->string('overtime_rate', 200)->nullable();
            $table->integer('house_rent')->nullable();
            $table->integer('provident_fund')->nullable();
            $table->integer('gross_salary')->nullable();
            $table->integer('total_deduction')->nullable();
            $table->integer('net_salary')->nullable();
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
        Schema::dropIfExists('sm_hr_salary_templates');
    }
}
