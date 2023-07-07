<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePayrollPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('sm_hr_payroll_generate_id')->nullable()->unsigned();
            $table->foreign('sm_hr_payroll_generate_id')->references('id')->on('sm_hr_payroll_generates');
            $table->double('amount')->nullable();
            $table->string('payment_mode')->nullable();
            $table->integer('payment_method_id')->nullable()->unsigned();
            $table->date('payment_date')->nullable();
            $table->integer('bank_id')->nullable();            
            $table->string('note', 200)->nullable();
            $table->integer('created_by')->nullable();
            $table->timestamps();
        });
        Schema::table('sm_hr_payroll_generates', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_hr_payroll_generates', 'is_partial')){
                $table->integer('is_partial')->after('active_status')->nullable();
            }           
            if(!Schema::hasColumn('sm_hr_payroll_generates', 'paid_amount')){
                $table->integer('paid_amount')->after('active_status')->nullable();
            }           
        });
        Schema::table('sm_add_expenses', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_add_expenses', 'payroll_payment_id')){
                $table->integer('payroll_payment_id')->nullable();
            }         
        });
        Schema::table('sm_bank_statements', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_bank_statements', 'payroll_payment_id')){
                $table->integer('payroll_payment_id')->nullable();
            }         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payroll_payments');
    }
}
