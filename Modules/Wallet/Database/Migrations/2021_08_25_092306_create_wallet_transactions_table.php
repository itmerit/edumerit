<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->float('amount')->nullable();
            $table->string('payment_method')->nullable();
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('bank_id')->nullable();
            $table->string('note')->nullable();
            $table->string('type')->nullable()->comment('diposit, refund, expense, fees_refund');
            $table->text('file')->nullable();
            $table->text('reject_note')->nullable();
            $table->float('expense')->nullable();
            $table->string('status')->default('pending')->comment('pending, approve, reject');
            $table->integer('created_by')->nullable();
            $table->integer('academic_id')->default(1);
            $table->integer('school_id')->default(1);
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
        Schema::dropIfExists('wallet_transactions');
    }
}
