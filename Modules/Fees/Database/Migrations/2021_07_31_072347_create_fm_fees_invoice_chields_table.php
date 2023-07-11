<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFmFeesInvoiceChieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fm_fees_invoice_chields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fees_invoice_id')->nullable()->unsigned();
            $table->foreign('fees_invoice_id')->references('id')->on('fm_fees_invoices')->onDelete('cascade');
            $table->integer('fees_type')->nullable();
            $table->float('amount')->nullable();
            $table->float('weaver')->nullable();
            $table->float('fine')->nullable();
            $table->float('sub_total')->nullable();
            $table->float('paid_amount')->nullable();
            $table->float('service_charge')->nullable();
            $table->float('due_amount')->nullable();
            $table->string('note')->nullable();
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
        Schema::dropIfExists('fm_fees_invoice_chields');
    }
}
