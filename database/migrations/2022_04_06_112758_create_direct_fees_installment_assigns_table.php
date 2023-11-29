<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectFeesInstallmentAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_fees_installment_assigns', function (Blueprint $table) {
            $table->id();
            $table->integer('fees_installment_id');
            $table->text('fees_master_ids')->nullable();
            $table->float('amount', 10, 2)->nullable();
            $table->float('paid_amount', 10, 2)->nullable();
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_mode', 100)->nullable();
            $table->text('note')->nullable();
            $table->string('slip')->nullable();
            $table->tinyInteger('active_status')->default(0);

            $table->text('assign_ids')->nullable();
        
            $table->integer('bank_id')->nullable()->unsigned();
            $table->foreign('bank_id')->references('id')->on('sm_bank_accounts')->onDelete('cascade');

            $table->float('discount_amount', 10, 2)->default(0)->nullable();  
            
            $table->integer('fees_discount_id')->nullable()->unsigned();
            $table->foreign('fees_discount_id')->references('id')->on('sm_fees_discounts')->onDelete('cascade');
                                 
        
            $table->integer('fees_type_id')->nullable()->unsigned();
            $table->foreign('fees_type_id')->references('id')->on('sm_fees_types')->onDelete('cascade');

            $table->integer('student_id')->nullable()->unsigned();
            $table->foreign('student_id')->references('id')->on('sm_students')->onDelete('cascade');

            $table->integer('record_id')->nullable()->unsigned();
            $table->integer('collected_by')->nullable()->default(1)->unsigned();

            $table->integer('academic_id')->nullable()->default(1)->unsigned();

            $table->integer('created_by')->nullable()->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
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
        Schema::dropIfExists('direct_fees_installment_assigns');
    }
}
