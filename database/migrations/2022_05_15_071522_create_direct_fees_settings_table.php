<?php

use App\SmSchool;
use App\InfixModuleManager;
use App\Models\DirectFeesSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectFeesSettingsTable extends Migration
{
    
    public function up()
    {
        Schema::create('direct_fees_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('fees_installment')->default(0);
            $table->boolean('fees_reminder')->default(0);
            $table->integer('reminder_before')->default(5);
            $table->integer('no_installment')->default(0);
            $table->integer('due_date_from_sem')->default(10);
            $table->integer('end_day')->nullable();         
            $table->unsignedInteger('academic_id')->nullable();
            $table->unsignedInteger('school_id')->nullable();
            $table->foreign('school_id')->on('sm_schools')->references('id')->cascadeOnDelete();
            $table->timestamps();
        });

        $module_name = "University";
        $schools = SmSchool::all();
        foreach($schools as $school){
            $new = new DirectFeesSetting();
            $new->school_id = $school->id;
            $new->academic_id = getAcademicId();
            $new->save();

            Schema::table('sm_general_settings', function (Blueprint $table)use($module_name) {
                if (!Schema::hasColumn('sm_general_settings', $module_name)) {
                    $table->unsignedBigInteger($module_name)->nullable();
                }
    
                if (!Schema::hasColumn('sm_general_settings', 'direct_fees_assign')) {
                    $table->boolean('direct_fees_assign')->default(0);
                }
            }); 
        }
        $check = InfixModuleManager::where('name', $module_name)->first();
        if (!$check) {
            $s = new InfixModuleManager();
            $s->name = $module_name;
            $s->email = 'support@spondonit.com';
            $s->notes = "Manage Your University Using This Module";
            $s->version = 1.0;
            $s->update_url = url('/');
            $s->is_default = 0;
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        }

        $class_id = "class_id";
        $section_id = "section_id";
        $direct_fees_installment_assign_id = "direct_fees_installment_assign_id";

        Schema::table('sm_fees_masters', function (Blueprint $table)use($class_id) {
            if (!Schema::hasColumn('sm_fees_masters', $class_id)) {
                $table->unsignedBigInteger($class_id)->nullable();
            }
        });

        Schema::table('sm_fees_masters', function (Blueprint $table)use($section_id) {
            if (!Schema::hasColumn('sm_fees_masters', $section_id)) {
                $table->unsignedBigInteger($section_id)->nullable();
            }
        });
    

        Schema::table('sm_fees_assigns', function (Blueprint $table)use($class_id) {
            if (!Schema::hasColumn('sm_fees_assigns', $class_id)) {
                $table->unsignedBigInteger($class_id)->nullable();
            }
        });
    
        Schema::table('sm_fees_assigns', function (Blueprint $table)use($section_id) {
            if (!Schema::hasColumn('sm_fees_assigns', $section_id)) {
                $table->unsignedBigInteger($section_id)->nullable();
            }
        });

        Schema::table('sm_fees_payments', function (Blueprint $table)use($direct_fees_installment_assign_id) {
            if (!Schema::hasColumn('sm_fees_payments', $direct_fees_installment_assign_id)) {
                $table->unsignedBigInteger($direct_fees_installment_assign_id)->nullable();
            }
        });


        if(moduleStatusCheck('ParentRegistration')){
            $columns = ['un_session_id','un_faculty_id','un_department_id','un_academic_id','un_semester_id','un_semester_label_id','un_section_id'];
            foreach($columns as $column){
                Schema::table('sm_student_registrations', function (Blueprint $table)use($column) {
                    if (!Schema::hasColumn('sm_student_registrations', $column)) {
                        $table->unsignedBigInteger($column)->nullable();
                    }
                });
            }
        }
        
    
    }


    
}

