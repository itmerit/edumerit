<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\ParentRegistration\Entities\SmStudentField;
use Modules\RolePermission\Entities\InfixModuleStudentParentInfo;

class AddColumToInfixModuleStudentParentInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('infix_module_student_parent_infos', function (Blueprint $table) {
            if (!Schema::hasColumn('infix_module_student_parent_infos', 'admin_section')) {
                $table->string('admin_section')->nullable();
            }
        });

        $examPlans = InfixModuleStudentParentInfo::whereIn('id', [2500, 2501, 2502, 2503])->delete();
        $infix_module_student_parent_infos = [
            [2500, 50, 0, '1', 1,'ExamPlan','','ExamPlan','flaticon-test',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [2501, 50, 2500, '2', 1,'Admit Card','admit/card','','chat_box', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [2502, 50, 0, '1', 2,'ExamPlan','','ExamPlan','flaticon-test',1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22'],
            [2503, 50, 2502, '2', 2,'Admit Card','admit/card','','chat_box', 1, 1, 1, 1, '2019-07-25 02:21:21', '2019-07-25 04:24:22']
        ];
        foreach ($infix_module_student_parent_infos as $key=>$value) {
            $check_exit=InfixModuleStudentParentInfo::find($value[0]);
            if($check_exit){
                continue;
            }
            $examPlan = new InfixModuleStudentParentInfo;
            $examPlan->id = $value[0];
            $examPlan->module_id = $value[1];
            $examPlan->parent_id = $value[2];
            $examPlan->type = $value[3];
            $examPlan->user_type = $value[4];
            $examPlan->name = $value[5];
            $examPlan->route = $value[6];
            $examPlan->lang_name = $value[7];
            $examPlan->icon_class = $value[8];
            $examPlan->active_status = $value[9];
            $examPlan->created_by = $value[10];
            $examPlan->updated_by = $value[11];
            $examPlan->school_id = $value[12];
            $examPlan->created_at = $value[13];
            $examPlan->updated_at = $value[14];
            $examPlan->save();
        }

        $ids =
            [
            20 => 'fees', 
            1156 => 'fees', 
            22 => 'academics', 
            23 => 'homework',
            26 => 'study_material', 
            35 => 'student_info', 
            36 => 'examination', 
            39 => 'leave', 
            45 => 'online_exam', 
            48 => 'communicate', 
            49 => 'academics', 
            50 => 'academics', 
            51 => 'library', 
            54 => 'transport', 
            55 => 'dormitory', 
            800 => 'lesson', 
            1124 => 'wallet',
            900 => 'chat', 
            71 => 'fees', 
            1157 => 'fees', 
            72 => 'academics', 
            73 => 'admin_section', 
            75 => 'student_info', 
            76 => 'examination', 
            2016 => 'online_exam',
            80 => 'leave', 
            85 => 'communicate',
            86 => 'academics', 
            87 => 'academics', 
            88 => 'library', 
            91 => 'transport', 
            92 => 'dormitory', 
            97 => 'lesson', 
            1127 => 'wallet', 
            910 => 'chat',
                2500 => 'examplan',
                2502 => 'examplan',
            ];
        foreach ($ids as $key => $admin_section) {
            $permission = InfixModuleStudentParentInfo::where('id', $key)->first();
            if($permission) 
            {
                $permission->admin_section = $admin_section;
                $permission->save();
            }
        }

        Schema::table('sm_student_registration_fields', function (Blueprint $table) {
            if (!Schema::hasColumn($table->getTable(), 'admin_section')) {
                $table->string('admin_section')->nullable();
            }
        });

        \App\Models\SmStudentRegistrationField::whereIn('field_name', ['route', 'vehicle'])->update([
            'admin_section' => 'transport'
        ]);
        \App\Models\SmStudentRegistrationField::whereIn('field_name', ['dormitory_name', 'room_number'])->update([
            'admin_section' => 'dormitory'
        ]);
        \App\Models\SmStudentRegistrationField::whereIn('field_name', ['custom_field'])->update([
            'admin_section' => 'custom_field'
        ]);

        if(Schema::hasTable('sm_student_fields')) {
            Schema::table('sm_student_fields', function (Blueprint $table) {
                if (!Schema::hasColumn($table->getTable(), 'admin_section')) {
                    $table->string('admin_section')->nullable();
                }
            });

            if(class_exists(SmStudentField::class)){
                SmStudentField::whereIn('field_name', ['route', 'vehicle'])->update([
                    'admin_section' => 'transport'
                ]);
                SmStudentField::whereIn('field_name', ['dormitory_name', 'room_number'])->update([
                    'admin_section' => 'dormitory'
                ]);
                SmStudentField::whereIn('field_name', ['custom_field'])->update([
                    'admin_section' => 'custom_field'
                ]);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('infix_module_student_parent_infos', function (Blueprint $table) {
            //
        });
    }
}
