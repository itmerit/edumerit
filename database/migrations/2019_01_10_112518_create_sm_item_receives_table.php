<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmItemReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_item_receives', function (Blueprint $table) {
            $table->increments('id');
            $table->date('receive_date')->nullable();
            $table->string('reference_no')->nullable();
            $table->float('grand_total')->nullable();
            $table->float('total_quantity')->nullable();
            $table->float('total_paid')->nullable();
            $table->float('total_due')->nullable();
            $table->integer('expense_head_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('paid_status')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();


            $table->integer('supplier_id')->nullable()->unsigned();
            $table->foreign('supplier_id')->references('id')->on('sm_suppliers')->onDelete('cascade');

            $table->integer('store_id')->nullable()->unsigned();
            $table->foreign('store_id')->references('id')->on('sm_item_stores')->onDelete('cascade');

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
        Schema::dropIfExists('sm_item_receives');
    }
}
