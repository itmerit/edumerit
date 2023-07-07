<?php

use App\CustomResultSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;

class CreateCustomResultSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_result_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('exam_type_id')->nullable();
            $table->float('exam_percentage')->nullable();
            $table->string('merit_list_setting');

            $table->string("print_status")->nullable();
            $table->string("profile_image")->nullable();
            $table->string("header_background")->nullable();
            $table->string("body_background")->nullable();

            $table->integer('academic_year')->nullable();;              
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            $table->timestamps();
        });

        $store = new CustomResultSetting();
        $store->merit_list_setting = 'total_mark';
        $store->print_status = "image";
        $store->save();
        $store = CustomResultSetting::first();
        if(!$store){
            $store = new CustomResultSetting();
            $store->merit_list_setting = 'total_mark';
            $store->print_status = "image";
        }
        $store->profile_image = "image";
        $store->header_background = "header";
        $store->body_background = "body";
        $store->save();

        $permission = InfixModuleInfo::find(5000);
        if(!$permission){
            $permission = new InfixModuleInfo();
            $permission->id = 5000;
            $permission->module_id = 9;
            $permission->parent_id = 870;
            $permission->type = '2';
            $permission->is_saas = 0;
            $permission->name = "Position Setup";
            $permission->route = "exam-report-position";
            $permission->lang_name = "position_setup";
            $permission->active_status = 1;
            $permission->created_by = 1;
            $permission->updated_by = 1;
            $permission->school_id = 1;
            $permission->save();
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_result_settings');
    }
}
