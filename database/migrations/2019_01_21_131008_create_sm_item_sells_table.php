<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmItemSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_item_sells', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('student_staff_id')->nullable();
            $table->date('sell_date')->nullable();
            $table->string('reference_no', 50)->nullable();
            $table->float('grand_total')->nullable();
            $table->float('total_quantity')->nullable();
            $table->float('total_paid')->nullable();
            $table->float('total_due')->nullable();
            $table->integer('income_head_id')->nullable();
            $table->integer('account_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('paid_status')->nullable()->comment('P = paid, PP = partially paid, U = unpaid, R = ----');
            $table->string('description')->length(500)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('role_id')->nullable()->unsigned();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

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
        Schema::dropIfExists('sm_item_sells');
    }
}
