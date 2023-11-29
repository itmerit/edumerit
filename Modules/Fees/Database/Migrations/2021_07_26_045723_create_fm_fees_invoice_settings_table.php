<?php

use App\SmSchool;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Fees\Entities\FmFeesInvoiceSettings;
use Modules\RolePermission\Entities\InfixModuleInfo;

class CreateFmFeesInvoiceSettingsTable extends Migration
{
    public function up()
    {
        Schema::create('fm_fees_invoice_settings', function (Blueprint $table) {
            $table->id();
            $table->text('invoice_positions')->nullable();
            $table->string('uniq_id_start')->nullable();
            $table->string('prefix')->nullable();
            $table->integer('class_limit')->nullable();
            $table->integer('section_limit')->nullable();
            $table->integer('admission_limit')->nullable();
            $table->string('weaver')->nullable();
            $table->integer('school_id')->nullable();
            $table->timestamps();
        });


        $schools = SmSchool::get();
        foreach ($schools as $school) {
            $store = new FmFeesInvoiceSettings();
            $store->invoice_positions = '[{"id":"prefix","text":"prefix"},{"id":"admission_no","text":"Admission No"},{"id":"class","text":"Class"},{"id":"section","text":"Section"}]';
            $store->uniq_id_start = "0011";
            $store->prefix = 'infixEdu';
            $store->class_limit = 3;
            $store->section_limit = 1;
            $store->admission_limit = 3;
            $store->weaver = 'amount';
            $store->school_id = $school->id;
            $store->save();
        }

        $feesInfixModuleInfos = [
            [1130, 250, 0, '1', 0,'Fees','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1131, 250, 1130, '2', 0,'Fees Group','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1132, 250, 1131, '3', 0,'Add','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1133, 250, 1131, '3', 0,'Edit','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1134, 250, 1131, '3', 0,'Delete','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
    
            [1135, 250, 1130, '2', 0,'Fees Type','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1136, 250, 1135, '3', 0,'Add','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1137, 250, 1135, '3', 0,'Edit','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1138, 250, 1135, '3', 0,'Delete','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
    
            [1139, 250, 1130, '2', 0,'Fees Invoice','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1140, 250, 1139, '3', 0,'Add','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1141, 250, 1139, '3', 0,'View Payment','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1142, 250, 1139, '3', 0,'View','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1143, 250, 1139, '3', 0,'print','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1144, 250, 1139, '3', 0,'Add Payment','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1145, 250, 1139, '3', 0,'Edit','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1146, 250, 1139, '3', 0,'Delete','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1147, 250, 1139, '3', 0,'Fees Collect','','','',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
    
            [1148, 250, 1130, '2', 0,'Bank Payment','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1149, 250, 1148, '3', 0,'Search','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1150, 250, 1148, '3', 0,'Approve Payment','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1151, 250, 1148, '3', 0,'Reject Payment','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
    
            [1152, 250, 1130, '2', 0,'Fees Invoice Settings','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1153, 250, 1152, '3', 0,'Update','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],

            // 1156,1157 Student and Parent Fees Invoice
            // 1162 Bulk Print

            [1154, 250, 1130, '2', 0,'Report','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1155, 250, 1154, '3', 0,'Fees Due','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1158, 250, 1154, '3', 0,'Fine Report','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1159, 250, 1154, '3', 0,'Payment Report','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1160, 250, 1154, '3', 0,'Balance Report','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [1161, 250, 1154, '3', 0,'Waiver Report','','','', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
        ];

        try {
            foreach ($feesInfixModuleInfos as $data) {
                $checkExist = InfixModuleInfo::find($data[0]);
                if ($checkExist) {
                     continue;
                } 
                $feesInvoiceData = new InfixModuleInfo;
                $feesInvoiceData->id = $data[0];
                $feesInvoiceData->module_id = $data[1];
                $feesInvoiceData->parent_id = $data[2];
                $feesInvoiceData->type = $data[3];
                $feesInvoiceData->is_saas = $data[4];
                $feesInvoiceData->name = $data[5];
                $feesInvoiceData->route = $data[6];
                $feesInvoiceData->lang_name = $data[7];
                $feesInvoiceData->icon_class = $data[8];
                $feesInvoiceData->active_status = $data[9];
                $feesInvoiceData->created_by = $data[10];
                $feesInvoiceData->updated_by = $data[11];
                $feesInvoiceData->school_id = $data[12];
                $feesInvoiceData->created_at = $data[13];
                $feesInvoiceData->updated_at = $data[14];
                $feesInvoiceData->save();
            }

        } catch (\Throwable$th) {
            Log::info($th);
        }
    }

    public function down()
    {
        Schema::dropIfExists('fm_fees_invoice_settings');
    }
}
