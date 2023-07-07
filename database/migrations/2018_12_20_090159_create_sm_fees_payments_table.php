<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmFeesPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('sm_fees_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('discount_month')->nullable();
            $table->double('discount_amount', 8, 2)->nullable();
            $table->double('fine', 8, 2)->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_mode', 100)->nullable();
            $table->text('note')->nullable();
            $table->string('slip')->nullable();
            $table->string('fine_title')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('assign_id')->nullable()->unsigned();
            $table->foreign('assign_id')->references('id')->on('sm_fees_assigns')->onDelete('cascade');

            $table->integer('bank_id')->nullable()->unsigned();
            $table->foreign('bank_id')->references('id')->on('sm_bank_accounts')->onDelete('cascade');

            $table->integer('fees_discount_id')->nullable()->unsigned();
            $table->foreign('fees_discount_id')->references('id')->on('sm_fees_discounts')->onDelete('cascade');

            $table->integer('fees_type_id')->nullable()->unsigned();
            $table->foreign('fees_type_id')->references('id')->on('sm_fees_types')->onDelete('cascade');
            $table->integer('record_id')->nullable()->unsigned();
            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('direct_fees_installment_assign_id')->nullable()->unsigned();
            $table->integer('installment_payment_id')->nullable()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_fees_payments');
    }
}
