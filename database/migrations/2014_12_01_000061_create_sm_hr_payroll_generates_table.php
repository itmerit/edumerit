<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmHrPayrollGeneratesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_hr_payroll_generates', function (Blueprint $table) {
            $table->increments('id');
            $table->double('basic_salary')->nullable();
            $table->double('total_earning')->nullable();
            $table->double('total_deduction')->nullable();
            $table->double('gross_salary')->nullable();
            $table->double('tax')->nullable();
            $table->double('net_salary')->nullable();
            $table->string('payroll_month')->nullable();
            $table->string('payroll_year')->nullable();
            $table->string('payroll_status')->nullable()->comment('NG for not generated, G for generated, P for paid');
            $table->string('payment_mode')->nullable();
            $table->date('payment_date')->nullable();
            $table->integer('bank_id')->nullable();
            $table->string('note', 200)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();



            $table->integer('staff_id')->nullable()->unsigned();
            $table->foreign('staff_id')->references('id')->on('sm_staffs')->onDelete('cascade');


            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });

        //  Schema::table('sm_hr_payroll_generates', function($table) {
        //     $table->foreign('staff_id')->references('id')->on('sm_staffs');

        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_hr_payroll_generates');
    }
}
