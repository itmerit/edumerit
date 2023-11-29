<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmFeesTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fm_fees_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->nullable();
            $table->integer('student_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('bank_id')->nullable();
            $table->float('add_wallet_money')->nullable();
            $table->string('payment_note')->nullable();
            $table->text('file')->nullable();
            $table->string('paid_status')->nullable();
            $table->unsignedBigInteger('fees_invoice_id')->nullable()->unsigned();
            $table->foreign('fees_invoice_id')->references('id')->on('fm_fees_invoices')->onDelete('cascade');
            $table->integer('school_id')->nullable();
            $table->integer('academic_id')->nullable();
            $table->float('service_charge')->nullable();
            $table->integer('active_status')->nullable()->default(1);
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
        Schema::dropIfExists('fm_fees_transactions');
    }
}
