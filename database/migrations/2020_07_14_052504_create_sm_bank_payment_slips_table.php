<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSmBankPaymentSlipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_bank_payment_slips', function (Blueprint $table) {
             $table->bigIncrements('id');

            $table->date('date');
            $table->float('amount', 10, 2)->nullable();
            $table->string('slip')->nullable();
            $table->text('note')->nullable();
            $table->integer('bank_id')->nullable();

            $table->tinyInteger('approve_status')->default(0)->comment('0 pending, 1 approve');
            $table->string('payment_mode')->comment('Bk= bank, Cq=Cheque');
            $table->text('reason')->nullable();

            $table->integer('fees_discount_id')->nullable()->unsigned();
            $table->foreign('fees_discount_id')->references('id')->on('sm_fees_discounts')->onDelete('restrict');

            $table->integer('fees_type_id')->nullable()->unsigned();
            // $table->foreign('fees_type_id')->references('id')->on('sm_fees_types')->onDelete('restrict');
            $table->integer('record_id')->nullable()->unsigned();
            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->integer('class_id')->nullable()->unsigned();
            // $table->foreign('class_id')->references('id')->on('sm_classes')->onDelete('cascade');

            $table->integer('assign_id')->nullable()->unsigned();
            // $table->foreign('assign_id')->references('id')->on('sm_fees_assigns')->onDelete('cascade');

            $table->integer('section_id')->nullable()->unsigned();
            // $table->foreign('section_id')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('restrict');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            
            $table->integer('child_payment_id')->nullable();
            $table->integer('installment_id')->nullable();
            // $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            $table->timestamps();
            $table->integer('active_status')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_bank_payment_slips');
    }
}