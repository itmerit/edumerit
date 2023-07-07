<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\BulkPrint\Entities\FeesInvoiceSetting;

class CreateFeesInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fees_invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('per_th')->default(2);
            $table->string('invoice_type')->default('invoice');
            $table->tinyInteger('student_name')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('student_section')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('student_class')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('student_roll')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('student_group')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('student_admission_no')->default(1)->comment('0=No, 1=Yes');
            
            $table->string('footer_1',255)->default('Parent/Student')->nullable();
            $table->string('footer_2',255)->default('Casier');
            $table->string('footer_3',255)->default('Officer');

            $table->tinyInteger('signature_p')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('signature_c')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('signature_o')->default(1)->comment('0=No, 1=Yes');

            $table->tinyInteger('c_signature_p')->default(1)->comment('0=No, 1=Yes');
            $table->tinyInteger('c_signature_c')->default(0)->comment('0=No, 1=Yes');
            $table->tinyInteger('c_signature_o')->default(1)->comment('0=No, 1=Yes');
           
            $table->string('copy_s',255)->default('Parent/Student')->nullable();
            $table->string('copy_o',255)->default('Office');
            $table->string('copy_c',255)->default('Casier');


            $table->timestamps();

            $table->text('copy_write_msg',255)->nullable();

            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });

        try {
            $storeInvoice=new FeesInvoiceSetting();
            $storeInvoice->per_th=2;
            $storeInvoice->invoice_type='invoice';
            $storeInvoice->save();
        } catch (\Throwable $th) {
            Log::info($th);
        }
    }

    public function down()
    {
        Schema::dropIfExists('fees_invoice_settings');
    }
}
