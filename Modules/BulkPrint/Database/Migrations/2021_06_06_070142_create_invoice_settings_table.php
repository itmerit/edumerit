<?php

use App\SmLanguagePhrase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Log;
use Modules\BulkPrint\Entities\InvoiceSetting;
use Modules\RolePermission\Entities\InfixModuleInfo;
use Modules\RolePermission\Entities\InfixPermissionAssign;

class CreateInvoiceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('per_th')->default(2);
            $table->string('prefix')->nullable();
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
            //code...
            $invoiceSettings=new InvoiceSetting;
            $invoiceSettings->per_th=2;
            $invoiceSettings->prefix='SPN';
            $invoiceSettings->save();


            $d=[
                [0,'bulk_print','Bulk Print','Bulk Print','বাল্ক প্রিন্ট','Bulk Print'],
                [0,'fees_invoice_settings','Fees invoice Settings','Fees invoice Settings','ফি চালানের সেটিংস','Fees invoice Settings'],
                [0,'fees_invoice_bulk_print','Fees invoice Bulk Print','Fees invoice Bulk Print','ফি চালানের বাল্ক প্রিন্ট','Fees invoice Bulk Print'],
                [0,'payroll_bulk_print','Payroll Bulk Print','Payroll Bulk Print','পেওরোল বাল্ক প্রিন্ট','Payroll Bulk Print'],
                [0,'bulk','Bulk','Bulk','বাল্ক','Bulk'],
                [0,'per','Per','Per','প্রতি','Per'],
                [0,'part','Part','Part','অংশ','Part'],
                [0,'is_showing','Is Showing','Is Showing','দেখাচছ','Is Showing'],
                [0, 'format_standard_three_character', 'Standard Format 3 Character', 'Standard Format 3 Character', 'স্ট্যান্ডার্ড ফরমেট ৩ ক্যারেক্টার', 'Standard Format 3 Character'],
                [0,'prefix','Prefix','Prefix','প্রারম্ভে স্থাপন করা','Prefix'],
                [0, 'staff_id_card', 'Staff ID Card', 'Credencial de personaro', 'কর্মী আইডি কার্ড', 'Carde didentité personnelle']

            ];
            foreach ($d as $row) {
                $s = SmLanguagePhrase::where('default_phrases', trim($row[1]))->first();
                if (empty($s)) {
                    $s = new SmLanguagePhrase();
                }
            
                $s->modules = $row[0];
                $s->default_phrases = trim($row[1]);
                $s->en = trim($row[2]);
                $s->es = trim($row[3]);
                $s->bn = trim($row[4]);
                $s->fr = trim($row[5]);
                $s->save();
            }
            $admins=[920,921,922,923,924,925,926];

            foreach ($admins as $key => $value) {
               $check= InfixModuleInfo::find($value);
               if($check){

                $permission = new InfixPermissionAssign();
                $permission->module_id = $value;
                $permission ->module_info = InfixModuleInfo::find($value)->name;
                $permission->role_id = 5;
                $permission->save();
               }
            }


        } catch (\Throwable $th) {
            
            Log::info($th);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_settings');
    }
}
