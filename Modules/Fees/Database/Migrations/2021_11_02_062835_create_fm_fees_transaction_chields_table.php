<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmFeesTransactionChieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fm_fees_transaction_chields', function (Blueprint $table) {
            $table->id();
            $table->string('fees_type')->nullable();
            $table->float('paid_amount')->nullable();
            $table->float('service_charge')->nullable();
            $table->float('fine')->nullable();
            $table->float('weaver')->nullable();
            $table->string('note')->nullable();
            $table->unsignedBigInteger('fees_transaction_id')->nullable()->unsigned();
            $table->foreign('fees_transaction_id')->references('id')->on('fm_fees_transactions')->onDelete('cascade');
            $table->integer('school_id')->nullable();
            $table->integer('academic_id')->nullable();
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
        Schema::dropIfExists('fm_fees_transaction_chields');
    }
}
