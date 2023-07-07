<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmBankStatementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_bank_statements', function (Blueprint $table) {
            $table->id();
            $table->integer('bank_id')->nullable();
            $table->integer('after_balance')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->string('type')->length(11)->nullable()->comment('1 for Income 0 for Expense');

            $table->integer('payment_method')->nullable()->unsigned();
            
            $table->string('details')->length(500)->nullable();
            $table->integer('item_receive_id')->nullable();
            $table->integer('item_receive_bank_statement_id')->nullable();
            $table->integer('item_sell_bank_statement_id')->nullable();
            $table->integer('item_sell_id')->nullable();
            $table->date('payment_date')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->integer('school_id')->nullable()->default(1)->unsigned();

            $table->integer('academic_id')->nullable()->unsigned();

            $table->integer('fees_payment_id')->nullable();
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
        Schema::dropIfExists('sm_bank_statements');
    }
}
